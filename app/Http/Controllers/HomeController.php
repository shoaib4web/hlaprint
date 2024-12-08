<?php



namespace App\Http\Controllers;



use PDF;

use App\Events\RequestRecieved;
use App\Events\PaymentReceived;




use Howtomakeaturn\PDFInfo\PDFInfo;



use App\Models\PrintJob;

use App\Models\PriceOptions;



use Illuminate\Http\Request;



use Illuminate\Support\Facades\DB;



use Rawilk\Printing\Facades\Printing;



use Illuminate\Support\Facades\Http;



use Illuminate\Support\Facades\File;



use TCPDF;



use App\Models\PrintJobsModel;



use App\Models\Shops;



use App\Models\Invoice;



use App\Models\Transaction;



use App\Models\Color_size;



use App\Models\Shop_printers;

use Illuminate\Support\Carbon;



use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;





class HomeController extends Controller

{

    public function verifyCredentials(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = User::where('email', $username)->first();

    // If user exists and the password matches
        if ($user && Hash::check($password, $user->password)) {
            // Credentials are valid

           $shopsData = Shops::where('owner_id',$user->id)->first();
           if(!$shopsData)
           {
            $shopid = $user->shop_id;
           }
           else {
            $shopid = $shopsData->id;
           }
            return response()->json(['success' => true, 'shop_id' => $shopid]);
        } else {
            // Invalid credentials
            return response()->json(['success' => false, 'error' => 'Invalid credentials'], 401);
        }





    }

    public function Dashboard()

    {

     $currentDate = Carbon::now()->toDateString(); // Get the current date (YYYY-MM-DD)

$data["orders"] = PrintJob::where('shop_id', Auth::user()->shop_id)
    ->whereDate('created_at', $currentDate)
    ->count();

$data["users"] = PrintJob::distinct("phone")
    ->where('shop_id', Auth::user()->shop_id)
    ->whereDate('created_at', $currentDate)
    ->count("phone");

$data["revenu"] = Transaction::where('shop_id', Auth::user()->shop_id)
    ->where('type', 'online')->where('status','A')
    ->whereDate('created_at', $currentDate)
    ->sum("amount");



        $data["grossIncome"] = Transaction::where('shop_id', Auth::user()->shop_id)
    ->where('type', 'cash')->where('status','Approved')
    ->whereDate('created_at', $currentDate)
    ->sum("amount");



        // echo Auth::user()->role;exit;



        if (Auth::user()->role == "superadmin") {

            return view("admin.dashboard", compact("data"));

        } elseif (

            Auth::user()->role == "shopowner" ||

            Auth::user()->role == "shopmanager" || Auth::user()->role == "cashier"

        ) {

            return view("admin.dashboard", compact("data"));

        } else {

            return view("dashboard", compact("data"));

        }

    }

    public function toggleSystem($shop_id,$state)
    {
        $shop = Shops::where('id',$shop_id)->first();

        $shop->online = (bool)$state;

        if($shop->save())
        {
              $response = ['success'=>'true','status' => (bool) $state];
        }
        else
        {
            $response = ['error' => 'true'];
        }

        return response()->json($response);



    }



    //---------------------------------------------------------------------- Submit Code









   public function getOptions(Request $request, $code, $shop = null)

   {


    if ($shop) {

        $shop_id = $shop;

    } else {

        $shop_id = "0";

    }







    $code_record = DB::table("user_codes")



        ->where("code", $code)

        ->where("status", 0)

        ->first();



    if ($code_record != null) {

        $printJob = DB::table("print_jobs")



            ->where("code", $code)



            ->get();



        $file = "Dummy";



        $phone = $printJob[0]->phone;







        $id = $printJob[0]->id;


        $priceOptions = PriceOptions::where('shop_id', $shop_id)->get();


        return view(

            "english.arraysenglish",



            compact("file", "phone", "code", "shop", "printJob", "id", 'priceOptions')

        );

    } else {

        $message = "The Link has expired";



        return view("english.error", compact("message"));

    }

}



    public function getArabicViewOptions($code, $shop_id = null)

    {


        if ($shop_id) {

            $shop = $shop_id;

        } else {

            $shop = "0";

        }

        $printRequest = new PrintRequestsController();
            if(!$printRequest->onlineCheck($shop_id))
            {
                $message = "انتهت صلاحية الرابط";



                return view("arabic.error", compact("message"));
            }





        $code_record = DB::table("user_codes")



            ->where("code", $code)

            ->where("status", 0)

            ->first();



        if ($code_record != null) {

            $printJob = DB::table("print_jobs")



                ->where("code", $code)



                ->get();



            $file = "Dummy";

            if($printJob->isEmpty())
            {

                $message = "انتهت صلاحية الرابط";



                return view("arabic.error", compact("message"));


            }



            $phone = $printJob[0]->phone;







            $id = $printJob[0]->id;


            $priceOptions = PriceOptions::where('shop_id', $shop_id)->get();


            return view(

                "arabic.arraysarabicMulti",



                compact("file", "phone", "code", "shop", "printJob", "id", 'priceOptions')

            );

        } else {

            $message = "انتهت صلاحية الرابط";



            return view("arabic.error", compact("message"));

        }

    }

