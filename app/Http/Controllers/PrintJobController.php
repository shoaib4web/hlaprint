<?php



namespace App\Http\Controllers;
use App\Events\PaymentReceived;
use Howtomakeaturn\PDFInfo\PDFInfo;

use App\Models\Color_size;

use App\Models\Invoice;

use App\Models\PrintJob;

use App\Models\UserCodes;

use App\Models\Shops;

use App\Models\Transaction;

use App\Models\Shop_printers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\ConvertController;


use Illuminate\Support\Carbon;



class PrintJobController extends Controller

{

    public function index(Request $request)

    {

        $qry = PrintJob::query();

        $qry = $qry->with('printshop');

        $qry = $qry->where('type', 'online');



        if ($request->isMethod('post')) {



            $qry->when($request->shop_id, function ($query, $shop_id) {

                return $query->where('shop_id', $shop_id);

            });



            $qry->when($request->phone, function ($query, $phone) {

                return $query->where('phone', $phone);

            });



            $qry->when($request->date, function ($query, $date) {

                return $query->whereDate('created_at', '=', $date);

            });

        }



        if (Auth::user()->role == 'superadmin') {

            $data['print_jobs'] = $qry->latest()->paginate(5);

        } elseif (Auth::user()->role == 'shopowner') {

            $shopsData = Shops::where('owner_id',Auth::user()->id)->get();

            foreach($shopsData as $d){

                $data['print_jobs'] = PrintJob::with('Shops')->where('shop_id',$d->id)->where('type','online')->latest()->paginate(10);

            }



        } else {

            $newData = PrintJob::with('Shops')->where('shop_id',7)->where('type','online')->latest()->paginate(10);



            $data['print_jobs'] =$newData;

        }



        $data['shops'] = Shops::select('id', 'name')->get();



        return view('admin.print_job.index', compact('data'));

    }



    public function cashOrder(Request $request)

    {

        $qry = PrintJob::query();

         $qry = $qry->with('printshop');

        $qry = $qry->where('type', 'cash');









        if ($request->isMethod('post')) {



            $qry->when($request->shop_id, function ($query, $shop_id) {

                return $query->where('shop_id', $shop_id);

            });



            $qry->when($request->phone, function ($query, $phone) {

                return $query->where('phone', $phone);

            });



            $qry->when($request->date, function ($query, $date) {

                return $query->whereDate('created_at', '>=', $date);

            });

        }



        if (Auth::user()->role == 'superadmin') {

            $data['print_jobs'] = $qry->get();

        } elseif (Auth::user()->role == 'shopowner') {

            $shopsData = Shops::where('owner_id',Auth::user()->id)->get();

            foreach($shopsData as $d){

                $data['print_jobs'] = PrintJob::with('Shops')->where('shop_id',$d->id)->get();

            }

        } else {

            $newData = PrintJob::with('Shops')->where('shop_id',7)->where('type','cash')->latest()->paginate(10);



            $data['print_jobs'] =$newData;

        }





        $data['shops'] = Shops::select('id', 'name')->get();

        return view('admin.print_job.cash_order', compact('data'));

    }

    public function updatePrintJob(Request $request)
    {

        $print_job = PrintJob::find($request->id);
        $print_job->copies = $request->copies;
        $print_job->color = $request->color;
        $print_job->double_sided = $request->page_sides;
        $print_job->status = 'Saved';
        $res = $print_job->update();

        return back()->with('success', 'PrintJob updated successfully');
    }


    public function api_store(Request $request)
    {

          // Ensure user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    $userShopId = $user->shop_id; // Retrieve the user ID from the token
        do {
            $code = rand(1000, 9999);
            $codeExists = UserCodes::where('code', $code)->exists();
        } while ($codeExists);

        // Validate the request
        $request->validate([
            'phone' => 'required|string|max:15',
            'totalPrice' => 'required|numeric|min:0',
            'print_files' => 'required|array|min:1',
            'print_files.*.filename' => [
                'required',
                'string',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                    try {
                        $response = Http::head($value);
                        $mimeType = $response->header('Content-Type');

                        if (!in_array($mimeType, $allowedMimeTypes)) {
                            $fail("The $attribute must be a PDF, JPG, or PNG file.");
                        }
                    } catch (\Exception $e) {
                        $fail("Unable to retrieve file type for $attribute.");
                    }
                },
            ],
            'print_files.*.color' => 'required|boolean',
            'print_files.*.double_sided' => 'required|boolean',
            'print_files.*.pages_start' => 'integer|min:1|required_with:print_files.*.page_end',
            'print_files.*.page_end' => 'integer|min:1|gte:print_files.*.pages_start|required_with:print_files.*.pages_start',
            'print_files.*.page_size' => 'required|string|in:A4,A3,Letter',
            'print_files.*.copies' => 'required|integer|min:1|max:100',
            'print_files.*.page_orientation' => 'required|string|in:auto,portrait,landscape',
        ]);

