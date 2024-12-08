<?php







namespace App\Http\Controllers;







use App\Models\Invoice;



use App\Models\Transaction;



use App\Models\PrintJob;

use App\Models\Invoice_detail;



use App\Models\Shops;



use Illuminate\Http\Request;



// use Barryvdh\DomPDF\PDF;



use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\DB;


use PDF;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use App\Events\PaymentReceived;


class TransactionController extends Controller



{



    public function index(Request $request)



    {



        if(Auth::user()->role == 'superadmin'){



                $data['transactions'] = Transaction::whereHas('printjob', function ($query) use ($request) {



                    


                })->latest()->paginate(10);



        }else{



            $shopsData = Shops::where('owner_id',Auth::user()->id)->first();
       
            if($shopsData)
            {
                $shopId = $shopsData->id;



                    $data['transactions']= Transaction::where('shop_id', $shopId)->latest()->paginate(10);
            }
            else {
                $shopId = 7;
                 $data['transactions']= Transaction::where('shop_id', $shopId)->latest()->paginate(10);
            }



            



        }







        return view('admin.transaction.index', compact('data'));



    }
    
    
    public function checkStatus($orderId) {
  
  $order = Transaction::find($orderId);

  $printJobs = json_decode($order->print_job_id);

  $printjob = PrintJob::find($printJobs[0]);

  
  if ($printjob) {
    return response()->json(['status' => $printjob->status]);
  } else {
    return response()->json(['status' => 'not_found'], 404);
  }
}



   public function sales(Request $request)



    {



        if(Auth::user()->role == 'superadmin'){



                $data['transactions'] = Transaction::whereHas('printjob', function ($query) use ($request) {



                    


                })->latest()->paginate(10);



        }else{



            $shopsData = Shops::where('owner_id',Auth::user()->id)->first();
       
            if($shopsData)
            {
                $shopId = $shopsData->id;



                    $data['transactions']= Transaction::where('shop_id', $shopId)->latest()->paginate(10);
                    $data['totalSales'] = Transaction::where('shop_id', $shopId)->whereIn('status', ['A','Approved'])->sum('amount');
                 $data['totalSalesOnline'] = Transaction::where('shop_id', $shopId)->where('type','online')->where('status','A')->sum('amount');
                 $data['totalSalesCash'] = Transaction::where('shop_id', $shopId)->where('type','cash')->where('status','Approved')->sum('amount');
            }
            else {
                $shopId = 7;
                 $data['transactions']= Transaction::where('shop_id', $shopId)->latest()->paginate(10);
                 $data['totalSales'] = Transaction::where('shop_id', $shopId)->whereIn('status', ['A','Approved'])->sum('amount');
                 $data['totalSalesOnline'] = Transaction::where('shop_id', $shopId)->where('type','online')->where('status','A')->sum('amount');
                 $data['totalSalesCash'] = Transaction::where('shop_id', $shopId)->where('type','cash')->where('status','Approved')->sum('amount');
            }



            



        }







        return view('admin.sales.index', compact('data'));



    }
    