    public function getTestArabicViewOptions($code, $shop_id = null)

    {


        if ($shop_id) {

            $shop = $shop_id;

        } else {

            $shop = "0";

        }







        $code_record = DB::table("user_codes")



            ->where("code", $code)

            ->where("status", 0)

            ->first();



        if ($code_record != null) {

            $printJob = DB::table("print_jobs")



                ->where("code", $code)



                ->get();



            $file = "Dummy";

            if($printJob->isEmpty())
            {

                $message = "انتهت صلاحية الرابط";



                return view("arabic.error", compact("message"));


            }



            $phone = $printJob[0]->phone;







            $id = $printJob[0]->id;


            $priceOptions = PriceOptions::where('shop_id', $shop_id)->get();


            return view(

                "arabic.TestMultiple",



                compact("file", "phone", "code", "shop", "printJob", "id", 'priceOptions')

            );

        } else {

            $message = "انتهت صلاحية الرابط";



            return view("arabic.error", compact("message"));

        }

    }



//

  public function  getMonthlyInvoiceID($shopID)
  {
        // Get the first day of the current month
        $startOfMonth = Carbon::now()->startOfMonth();

        // Get the last day of the current month
        $endOfMonth = Carbon::now()->endOfMonth();

        // Query invoices for the current month
        $invoices = Invoice::where('shop_id', $shopID)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->count();

        return $invoices + 1;
  }


    //---------------------------------------------------------------------- Submit Document

    public function arabicSubmitDocument(Request $request)

    {








        $check_code = PrintJob::where("code", $request->code)->get();

        if($request->type == "cash"){

            DB::table("user_codes")



                ->where("code", $request->code)



                ->update(["status" => "0"]);



        }else{

            DB::table("user_codes")



                ->where("code", $request->code)



                ->update(["status" => "0"]);

        }





        $pJIds = [];
        $shopID = '';





        foreach ($check_code as $index => $c) {

            $colors = $request->color;

            $c->type= $request->type;
            $shopID = $c->shop_id;



            if ($colors[$index]) {

                $c->color = $colors[$index];

            }



            $copies = $request->copies;



            if ($copies[$index]) {

                $c->copies = $copies[$index];

            }



            $page_size = $request->pageSize;



            if ($page_size[$index]) {

                $pageSize = explode(" ",$page_size[$index]);



                $c->page_size = $pageSize[0];

            }



            $from = $request->from;



            if ($from) {

                if ($from[$index]) {

                    $c->pages_start = $from[$index];

                }

            } else {

                $c->pages_start = 1;

            }



            $to = $request->to;



            if ($to) {

                if ($to[$index]) {

                    $c->page_end = $to[$index];

                }

            } else {

                $c->page_end = 1;

            }



            $sides = $request->sides;



            if ($sides[$index]) {

                if ($sides[$index] == "one") {

                    $c->double_sided = "false";

                } else {

                    $c->double_sided = "true";

                }

            }

            $orientation = $request->orientation??$request->orientation;

            if($orientation && $orientation[$index]){
                $c->page_orientation = $orientation[$index];
            }



            array_push($pJIds, $c->id);



            $c->save();

        }


        $printRequest = new PrintRequestsController();

        if(!$printRequest->onlineCheck($shopID))

             {
                 return view('arabic.error')->with("message","النظام غير متصل");
                 }




        $price = 0;



        $amountToPay = 0;

        $priceVariations = array();



        foreach ($check_code as $u) {




        $total_pages = ($u->page_end - $u->pages_start) + 1;
        $total_pages = $u->copies * $total_pages;



        $priceQuery = array();
        if($u->color == 'true')
        {
            $priceQuery['color'] = 'color';
        }
        else {
            $priceQuery['color'] = 'mono';
        }

        if($u->double_sided == 'true')
        {
            $priceQuery['double_sided'] = 'doubleSided';
        }
        else {
            $priceQuery['double_sided'] = 'oneSide';
        }
        $priceQuery['shop_id'] = $u->shop_id;
        $priceQuery['page_size'] = $u->page_size;

        $variationString = $priceQuery['page_size']."_".$priceQuery['color']."_".$priceQuery['double_sided'];


        $priceVariation[$variationString][] = $total_pages;





        }
        $string = print_r($priceVariation, true);

        Log::info("Price Variation for Shop ".$shopID." ".$string);


        $amountToPay = $this->calculateIntervalTotals($priceVariation, $shopID);

        // code to add admin comission tax start

        $temp = $amountToPay*1.15;



        if($temp<1)

        {

            $temp = 1.15;

        }



        $temp = round($temp,2);

        $amountToPay = $temp;

        // code to add admin comission tax end



        if ($amountToPay < 1) {

            $amountToPay = 1.15;

        }

        // echo $amountToPay;exit;

       $transaction = Transaction::where('print_job_id', json_encode($pJIds))->first(); 

       if(!$transaction)
       {
            $transaction = new Transaction();
       }








        $transaction->print_job_id = json_encode($pJIds);

         $transaction->shop_id = $shopID;



        $transaction->invoice_id = 0;



        $transaction->amount = $amountToPay;



        $transaction->currency = "SAR";



        $transaction->type = $request->type;



        $transaction->status = "Queued";



        $transaction->date = date("Y-m-d H:i:s");



        $transaction->created_at = date("Y-m-d H:i:s");



        //  print_r($transaction);exit;



        $trans_update = $transaction->save();

        foreach($pJIds as $printJobId)
       {
           $printJob = PrintJob::find($printJobId);
           $printJob->transaction_id = $transaction->id;
           $printJob->save();
       }



        if ($trans_update) {

            Invoice::where("trans_id", $transaction->id)->delete();



            $invoice = new Invoice();



            $invoice->trans_id = $transaction->id; // Assuming a user association



            $invoice->invoice_number = date("is");



            $invoice->date = now();

            $invoice->shop_id = $shopID;

            $invoice->monthly_id = $this->getMonthlyInvoiceID($shopID);



            $invoice->amount = $amountToPay;



             $invoice->save();



            // Associate the transaction with the invoice



            $transaction->invoice_id = $invoice->id;



             $transaction->update();

        }



        if ($check_code && $request->type == "cash") {



            $type = "cash";





            $modalCode = '<div class="modal confirmModal successModal" id="cashOrder">

            <div class="container" style="margin-top:50px;!important">

                <div class="close" onclick="successModal()">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">

                    <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E" />

                </svg>

                </div>

                <img src="http://hlaprint.synet.com.pk/public/assets/img/lineIcon.png" alt="" style="width: 9.2rem; height: 9.2rem;">

                <p class="infoP">الرجاء الدفع عند الكاشير</p>

                <a style="font-size:15px;padding:1rem 2rem;background-color:#007FFF;color:white" class="btn " onclick="successModal()" type="">نعم</a>

            </div>';

            session()->put('modal_code', $modalCode);

            event(new RequestRecieved($transaction->id));

             return redirect()->back();

            //  return view('arabic.success');





        } elseif ($check_code) {

            $type = $request->type;






            $update_transaction = Transaction::where('print_job_id', json_encode($pJIds))
                                 ->latest()
                                 ->first();



            // print_r($update_transaction);exit;



            // $payement = new PaymentController();



            // $trans = $payement->initiatePayment(

            //     $update_transaction->amount,



            //     $update_transaction->id,



            //     "arabic", $type

            // );





            // $redirect_url = $trans["redirect_url"];



            // $trans_ref = $trans["tran_ref"];



            // $update_transaction->trans_id = $trans_ref;



            // $update_transaction->update();



            // if ($redirect_url) {

            //     return redirect($redirect_url);

            // } else {

            //     return redirect()

            //         ->route("englishProcessing")



            //         ->with("message", "Your print has been submitted");

            // }




            event(new RequestRecieved($transaction->id));

            return view("english.processing")->with("message", "قيد المعالجة")->with("trans_id", $transaction->id);

        } else {

            return "Code Not Found";

        }



    }