        // Create a transaction record
        $transaction = Transaction::create([
            'shop_id' => $userShopId,
            'amount' => $request->totalPrice,
            'currency' => "SAR",
            'type' => 'NANA',
        ]);

        $printJobIds = [];

        foreach ($request->print_files as $printFile) {
            try {
                $fileContents = Http::get($printFile['filename']);
                if ($fileContents->failed()) {
                    return response()->json(['error' => 'Failed to access file from ' . $printFile['filename']], 422);
                    exit();
                }

                // Determine file extension
                $urlParts = parse_url($printFile['filename']);
                $extension = pathinfo($urlParts['path'], PATHINFO_EXTENSION);

                // Save original file
                $filePath = 'WhatsApp_Files/Access/' . Str::random(40) . '.' . $extension;
                Storage::disk("public")->put($filePath, $fileContents->body() );


                Log::info("File Saved at ".$filePath);

                // If the file is JPG or PNG, convert it to PDF

                    Log::info("Starting Conversion in PrintJobController");

                    $convertController = new ConvertController();
                    $pdfName = $convertController->convert(asset($filePath), "Access" ,$code);
                    Log::info ("Full Path is ".$pdfName);
                    $fileNameParts = explode(".", $pdfName);
                    $fileNameParts = explode("/", $fileNameParts[1]);
                    $exactFileName = end($fileNameParts);
                    Log::info ("Exact Filename is ".$exactFileName);

                    $pdfPath = public_path("storage/WhatsApp_Files/" . "Access" . "/" . $exactFileName . ".pdf");
                    Log::info ("PDF Path  is ".$pdfPath);
                    $pdf = new PDFInfo($pdfPath);
                    $pageNumber = $pdf->pages;
                    Log::info("Converted File: " . $pdfName);
                    $filePath = $pdfPath;  // Update file path to the new PDF file path

                    Log::info("Print FIle info: " . print_r($printFile,true));


                // Create the print job and associate it with the transaction
                $printJob = PrintJob::create([
                    'transaction_id' => $transaction->id,
                    'filename' => asset($pdfName),  // Store the public URL for the converted file
                    'color' => $printFile['color'],
                    'double_sided' => $printFile['double_sided'],
                    'pages_start' => $printFile['pages_start']??1,
                    'page_end' => $printFile['page_end']??$pageNumber,
                    'page_size' => $printFile['page_size'],
                    'copies' => $printFile['copies'],
                    'type' => 'api',
                    'page_orientation' => $printFile['page_orientation'],
                    'status' => 'Received',
                    'phone' => $request->phone,
                    'total_pages' => $pageNumber,
                    'code' => $code,
                    'shop_id' => $userShopId,
                ]);

                // Add the print job ID to the array
                $printJobIds[] = $printJob->id;
            } catch (\Exception $e) {
                Log::error("Error in printJobController: " . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'stack_trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => 'Unable to process file from ' . $printFile['filename']], 422);
            }
        }

        // Update the transaction with the list of print job IDs
        $transaction->update([
            'print_job_id' => json_encode($printJobIds),
        ]);

        // Generate an invoice for the transaction
        $invoice_number = 'INV-' . strtoupper(Str::random(8));
       $invoice = Invoice::create([
            'trans_id' => $transaction->id,
            'invoice_number' => $invoice_number,
            'amount' => $request->totalPrice,
            'monthly_id' => $this->getMonthlyInvoiceID(13),
            'shop_id' => 13,
        ]);

        $transaction->update([
            'invoice_id' => $invoice->id,
        ]);

        $uc = new UserCodes();

        $uc->phone = $request->phone;

        $uc->code = $code;

        $uc->status = false;

        $uc->expiry = date("Y-m-d", strtotime(" +1 day"));

        $uc->save();


        return response()->json([
            'message' => 'Print jobs created succesfully',
            'transaction_id' => $transaction->id,
            'code' => $code,
        ]);
    }

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