    public function filterTransaction(Request $request){
        
        
        if(Auth::user()->role == 'superadmin'){



                $data['transactions'] = Transaction::whereHas('printjob', function ($query) use ($request) {



                    


                })->latest()->paginate(10);



        }else{



            $shopsData = Shops::where('owner_id',Auth::user()->id)->first();
       
            if($shopsData)
            {
                $shopId = $shopsData->id;

                if($request->type)
                {
                   if($request->type == 'both')
                    {
                        $data['transactions']= Transaction::where('shop_id', $shopId)->latest()->paginate(10);
                    }
                    else {
                     $data['transactions']= Transaction::where('shop_id', $shopId)->where('type',$request->type)->latest()->paginate(10);   
                    }
                }
                 if($request->date)
                {
                     
                    $data['transactions'] = DB::table('transactions')
        ->where([
            ['shop_id', '=', $shopId],
            ['created_at', 'LIKE', $request->date . '%'], // Match date and time up to seconds
        ])
        ->latest()
        ->paginate(10);
                }

                    
            }
            else {
                $shopId = 7;
                 if($request->type)
                {
                    if($request->type == 'both')
                    {
                        $data['transactions']= Transaction::where('shop_id', $shopId)->latest()->paginate(10);
                    }
                    else {
                     $data['transactions']= Transaction::where('shop_id', $shopId)->where('type',$request->type)->latest()->paginate(10);   
                    }
                    
                }
                if($request->date)
                
                {
                     $data['transactions'] = DB::table('transactions')
        ->where([
            ['shop_id', '=', $shopId],
            ['created_at', 'LIKE', $request->date . '%'], // Match date and time up to seconds
        ])
        ->latest()
        ->paginate(10);
                }
            }}



   



      



        return view('admin.transaction.index', compact('data'));


    }
    
    
    public function filterSales(Request $request){
        
        if(!isset($request->type) && !isset($request->startDate))
        {
            return redirect()->back();
        }
        
        
        if(Auth::user()->role == 'superadmin'){



                $data['transactions'] = Transaction::whereHas('printjob', function ($query) use ($request) {



                    


                })->latest()->paginate(10);



        }else{



            $shopsData = Shops::where('owner_id',Auth::user()->id)->first();
       
            if($shopsData)
            {
                $shopId = $shopsData->id;

                if($request->type)
                {
                   if($request->type == 'week')
                    {
                        $endDate = Carbon::now();  // Current date and time
                        $startDate = Carbon::now()->subWeek();  // Date and time one week ago
                        
                        $data['transactions'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->latest()
                                ->paginate(10);
                        $data['totalSales'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->whereIn('status',['A','Approved'])->sum('amount');
                                
                        $data['totalSalesOnline'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->where('type','online')->where('status','A')->sum('amount');
                                
                        $data['totalSalesCash'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->where('type','cash')->where('status','Approved')->sum('amount');
                    }
                    elseif($request->type == 'month') {
                        
                        $endDate = Carbon::now();  // Current date and time
                        $startDate = Carbon::now()->subMonth();
                        
                     $data['transactions'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->latest()
                                ->paginate(10);
                      $data['totalSales'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->whereIn('status',['A','Approved'])->sum('amount');
                                
                        $data['totalSalesOnline'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->where('type','online')->where('status','A')->sum('amount');
                                
                        $data['totalSalesCash'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->where('type','cash')->where('status','Approved')->sum('amount');  
                    }
                }
                 if($request->startDate && $request->endDate )
                {
                    
                    $startDate = Carbon::parse($request->startDate)->startOfDay();  // Start of the day
$endDate = Carbon::parse($request->endDate)->endOfDay();  // End of the day
                     
                    $data['transactions'] = DB::table('transactions')
        ->where('shop_id', '=', $shopId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->latest()
        ->paginate(10);
       $data['totalSales'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->whereIn('status',['A','Approved'])->sum('amount');
                                
                        $data['totalSalesOnline'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->where('type','online')->where('status','A')->sum('amount');
                                
                        $data['totalSalesCash'] = DB::table('transactions')
                                ->where('shop_id', '=', $shopId)
                                ->whereBetween('created_at', [$startDate, $endDate])->where('type','cash')->where('status','Approved')->sum('amount');

                }
                
                else {
                    
                }

                    
            }
           }



   



      



        return view('admin.sales.index', compact('data'));


    }

    public function editInvoiceDetail($shop_id)
{
    $shop_id =  Auth::user()->shop_id;
    $shop = Shops::where('id', $shop_id)->first();
    $invoice_data = DB::table('invoice_details')->where('shop_id', $shop_id)->first();

    $data['shops'] = Shops::all();
    $data['invoice_data'] = $invoice_data;
    $data['shop'] = $shop;

    return view('admin.invoice.edit_invoice_detail', compact('data'));
}
    
    public function invoiceDetail($shop_id)

    {
        $shop_id = null;
        if($shop_id){
             $shop = Shops::where('id',$shop_id)->get();
             $data['shops'] =$shop;
            $data['invoice_data'] = DB::table('invoice_details')->where('shop_id',$shop_id)->get();

        }else{
            $data['shops'] = Shops::where('id',Auth::user()->shop_id)->get();
            $data['invoice_data'] = DB::table('invoice_details')->where('shop_id',Auth::user()->shop_id)->get();

        }
       
       
        return view('admin.invoice.create_invoice_details', compact('data'));

    }
    public function editInvoice(Request $request)
    {
        if($request->id){
            $invoice_detail = DB::table('invoice_details')->where('shop_id', '=', $request->id)->first();
            $data['shops'] = Shops::where('id',$invoice_detail->shop_id)->get();
        }else{
            $invoice_detail = DB::table('invoice_details')->where('shop_id', '=', '0')->first();
            $data['shops'] = [];
      
        }
        $data['invoice_data'] = $invoice_detail;
        // dd( $invoice_detail);
        return view('admin.invoice.edit_invoice', compact('invoice_detail','data'));
    }

    public function storeInvoiceDetail(Request $request)
    {
       
        $invoice_detail = Invoice_detail::where('shop_id', '=', $request->shop_id)->first();
        if(!$invoice_detail)
        {
            $invoice_detail = new Invoice_detail();
        }
        $invoice_detail->shop_id =  $request->shop_id;
        $invoice_detail->name = $request->display_name;
        $invoice_detail->tax_number = $request->tax_number;
        $invoice_detail->license_number = $request->license_number;
        $invoice_detail->vat_number = $request->vat_number;
        $invoice_detail->address = $request->address;
        // print_r($invoice_detail);exit;
        
        $res = $invoice_detail->save();
        if ($res) {
            return back()->with('success', 'Invoice detail added successfully');
        } else {
            return back()->with('error', 'Failed, Invoice detail not addedd!');
        }
    }

    
   
    public function generateInvoice(Request $request)
    {
        $data['generate_invoice'] = Invoice::with(['transaction.printjob.shops'])->where('trans_id', $request->id)->first();
        return view('admin.invoice.invoice', compact('data'));

    }

    public function rePrint($id){
        // this id is of printjob

        $printjob =  PrintJob::where('id', $id)->first();
        
        $transaction = Transaction::where('print_job_id',json_encode($printjob->id))->first();

        $invoice =  DB::table('invoices')->where('trans_id',$transaction->id)->first();
        

        $shop = Shops::where('id', $printjob->shop_id)->first();

        if($shop){

           $name = $shop; 

        }else{

            $name = 'Admin';

        }

        $printers = DB::table('shop_printers')->where('shop_id', 0)->first();

        $color_printers = json_decode($printers->color_printer_id);

        $bw_printers = json_decode($printers->bw_printer_id);

        

        $invoiceData = [

            'id' => $invoice->id,

            'invoice'=>$invoice->invoice_number,

            'trans_id'=>$transaction->trans_id,

            'date'=>$invoice->created_at,

            'amount'=>$transaction->amount,

            'color'=>$printjob->color,

            'shop'=>$name,

            'type'=>$transaction->type,

            'copy'=>$printjob->copies,

            // Add other data here

        ];

        $pdf = PDF::loadView('invoice', $invoiceData);

        if(!File::isDirectory(public_path('/storage/WhatsApp_Files/Invoices/' . $printjob->phone)))

        {

            File::makeDirectory(public_path('/storage/WhatsApp_Files/Invoices/' . $printjob->phone), 0755, true, true);

        }

        $pdfPath =  public_path('/storage/WhatsApp_Files/'.$printjob->phone.'/'.$printjob->code.'.pdf');

        $pdf->save($pdfPath);

        $pdfPath = asset('public/storage/WhatsApp_Files/'.$printjob->phone.'/'.$printjob->code.'.pdf');

        $debug = print_r($printjob->color,true);

         Storage::disk('local')->put('khttki_invoice_debug.txt', $debug);

        if($printjob && $printjob->color == 'true' )

        {



            $printrequest = new  PrintRequestsController();

            $printrequest->printRun($printjob, $color_printers[0],$pdfPath);

           

        }

        elseif($printjob && $printjob->color == 'false')

        {

            $printrequest = new  PrintRequestsController();

            $printrequest->printRun($printjob, $bw_printers[0],$pdfPath);

           

        }else{

            echo 'nothing';

        }


        return back()->with('success','Printing done');

            



    }

    


    public function newRePrint($code){
        // this id is of printjob

        $printjob =  PrintJob::where('code', $code)->first();
         $printJobsIds = PrintJob::where("code",$code)->get();
        
         $fetchJobs=[];

        $pIds=[];

       

        foreach($printJobsIds as $id){

           $jobs =  PrintJob::where("id",$id->id)->first();

           $shop =  $jobs->shop_id;

           $code = $jobs->code;

           $phone = $jobs->phone;

           array_push($fetchJobs,$jobs);

           array_push($pIds,$jobs->id);

          

        }

        $pIds = json_encode($pIds);

        $transaction = Transaction::where('print_job_id',$pIds)->first();

        $trans_id = $transaction->id;

        event(new PaymentReceived($trans_id));
        return back()->with("success", "Print is underway");
        die();
         $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();

        

        // get shop details

        if($shop){

            $shopData=Shops::where("id", $shop)->first();

            $name = $shopData->name;

            $shopPhone= $shopData->contact;

        }else{

            $name="ADMIN";

            $shopData=[];

            $shopPhone= "+966 59 101 3248";

        }

        // to print all input fields on invoice get all print jobs 

       

        // get invoice detail of shop keepers

        if($shopData){

            $invoiceDetails = DB::table("invoice_details")->where("shop_id", $shopData->id)->first();

            $priceDetails = DB::table("color_sizes")->where("shop_id", $shopData->id)->first();

        }else{

            $invoiceDetails = DB::table("invoice_details")->where("shop_id", "0")->first();

            $priceDetails = DB::table("color_sizes")->where("shop_id", '0')->first();

       

        }

        

        // needtobedynamic    ??

        if($shop)

        {

            $printers = DB::table("shop_printers")->where("shop_id",$shop)->first();

        }

        else {

            $printers = DB::table("shop_printers")->where("shop_id", 0)->first();

        }

        





        

       

        $color_printers = json_decode($printers->color_printer_id);



        $bw_printers = json_decode($printers->bw_printer_id);

        $invoiceType='color';

        $invoiceRequest = new PaymentController();

        $fetchPDF =  $invoiceRequest->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$fetchJobs,$invoiceType, $priceDetails );

        $colorFetchJobs=[];

        $BWFetchJobs=[];

        $printedBlack = false;

        $printedColored = false;



        // single array 

        $userPhone = 0;

        $finalFetchJobs=[];

        $selectedPrinterColor =0;

        $selectedPrinterBW = 0;

        $phone = 0;

        $printrequest = new PrintRequestsController();

        foreach($bw_printers as $bw_printer)

        {

            

            if( $printrequest->isPrinterOnline($bw_printer)) //printer is online

            {

                $selectedPrinterBW = $bw_printer;

                break;

            }



        }



        foreach($color_printers as $color_printer)

        {

              

            if( $printrequest->isPrinterOnline($color_printer)) //printer is online

            { 

            $selectedPrinterColor = $color_printer;

            break;

            }

        }

       

        foreach($fetchJobs as $printjob){

            $userPhone = $printjob->phone;

            if ($printjob && $printjob->color == "true") {

                array_push($finalFetchJobs,$printjob);

                array_push($colorFetchJobs,$printjob);

                $phone = $printjob->phone;

                

               

                

                if ($color_printers) {

                    

                    

                    if($selectedPrinterColor){

                        $result = $printrequest->printRun(

                            $printjob,



                            $selectedPrinterColor,



                        );

                        $printedColored = true;



                    }

                    

                    else {

                       return back()->with('error', "There was an error");

                    }

                 

                }

            } elseif ($printjob && $printjob->color == "false") {

               

                array_push($finalFetchJobs,$printjob);

                array_push($BWFetchJobs,$printjob);

                $phone = $printjob->phone;

                

                if($selectedPrinterBW)

                {

                    $result = $printrequest->printRun(

                        $printjob,

                        $selectedPrinterBW ,

                    );

                    $printedBlack = true;

                

                    if ($result) {

                        DB::table("whatsapp_requests")



                            ->where("WA_number", $printjob->phone)



                            ->delete();

                    }

                }

                    

                else {

                 return back()->with('error', "There was an error");

            }

            

            } else {

                return back()->with('error', "There was an error");
            }

            // $printrequest = new PrintRequestsController();





        }

        

        $printrequest = new PrintRequestsController();

        if($selectedPrinterColor == $selectedPrinterBW)

        {

            $invoiceType = 'combined';

            $invoiceRequest = new PaymentController();

            $fetchPDF = $invoiceRequest->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$finalFetchJobs,$invoiceType, $priceDetails );

       

            

                $printrequest->generateInvoice($fetchJobs,$fetchPDF,$selectedPrinterColor );

                $wa = new WAController();

            $wa->sendInvoice($invoice->monthly_id, $userPhone);

        }

        else {

            $invoiceType = 'color';

            $invoiceRequest = new PaymentController();

            $colorfetchPDF = $invoiceRequest->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$colorFetchJobs,$invoiceType, $priceDetails );

            $invoiceType = 'BW';

           

            $BWfetchPDF = $invoiceRequest->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$BWFetchJobs,$invoiceType, $priceDetails );

               
             

           

            if($printedBlack)

            {

                $printrequest->generateInvoice($BWFetchJobs, $BWfetchPDF,$selectedPrinterBW );

            }

            if($printedColored)

            {

                $printrequest->generateInvoice($colorFetchJobs, $colorfetchPDF,$selectedPrinterColor );

            }

            

            return back()->with("success", "Print is underway");

            

        }

        // $jobsArr=[];
        // // get all print jobs of that code
        // $printjobs = printJob::where('code',$code)->get();
        // // made array of that ids 
        // foreach($printjobs as $jobs){
        //     array_push($jobsArr,$jobs->id);
        // }
        // $jobsArr= json_encode($jobsArr);
      
        // // get then transaction id from set arrays of ids 
        // $transaction = Transaction::where('print_job_id',$jobsArr)->first();
      

        // $invoice =  DB::table('invoices')->where('trans_id',$transaction->id)->first();
        
        // $shop = Shops::where('id', $printjob->shop_id)->first();

        // if($shop){

        //   $name = $shop; 

        // }else{

        //     $name = 'Admin';

        // }

        // $printers = DB::table('shop_printers')->where('shop_id', $printjob->shop_id)->first();

        // $color_printers = json_decode($printers->color_printer_id);

        // $bw_printers = json_decode($printers->bw_printer_id);

        
        // foreach($printjobs as $jobs){
        //     if($jobs && $jobs->color == 'true' )

        //     {
        //         $printrequest = new  PrintRequestsController();

        //         $printrequest->printRun($jobs, $color_printers[0]);

        //     }

        //     elseif($jobs && $jobs->color == 'false')

        //     {

        //         $printrequest = new  PrintRequestsController();

        //         $printrequest->printRun($jobs, $bw_printers[0]);

            

        //     }else{

        //         echo 'nothing';

        //     }
        // }

        // return back()->with('success','Printing done');

            



    }


    public function generateNewPrint(Request $request)
    {
        $jobsArr=[];
        // will get code from now 
      
        // get all print jobs of that code
        $printjobs = printJob::where('code',$request->code)->get();
        // made array of that ids 
        foreach($printjobs as $jobs){
            array_push($jobsArr,$jobs->id);
        }
        $jobsArr= json_encode($jobsArr);
      
        // get then transaction id from set arrays of ids 
        $transaction = Transaction::where('print_job_id',$jobsArr)->first();
      
        // echo $transaction->id;exit;
        
    }


}