    public function PrintCodeInput($code, $shop) {
        
        $codeCheck = DB::table("user_codes")->where("code", $code)->where('status', "0")->first();
        if(!isset($codeCheck) || !$codeCheck )
        {
            return view("self-service.codeInput")->with("message","Please Enter a Valid number");
        }
        
        if($codeCheck->status)
        {
            return view("self-service.codeInput")->with("message","This code has already been used");
        }
        
        $check_code = PrintJob::where("code", $code)->get();
        $PJIds = [];

        foreach($check_code as $printJob)
        {
            array_push($PJIds, $printJob->id);
        }

        $transaction = Transaction::where('print_job_id', json_encode($PJIds))->first();
        if ($transaction) {
            event(new PaymentReceived($transaction->id));
            return view("self-service.processing")->with("trans_id",$transaction->id);
        } else {
            return view("self-service.codeInput")->with("message","Please Enter a Valid number");
        }
    }
    
    public function getCodeInput()
    {
        return view("self-service.codeInput");
    }

    public function PaymentLoader(Request $request)
    {
        return view("english.testProcessing")->with("message","Yo Habibi!")->with("trans_id",69);
    }


    public function SplitTesting()
    {
        $payement = new PaymentController();



        $trans = $payement->splitPaymentTest();

           return json_encode($trans);

    }


    public function paymentStart($trans_id)
    {
        $payement = new PaymentController();

        $update_transaction = Transaction::find($trans_id);

     $trans = $payement->initiatePayment(

                $update_transaction->amount,



                $update_transaction->id,



                "arabic", $update_transaction->type

            );

           if(isset($trans["redirect_url"]))
           {
            $redirect_url = $trans["redirect_url"];



            $trans_ref = $trans["tran_ref"];



            $update_transaction->trans_id = $trans_ref;



            $update_transaction->update();



            if ($redirect_url) {

                return redirect($redirect_url);

            }
           }
           else {
            return view('arabic.error')->with("message",$trans["message"]);
           }






    }