    /**
     * Display the list of print jobs for a specific transaction ID.
     */
    public function api_show($transactionId)
    {
        // Retrieve print jobs by transaction ID
        $printJobs = PrintJob::where('transaction_id', $transactionId)->get();

        if ($printJobs->isEmpty()) {
            return response()->json(['message' => 'No print jobs found'], 404);
        }

        return response()->json($printJobs);
    }


    public function printJobs(Request $request)

    {

        $data['print_jobs'] = PrintJob::whereHas('printshop', function ($query) use ($request) {

            $query->where('shop_id', $request->shop_id);

        })->get();

        return view('admin.shop.print_job', compact('data'));

    }



    public function updateStatus(Request $request)

    {

        // echo 'here';exit;

        $check_code = PrintJob::where('code', $request->id)->first();

        $convert_request = new ConvertController();

        $convert_request->convert( $check_code->filename, $check_code->phone, $check_code->code);



        $change_status = PrintJob::where('code', $request->id)->first();

        if ($change_status->status == 'Queued') {

            $change_status->status = 'Recieved';

            $change_status->update();

        }

        return response()->json([

            'status' => 200,

            'message' => 'Status update successfully',

        ]);

    }



    public function createPrintJob()

    {

        if (Auth::user()->role == 'superadmin') {

            $data['shops'] = Shops::select('id', 'name')->get();



        }elseif(Auth::user()->role == 'shopowner'){

            $data['shops'] = Shops::select('id', 'name')->where('owner_id',Auth::user()->id)->get();



        }else{

            $data['shops'] = Shops::select('id', 'name')->where('owner_id',Auth::user()->id)->get();





        }



        return view('admin.print_job.create_print_job', compact('data'));

    }



    public function storePrintJob(Request $request)

    {

        $print_job = new PrintJob();

        $code = rand(1000,9999);

        $color_amount = Color_size::select('color_page_amount', 'black_and_white_amount')->first();

        if ($request->color == false && $request->page_sides == 0) {

            $amount = $color_amount->black_and_white_amount;

        } elseif ($request->color == true && $request->page_sides == 1) {

            $amount = $color_amount->color_page_amount + $color_amount->color_page_amount;

        } elseif ($request->color == false && $request->page_sides == 1) {

            $amount = $color_amount->black_and_white_amount + $color_amount->black_and_white_amount;

        } else {

            $amount = $color_amount->color_page_amount;

        }



        $total_amount = $amount * $request->copies;

        // echo $total_amount;exit;

        $print_job->code = $code;

        $print_job->shop_id = $request->shop_id;

        $print_job->copies = $request->copies;

        $print_job->color = $request->color;

        $print_job->double_sided = $request->page_sides;

        $print_job->type  = 'cash' ;

        $print_job->status = 'Recieved';

        $res = $print_job->save();

        // echo $res;exit;

        if ($res) {

             // dev code to save transaction pending

            $transactions = Transaction::where('print_job_id', $print_job->id)->get();

            if ($transactions->isEmpty()) {

                $transaction = new Transaction;

                $transaction->print_job_id = $print_job->id;

                $transaction->invoice_id = 0;

                $transaction->amount = $total_amount;

                $transaction->currency = 'SAR';

                $transaction->type = 'cash';

                $transaction->status = 0;

                $transaction->date=date('Y-m-d H:i:s');

                $transaction->created_at=date('Y-m-d H:i:s');

                $transaction->save();

            }

            // dev code to save transaction pending

            $print_job_id = $print_job->id;

            $this->updateTransaction($print_job_id, $total_amount);

        }

        return back()->with('success','PrintJob created successfully');

    }



    public function updateTransaction($print_job_id, $total_amount)

    {

        // echo $print_job_id;exit;

        $transactions = Transaction::where('print_job_id', $print_job_id)->get();

        //  print_r($transactions);exit;

        if ($transactions->isEmpty()) {

            return 'Not transaction found';

        } else {

            //  echo 'here';exit;

            foreach ($transactions as $trans) {

                $trans->amount = $total_amount;

                $trans_update = $trans->update();

                if($trans_update) {

                    Invoice::where('trans_id', $trans->id)->delete();

                    $invoice = new Invoice();

                    $invoice->trans_id = $trans->id; // Assuming a user association

                    $invoice->invoice_number = 'INV-' . date('YmdHis');

                    $invoice->date = now();

                    $invoice->amount =$total_amount;

                    $invoice->save();

                    // Associate the transaction with the invoice

                    $trans->invoice_id = $invoice->id;

                    $trans->save();

                } else {

                    return back()->with('error','No invoice generated');

                }

            }

        }

    }