    public function englishSubmitDocument(Request $request)

    {


        $printRequest = new PrintRequestsController();

        if(!$printRequest->onlineCheck(7))

             {
                 return view('arabic.error')->with("message","النظام غير متصل");
                 }





         $check_code = PrintJob::where("code", $request->code)->get();

         if($request->type == "cash"){

             DB::table("user_codes")



                 ->where("code", $request->code)



                 ->update(["status" => "0"]);



         }else{

             DB::table("user_codes")



                 ->where("code", $request->code)



                 ->update(["status" => "0"]);

         }





         $pJIds = [];
         $shopID = '';





         foreach ($check_code as $index => $c) {

             $colors = $request->color;

             $c->type= $request->type;
             $shopID = $c->shop_id;



             if ($colors[$index]) {

                 $c->color = $colors[$index];

             }



             $copies = $request->copies;



             if ($copies[$index]) {

                 $c->copies = $copies[$index];

             }



             $page_size = $request->pageSize;



             if ($page_size[$index]) {

                 $pageSize = explode(" ",$page_size[$index]);



                 $c->page_size = $pageSize[0];

             }



             $from = $request->from;



             if ($from) {

                 if ($from[$index]) {

                     $c->pages_start = $from[$index];

                 }

             } else {

                 $c->pages_start = 1;

             }



             $to = $request->to;



             if ($to) {

                 if ($to[$index]) {

                     $c->page_end = $to[$index];

                 }

             } else {

                 $c->page_end = 1;

             }



             $sides = $request->sides;



             if ($sides[$index]) {

                 if ($sides[$index] == "one") {

                     $c->double_sided = "false";

                 } else {

                     $c->double_sided = "true";

                 }

             }



             array_push($pJIds, $c->id);



             $c->save();

         }







         $price = 0;



         $amountToPay = 0;

         $priceVariations = array();



         foreach ($check_code as $u) {




         $total_pages = ($u->page_end - $u->pages_start) + 1;
         $total_pages = $u->copies * $total_pages;



         $priceQuery = array();
         if($u->color == 'true')
         {
             $priceQuery['color'] = 'color';
         }
         else {
             $priceQuery['color'] = 'mono';
         }

         if($u->double_sided == 'true')
         {
             $priceQuery['double_sided'] = 'doubleSided';
         }
         else {
             $priceQuery['double_sided'] = 'oneSide';
         }
         $priceQuery['shop_id'] = $u->shop_id;
         $priceQuery['page_size'] = $u->page_size;

         $variationString = $priceQuery['page_size']."_".$priceQuery['color']."_".$priceQuery['double_sided'];


         $priceVariation[$variationString][] = $total_pages;





         }



         $amountToPay = $this->calculateIntervalTotals($priceVariation, $shopID);

         // code to add admin comission tax start

         $temp = $amountToPay*1.15;



         if($temp<1)

         {

             $temp = 1.15;

         }



         $temp = round($temp,2);

         $amountToPay = $temp;

         // code to add admin comission tax end



         if ($amountToPay < 1) {

             $amountToPay = 1.15;

         }

         // echo $amountToPay;exit;

        $transaction = Transaction::where('print_job_id', json_encode($pJIds))->first();

        if(!$transaction)
        {
             $transaction = new Transaction();
        }

        foreach($pJIds as $printJobId)
        {
            $printJob = PrintJob::find($printJobId);
            $printJob->transaction_id = $transaction->id;
            $printJob->save();
        }






         $transaction->print_job_id = json_encode($pJIds);

          $transaction->shop_id = $shopID;



         $transaction->invoice_id = 0;



         $transaction->amount = $amountToPay;



         $transaction->currency = "SAR";



         $transaction->type = $request->type;



         $transaction->status = "Queued";



         $transaction->date = date("Y-m-d H:i:s");



         $transaction->created_at = date("Y-m-d H:i:s");



         //  print_r($transaction);exit;



         $trans_update = $transaction->save();



         if ($trans_update) {

             Invoice::where("trans_id", $transaction->id)->delete();



             $invoice = new Invoice();



             $invoice->trans_id = $transaction->id; // Assuming a user association



             $invoice->invoice_number = date("is");



             $invoice->date = now();



             $invoice->amount = $amountToPay;



              $invoice->save();



             // Associate the transaction with the invoice



             $transaction->invoice_id = $invoice->id;



              $transaction->update();

         }



         if ($check_code && $request->type == "cash") {



             $type = "cash";





             $modalCode = '<div class="modal confirmModal successModal" id="cashOrder">

                    <div class="container" style="margin-top:50px;!important">

                        <div class="close" onclick="successModal()">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">

                            <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E" />

                        </svg>

                        </div>

                        <img src="http://hlaprint.synet.com.pk/public/assets/img/lineIcon.png" alt="" style="width: 9.2rem; height: 9.2rem;">

                        <p class="infoP">Please visit the Cashier to Pay</p>

                        <a style="width:100%!important ; background-color:#00448EB2;" class="btn " onclick="successModal()" type="">Ok</a>

                    </div>';

             session()->put('modal_code', $modalCode);



              return redirect()->back();

             //  return view('arabic.success');





         } elseif ($check_code) {

             $type = $request->type;






             $update_transaction = Transaction::where('print_job_id', json_encode($pJIds))
                                  ->latest()
                                  ->first();



             // print_r($update_transaction);exit;



             $payement = new PaymentController();



             $trans = $payement->initiatePayment(

                 $update_transaction->amount,



                 $update_transaction->id,



                 "english", $type

             );





             $redirect_url = $trans["redirect_url"];



             $trans_ref = $trans["tran_ref"];



             $update_transaction->trans_id = $trans_ref;



             $update_transaction->update();



             if ($redirect_url) {

                 return redirect($redirect_url);

             } else {

                 return redirect()

                     ->route("englishProcessing")



                     ->with("message", "Your print has been submitted");

             }

         } else {

             return "Code Not Found";

         }



     }

     public function Download($trans_id)
     {
            $transaction = Transaction::find($trans_id);
            $printJobs = json_decode($transaction->print_job_id);
            $printDetails = [];
            foreach($printJobs as $printJobId)
            {
                $printJob = PrintJob::find($printJobId);
                if($trans_id == 805)
                {
                    $printJob->page_orientation = "auto";
                }

                array_push($printDetails, $printJob);

            }
            $printDetails = json_encode($printDetails);


            return response()->json(['status' => 'success', 'message' => 'Files List Fetched', 'data' => $printDetails]);

     }







    public function cashApprove(Request $request, $code)

    {

        $check_code = PrintJob::where("code", $code)->first();



        $jobsArr=[];

        // get all print jobs of that code

        $printjobs = printJob::where('code',$code)->get();

        // made array of that ids

        foreach($printjobs as $jobs){

            array_push($jobsArr,$jobs->id);

        }

        $jobsArr= json_encode($jobsArr);



        // get then transaction id from set arrays of ids





        $printer = Shop_printers::where(

            "shop_id",



            $check_code->shop_id

        )->first();



        if ($printer) {

            $id = $printer->color_printer_id;



            $data = json_decode($printer->color_printer_id, true);

        } else {

            $printer = Shop_printers::where("shop_id", "0")->first();



            $id = $printer->color_printer_id;



            $data = json_decode($printer->color_printer_id, true);

        }



        $firstKey = array_key_first($data); // Get the first key



        $firstValue = $data[$firstKey]; // Get the value associated with the first key



        $printer_id = $firstValue;



        $print_request = new PrintRequestsController();



        $result = $print_request->printCashOrderIt(

            $check_code,



            $check_code->copies,



            $check_code->double_sided,



            $check_code->color,



            $check_code->range,



            $printer_id,



            $check_code->filename

        );



        $id = [];



        array_push($id, $check_code->id);



        // print_r(json_encode($id));exit;



        if ($result) {

            $trans_details = Transaction::where('print_job_id',$jobsArr)->first();



            // $trans_details = Transaction::where(

            //     "print_job_id",



            //     json_encode($id)

            // )->first();



            //   print_r($trans_details);exit;



            Transaction::where("id", $trans_details->id)->update([

                "status" => "A",

            ]);



            PrintJob::where("id", $check_code->id)->update([

                "status" => "Recieved",

            ]);

        }



        return back()->with("message", "Your print has been submitted");

    }


    public function checkIfFileConverted($code)

    {

        $printJobs = PrintJob::where("code", $code)->get();
        $totalJobs = $printJobs->count();
        $trueCount = 0;
        foreach($printJobs as $printJob)
        {
            $filename = $printJob->filename;
            $extension = substr($filename, -4);
            if(strtolower($extension) == ".pdf")
            {
                $trueCount++;
            }

        }

        if($trueCount == $totalJobs)
        {
            return response()->json(true);
        }
        else
        {
            return response()->json(false);
        }

    }




    public function printJobUpdate($printJob, $request, $type, $total_pages)