    // new function to show print jobs through transaction table

    public function TransPrintJob(){

         if (Auth::user()->role == 'superadmin') {

            $data['trans'] = $qry->latest()->paginate(10);

        } elseif (Auth::user()->role == 'shopowner') {

            $shopsData = Shops::where('owner_id',Auth::user()->id)->get();

            foreach($shopsData as $d){

                $data['trans'] = Transaction::where('shop_id',$d->id)->where('type','online')->latest()->paginate(10);

            }



        }
        else {
            $data['trans'] = Transaction::where('type','online')->where('shop_id', Auth::user()->shop_id)->latest()->paginate(10);
        }



        return view('admin.print_job.code_print_job', compact('data'));



    }

    public function TransCashJob(){

        if (Auth::user()->role == 'superadmin') {

            $data['trans'] = $qry->latest()->paginate(10);

        } elseif (Auth::user()->role == 'shopowner') {

            $shopsData = Shops::where('owner_id',Auth::user()->id)->get();

            foreach($shopsData as $d){

                $data['trans'] = Transaction::where('shop_id',$d->id)->where('type','cash')->latest()->paginate(10);

            }



        }
        else {
            $data['trans'] = Transaction::where('type','cash')->where('shop_id', Auth::user()->shop_id)->latest()->paginate(10);
        }



        return view('admin.print_job.code_cash_order', compact('data'));



    }

    public function cashApprove(Request $request, $code)

    {

        // print invoice with print also

        // $printJobsIds = json_decode($transaction->print_job_id);

         DB::table("user_codes")



                ->where("code", $code)



                ->update(["status" => "1"]);

        $printJobsIds = PrintJob::where("code",$code)->get();



        // get all data of fetched ids from transaction

        $fetchJobs=[];

        $pIds=[];

       $codeFinal="";

        foreach($printJobsIds as $id){

           $jobs =  PrintJob::where("id",$id->id)->first();

           $shop =  $jobs->shop_id;

           $code = $jobs->code;
           $codeFinal = $code;

           $phone = $jobs->phone;

           $jobs->status = "Sent to Print";

           $jobs->update();

           array_push($fetchJobs,$jobs);

           array_push($pIds,$jobs->id);



        }

        $pIds = json_encode($pIds);

        $transaction = Transaction::where('print_job_id',$pIds)->first();

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

        // Send Invoice to Whatsapp
        $wa = new WAController();


        Log::info("Printed Cash");

        if($shop == 13)
        {
             $wa->sendInvoice($codeFinal, $phone, $shop??0);
        }
        else {
            $wa->sendInvoice($invoice->monthly_id, $phone, $shop??0);
            event(new PaymentReceived($transaction->id));
        }



        Transaction::where('print_job_id',$pIds)->update([

            "status" => "Approved",

        ]);







        return back()->with("success", "Print is underway");
        die();

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

        $fetchPDF =  $invoiceRequest->createNewInvoice($transaction,$invoiceDetails,$fetchJobs,$invoice);

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

            $fetchPDF = $invoiceRequest->createNewInvoice($transaction,$invoiceDetails,$finalFetchJobs,$invoice);





                $printrequest->generateInvoice($fetchJobs,$fetchPDF,$selectedPrinterColor );

                $wa = new WAController();

            $wa->sendInvoice($invoice->monthly_id, $userPhone);

        }

        else {

            $invoiceType = 'color';

            $invoiceRequest = new PaymentController();

            $colorfetchPDF = $invoiceRequest->createNewInvoice($transaction,$invoiceDetails,$colorFetchJobs,$invoice);

            $invoiceType = 'BW';



            $BWfetchPDF = $invoiceRequest->createNewInvoice($transaction,$invoiceDetails,$BWFetchJobs,$invoice);

                $wa = new WAController();

            $wa->sendInvoice($invoice->monthly_id, $userPhone);





            if($printedBlack)

            {

                $printrequest->generateInvoice($BWFetchJobs, $BWfetchPDF,$selectedPrinterBW );

            }

            if($printedColored)

            {

                $printrequest->generateInvoice($colorFetchJobs, $colorfetchPDF,$selectedPrinterColor );

            }







        }



         Transaction::where('print_job_id',$pIds)->update([

            "status" => "Approved",

        ]);







        return back()->with("success", "Print is underway");

    }





}