    {

        if ($type == "online") {

            $printJob->status = "Recieved";



            $printJob->type = "online";

        } else {

            $printJob->status = "Queued";



            $printJob->filename = $printJob->filename;



            $printJob->type = "cash";

        }



        if ($request->shop_id) {

            $color_amount = Color_size::select(

                "color_page_amount",



                "black_and_white_amount"

            )



                ->where("shop_id", "=", $request->shop_id)



                ->first();

        } else {

            $color_amount = Color_size::select(

                "color_page_amount",



                "black_and_white_amount"

            )



                ->where("shop_id", "=", "0")



                ->first();

        }



        $amountToPay = 0;



        // $total_amount = $request->price;



        $printJob->name = $request->name;



        $printJob->copies = $request->copies;



        $printJob->page_size = $request->page_size;



        $printJob->color = $request->color;



        $printJob->double_sided = $request->sides == "two" ? "long-edge" : null;



        $printJob->pages_start = $request->pages_start

            ? $request->pages_start

            : null;



        $printJob->page_end = $request->page_end ? $request->page_end : null;



        $printJob->copies = $request->copies;



        $printJob->total_pages = $total_pages;



        $res = $printJob->update();



        if ($res) {

            $price = $this->calculateTotals($printJob->id);



            // echo $price;



            $price = $price * $total_pages;



            $amountToPay += $price;



            if ($amountToPay < 1) {

                $amountToPay = 1;

            }



            $print_job_id = $printJob->id;



            // print_r($print_job_id);exit;



            $id = [];



            array_push($id, $print_job_id);



            $this->updateTransaction(json_encode($id), $amountToPay);

        }

    }



    public function updateTransaction($print_job_id, $total_amount)

    {

        $transactions = Transaction::where(

            "print_job_id",



            $print_job_id

        )->get();



        // print_r($transactions);exit;



        // echo $total_amount;exit;



        if ($transactions->isEmpty()) {

            return "Not transaction found";

        } else {

            foreach ($transactions as $trans) {

                $trans->amount = $total_amount;



                $trans_update = $trans->update();



                if ($trans_update) {

                    Invoice::where("trans_id", $trans->id)->delete();



                    $invoice = new Invoice();



                    $invoice->trans_id = $trans->id; // Assuming a user association



                    $invoice->invoice_number = "INV-" . date("YmdHis");



                    $invoice->date = now();



                    $invoice->amount = $total_amount;



                    $invoice->save();



                    // Associate the transaction with the invoice



                    $trans->invoice_id = $invoice->id;



                    $trans->update();

                } else {

                    return back()->with("No invoice generated");

                }

            }

        }

    }



    public function updateTrans_old($printJobId, $amount)

    {

        // dev new code to update status of transaction



        $transaction = DB::table("transactions")



            ->where("print_job_id", $printJobId)



            ->update(["status" => "1", "type" => "online"]);



        // dev new code to update status of transaction



        // $transaction = Transaction::where('print_job_id', $printJobId)->first();



        // if ($transaction) {



        //     $printJob = PrintJob::where('id', $printJobId)->first();



        //     if ($printJob) {



        //         $transaction->type = $printJob->type;



        //         $transaction->save();



        //     }



        // }



        // else {

        //     $transaction = new Transaction();



        //     $transaction->print_job_id = $printJobId;



        //     // $transaction->



        //     $transaction->save();



        // }

    }

    public function calculation_loop($doc_pages,$price_pages,$price)
    {
        $count = 0;
        while($doc_pages > 0)
        {
            $doc_pages -= $price_pages;
            $count++;
        }

        return $count*$price;
    }


    public function calculateIntervalTotals($priceVariation, $shop_id)
    {
        $totalPrice = 0;
       foreach($priceVariation as $variationId => $pages)
       {
              $variation = explode("_",$variationId);
              $pricing = PriceOptions::where('page_size',$variation[0])
                             ->where('color_type',$variation[1])
                             ->where('sidedness',$variation[2])
                             ->where('shop_id', $shop_id)
                             ->first();
              if(!$pricing)
              {
                return 0;
              }
              $pagesAll = collect($pages)->sum();
              $price = $this->calculation_loop($pagesAll, $pricing->no_of_pages,$pricing->base_price);

              $totalPrice += $price;

       }


        return $totalPrice;


    }
    public function calculateTotals($printJobId , $doc_pages)

    {

        $printJob = PrintJob::where("id", $printJobId)->first();



        if ($printJob && $printJob->shop_id) {

            $color_amount = Color_size::where(

                "shop_id",



                $printJob->shop_id

            )->first();

        } else {

            $color_amount = Color_size::where("shop_id", "0")->first();

        }


        $page_sizes_price = unserialize($color_amount->size_amount);



        // get db price values

        $color_noOfCopies = $color_amount->color_copies;

        $color_price = $color_amount->color_copies_price ;



        $bw_noOfCopies = $color_amount->bw_copies;

        $bw_price= $color_amount->bw_copies_price;



        // get important 3 values first

        $doc_pages = $doc_pages;

        $doc_copies = $printJob->copies;

        $doc_type  = $printJob->color == 'true'? "color" : "BW";





        $doc_TotalPages = $doc_pages * $doc_copies;





        // formula apply



        $count = 0;

        if($doc_type == 'color')

        {

            // 3>0

            while($doc_TotalPages > 0 )

            {

                $doc_TotalPages -= $color_noOfCopies;



                $count++;



            }

            $extra = 0;


            if($printJob->double_sided == 'true')
            {
                $extra = $color_amount->double_side;
            }
            else {
                $extra = $color_amount->one_side;
            }

            $extra += $page_sizes_price[$printJob->page_size];

            $color_price += $extra;


            return $count*$color_price;

        }



        if($doc_type == 'BW')

        {

            while($doc_TotalPages > 0 )

            {

                $doc_TotalPages -= $bw_noOfCopies;

                $count++;



            }


             $extra = 0;


            if($printJob->double_sided == 'true')
            {
                $extra = $color_amount->double_side;
            }
            else {
                $extra = $color_amount->one_side;
            }

            $extra += $page_sizes_price[$printJob->page_size];

            $bw_price += $extra;



            return $count*$bw_price;

        }



        // New logic end





    }




    public function calculateOldTotals($printJobId , $doc_pages)

    {

        $printJob = PrintJob::where("id", $printJobId)->first();



        if ($printJob && $printJob->shop_id) {

            $color_amount = Color_size::where(

                "shop_id",



                $printJob->shop_id

            )->first();

        } else {

            $color_amount = Color_size::where("shop_id", "0")->first();

        }



        // get db price values

        $color_noOfCopies = $color_amount->color_copies;

        $color_price = $color_amount->color_copies_price ;



        $bw_noOfCopies = $color_amount->bw_copies;

        $bw_price= $color_amount->bw_copies_price;



        // get important 3 values first

        $doc_pages = $doc_pages;

        $doc_copies = $printJob->copies;

        $doc_type  = $printJob->color == 'true'? "color" : "BW";





        $doc_TotalPages = $doc_pages * $doc_copies;





        // formula apply



        $count = 0;

        if($doc_type == 'color')

        {

            // 3>0

            while($doc_TotalPages > 0 )

            {

                $doc_TotalPages -= $color_noOfCopies;



                $count++;



            }





            return $count*$color_price;

        }



        if($doc_type == 'BW')

        {

            while($doc_TotalPages > 0 )

            {

                $doc_TotalPages -= $bw_noOfCopies;

                $count++;



            }


            echo $count*$bw_price;
            return $count*$bw_price;

        }



        // New logic end





    }



    //---------------------------------------------------------------------- Static Pages



    //---------------------------------------------------------------------- Index



    public function index()

    {

        return view("index");

    }



    public function test()

    {

        $model = PrintJobsModel::where("phone", "=", "923339039585")



            ->where("code", "=", 3081)



            ->first();



        dd($model->filename);

    }



    //---------------------------------------------------------------------- Share



    public function arabicShare()

    {

        return view("arabic.share");

    }



    public function englishShare()

    {

        return view("english.share");

    }



    //---------------------------------------------------------------------- Code



    public function arabicCode()

    {

        return view("arabic.code");

    }



    public function englishCode()

    {

        return view("english.code");

    }



    //---------------------------------------------------------------------- Document



    public function arabicDocument($file)

    {

        $data["file"] = $file;



        return view("arabic.document")->with($data);

    }



    public function englishDocument()

    {

        return view("english.document");

    }



    //---------------------------------------------------------------------- Processing



    public function arabicProcessing()

    {

        return view("arabic.processing");

    }



    public function englishProcessing()

    {

        return view("english.processing");

    }



    //---------------------------------------------------------------------- Pay



    public function arabicPay()

    {

        return view("arabic.pay");

    }



    public function englishPay()

    {

        $token = $this->generateToken();



        $this->convertImg($token);

    }



    //---------------------------------------------------------------------- Success



    public function arabicSuccess()

    {

        return view("arabic.success");

    }

    public function selfSetviceSuccess()
    {
        return view("self-service.success");
    }



    public function arabicError()

    {

        return view("arabic.error");

    }



    public function englishError()

    {

        return view("english.error");

    }



    public function englishSuccess()

    {

        return view("english.success");

    }



     public function getTotalPages(Request $request)

    {

        //   print_r($request->file);exit;



        // storage/app/public/WhatsApp_Files/



        $targetDir = storage_path(

            "/app/public/WhatsApp_Files/" . $request->phone . "/"

        ); // Directory where uploaded files will be stored



        // $file = asset('storage/app/public/WhatsApp_Files/'. $phone.'/'.$fileName);

        $now = \DateTime::createFromFormat("U.u", microtime(true));

        $filename = preg_replace(

            '/\.(?![^.]+$)/',

            "_",

            basename($_FILES["file"]["name"])

        );



        $filename = str_replace(" ", "_", $filename);



        $filename = $now->format("H_i_s_u")."_".$filename;



        $fileToSend = asset(

            "public/storage/WhatsApp_Files/" . $request->phone . "/" . $filename

        );



        $uploadFile = $targetDir . $filename;



        // Check if the file was uploaded without errors







        if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {

            //create printjob



            $printJobIds = [];



            $total_pages = 0;



            $print_job = new PrintJob();



            $print_job->shop_id = $request->shop;



            $print_job->phone = $request->phone;



            $print_job->code = $request->code;



            $print_job->status = "Queued";



            $print_job->filename = $filename;



            $print_job->total_pages = $total_pages;

            $res = $print_job->save();



            $convert_request = new ConvertController();







            $name = $convert_request->convert(

                $fileToSend,



                $request->phone,



                $request->code

            );



            $fileNameParts = explode(".", $name);



        $exactFileName = $fileNameParts[1];

        $exactFileName = explode("/", $exactFileName);

        $exactFileName = end($exactFileName);



        $pdfNEW = public_path("storage/WhatsApp_Files/".$request->phone."/".$exactFileName.".pdf");

        // echo()

          $pdf = new PDFInfo($pdfNEW);







            $total_pages = $pdf->pages;





            PrintJob::where("code", $request->code)



                ->where("filename", $filename)



                ->update(["filename" => $name, "total_pages" => $total_pages]);











            return response()->json([

                "total_pages" => $total_pages,

                "printJob_id" => $print_job->id,

            ]);



            // return $total_pages;



            // echo $total_pages;



            echo "File uploaded successfully!";

        } else {

            echo "Error uploading file.";

        }



        exit();



        $filePath = $request->file("file");

    }

    public function removeModalSession(Request $request)

    {

        $request->session()->forget("modal_code");



        return response()->json(["message" => "Session value removed"]);

    }



    public function financials(Request $request)
{
    $query = Shops::query();

    // Filter by shop if shop_id is provided
    if ($request->has('shop_id') && $request->shop_id != 'all') {
        $query->where('id', $request->shop_id);
    }

    $shops = $query->get();

    // Filter by date range if both dates are provided
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $transactionQuery = Transaction::query();

    if ($startDate && $endDate) {
        $transactionQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Get the print jobs based on the filtered shops
    $transactions = $transactionQuery->whereIn('shop_id', $shops->pluck('id'))->get();

    // Prepare the transactions for each shop
    $data["shops"] = $shops->map(function($shop) use ($transactions) {
        $transactionsByShop = $transactions->where('shop_id', $shop->id);
        $shop->cash_revenue = $transactionsByShop->where('type', 'cash')->sum('amount');
        $shop->online_revenue = $transactionsByShop->where('type', 'online')->sum('amount');
        return $shop;
    });

    return view("admin.financials", compact("data"));
}




    // download invoice pdf



    public function downloadPdf()

    {

        $invoiceData = [

            "id" => "1",



            "name" => "abc",

        ];



        $html = view("invoice")->toArabicHTML();



        $pdf = PDF::loadHTML($html)->output();



        // dummy values



        $phone = "923326869240";



        $code = "1234";



        if (

            !File::isDirectory(

                public_path("/storage/WhatsApp_Files/dummy/Invoices/" . $phone)

            )

        ) {

            File::makeDirectory(

                public_path("/storage/WhatsApp_Files/dummy/Invoices/" . $phone),



                0755,



                true,



                true

            );

        }



        $pdfPath = public_path(

            "/storage/WhatsApp_Files/dummy/Invoices/" .

                $phone .

                "/" .

                $code .

                ".pdf"

        );



        file_put_contents($pdfPath, $pdf);



        // $pdf->save('aimandummypdf.pdf');



        echo "PDF done";

    }



    public function downloadPdfOLD()

    {

        $invoiceData = [

            "id" => "1",



            "name" => "abc",

        ];



        $pdf = PDF::loadView("invoice", $invoiceData);



        // dummy values



        $phone = "923326869240";



        $code = "1234";



        if (

            !File::isDirectory(

                public_path("/storage/WhatsApp_Files/dummy/Invoices/" . $phone)

            )

        ) {

            File::makeDirectory(

                public_path("/storage/WhatsApp_Files/dummy/Invoices/" . $phone),



                0755,



                true,



                true

            );

        }



        $pdfPath = public_path(

            "/storage/WhatsApp_Files/dummy/Invoices/" .

                $phone .

                "/" .

                $code .

                ".pdf"

        );



        $pdf->save($pdfPath);



        return $pdf->download("dummyPDF.pdf");



        echo "PDF done";

    }







function calculateNewTotals()

{

    // get all these values from db  first



    $bw_noOfCopies = 2; // number of copies from ColorSize Table

    $bw_Price = 1; // Price for those Copies



    $color_noOfCopies = 1; // Color Number of Copies

    $color_Price = 1; // Price for color Copies

    // get all these values from db  first







    $doc_pages = 2; // Pages of the document Uploaded

    // total_pages = $doc_pages

    $doc_copies = 3; //already get



    $doc_type = "color"; //already get

//  let suppose   2 *1 = 3

    $doc_TotalPages = $doc_pages * $doc_copies; //Total Pages of the Document



    // count should 1

	$count = 0;

    if($doc_type == 'color')

    {

        // 3>0

    	while($doc_TotalPages > 0 )

        {

        	$doc_TotalPages -= $color_noOfCopies;

            // 3-=5

            $count++;



        }

        //



        return $count*$color_Price;

    }



    if($doc_type == 'bw')

    {

    	while($doc_TotalPages > 0 )

        {

        	$doc_TotalPages -= $color_noOfCopies;

            $count++;



        }



        return $count*$bw_Price;

    }

}

public function khttki_Sockets()

{

    return view("khttki_sockets");

}


}



