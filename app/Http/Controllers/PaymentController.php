<?php



namespace App\Http\Controllers;



use SimpleSoftwareIO\QrCode\Facades\QrCode;



use Illuminate\Http\Request;



use GuzzleHttp\Client;



use Illuminate\Support\Facades\Http;



use Illuminate\Support\Facades\Storage;



use Illuminate\Support\Facades\DB;



use Illuminate\Support\Facades\File;



use App\Models\Transaction;



use App\Models\PrintJob;



use App\Models\Shop;



use PDF;



use Carbon\Carbon;

use App\Events\PaymentReceived;

// use ClickPay\ClickPayCore\ClickpayHelper;
// use ClickPay\ClickPayCore\ClickpayApplePayHolder;
// use ClickPay\ClickPayCore\ClickpayEnum;
// use ClickPay\ClickPayCore\ClickpayApi;



class PaymentController extends Controller

{

    protected $client;



    public function __construct()

    {

        $this->client = new Client();

    }


    public function refund($transaction_id)
    {

        $profile_id = "43626";



        $apiEndpoint = "https://secure.clickpay.com.sa/payment/request";



        $apiKey = config("services.clickpay.api_key");



        $secretKey = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J";

        $transaction = Transaction::where('id',$transaction_id)->first();
        $data = [

            "profile_id" => $profile_id,



            "cart_amount" => $transaction->amount,

            "tran_ref" => $transaction->trans_id,



            "cart_currency" => "SAR",



           "cart_id" => $transaction->cart_id,



            "cart_description" => "Technical Problem in Printing the Customer's Order",



            "tran_type" => "refund",



            "tran_class" => "ecom",



            // Add other required order details

        ];



        $response = Http::withHeaders([

            "authorization" => $secretKey,



            "content-type" => "application/json",

        ])->post($apiEndpoint, $data);



        $responseData = json_decode($response, true);

        $debug_value = print_r($responseData, true);

         Storage::disk("local")->put("khttki_paymentRefund_debug.txt", $debug_value);

         $data = $responseData;

         $status = isset($data["payment_result"]["response_status"])?$data["payment_result"]["response_status"]:"D";

         if($status == 'A')
         {
             $transaction->status = 'Refunded';
             $transaction->save();
             return back()->with("success", "Refund Initiated");
         }
         else {
             $transaction->status = "Refund Failed";
             $transaction->save();
             return back()->with("error", "Refund Declined Contact Bank");
         }





    }

    public function splitPayment($amount, $trans_id, $page, $type)
{
    $uniqueID = uniqid(); // Generate a unique ID
    $hash = md5($uniqueID); // Create an MD5 hash
    $uniqueHash = substr($hash, 0, 4);

    $transaction = Transaction::where('id', $trans_id)->first();
    $transaction->cart_id = "$trans_id"."_"."$uniqueHash";
    $shopid = $transaction->shop_id;
    $shop = Shop::find($shopid);
    $financialDetails = $shop->financialDetails;
    $transaction->save();

    if($type == 'card') {
        $type = array('creditcard', 'mada');
    } else {
        $type = array('applepay');
    }

    $profile_id = "47128"; // Example profile_id
    $apiEndpoint = "https://secure.clickpay.com.sa/payment/request";
    $apiKey = config("services.clickpay.api_key");

    $secretKey = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J";

    // Main payment details
    $data = [
        "profile_id" => $profile_id,
        "cart_amount" => $amount,
        "cart_currency" => "SAR",
        "cart_id" => "$trans_id"."_"."$uniqueHash",
        "cart_description" => "Description of items/services #$trans_id",
        "paypage_lang" => "en",
        "tran_type" => "sale",
        "tran_class" => "ecom",
        "callback" => "https://hlaprint.com/handle-payment-response",
        "return" => "https://hlaprint.com/handle-payment-end/$trans_id/$page",
        "customer_details" => [
            "name" => "first last",
            "email" => "email@domain.com",
            "phone" => "0522222222",
            "street1" => "address street",
            "city" => "dubai",
            "state" => "du",
            "country" => "AE",
            "zip" => "12345"
        ],
        "shipping_details" => [
            "name" => "name1 last1",
            "email" => "email1@domain.com",
            "phone" => "+966XXXXXXXXX",
            "street1" => "street2",
            "city" => "dubai",
            "state" => "dubai",
            "country" => "AE",
            "zip" => "54321"
        ],
        // Split payouts
        "split_payout" => [
            [
                "entity_id" => 1000,
                "entity_name" => "Hlaprint",
                "item_description" => "Service Charges",
                "item_total" => "0.00",
                "msc_flag" => "z",
                "beneficiary" => [
                    "name" => "Customer Name",
                    "account_number" => "SAXX0X0000XXX000XXXXX000",
                    "country" => "SA",
                    "bank" => "bank name",
                    "bank_branch" => "",
                    "email" => "email@domain.com",
                    "mobile_number" => "+966XXXXXXXXX",
                    "address_1" => "Riyadh",
                    "address_2" => ""
                ]
            ],
            [
                "entity_id" => 1001,
                "entity_name" => $shop->name,

                "item_description" => "Delivery Fee",
                "item_total" => "15.00",
                "msc_flag" => "Z",
                "beneficiary" => [
                    "name" => "customer name",
                    "account_number" => "SAXX0X0000XXX000XXXXX000",
                    "country" => "SA",
                    "bank" => "bank name",
                    "bank_branch" => "",
                    "email" => "email@gmail.com",
                    "mobile_number" => "+966XXXXXXXXX",
                    "address_1" => "Riyadh",
                    "address_2" => ""
                ]
            ],
            [
                "entity_id" => 1002,
                "entity_name" => "Tip",
                "item_description" => "Tip",
                "item_total" => "10.00",
                "msc_flag" => "Z",
                "beneficiary" => [
                    "name" => "customer name",
                    "account_number" => "SAXX0X0000XXX000XXXXX000",
                    "country" => "SA",
                    "bank" => "Bank name",
                    "bank_branch" => "",
                    "email" => "email@domain.com",
                    "mobile_number" => "+966XXXXXXXXX",
                    "address_1" => "Riyadh",
                    "address_2" => ""
                ]
            ]
        ]
    ];

    $response = Http::withHeaders([
        "authorization" => $secretKey,
        "content-type" => "application/json"
    ])->post($apiEndpoint, $data);

    $responseData = json_decode($response, true);

    $debug_value = print_r($responseData, true);
    Storage::disk("local")->put("split_payment_debug.txt", $debug_value);

    return $responseData;
}


   public function splitPaymentTest()
{
    $amount = 100;
    $trans_id = 2680;
    $page = "arabic";
    $type = "all";
    $uniqueID = uniqid(); // Generate a unique ID
    $hash = md5($uniqueID); // Create an MD5 hash
    $uniqueHash = substr($hash, 0, 4);

    $transaction = Transaction::where('id', $trans_id)->first();
    $transaction->cart_id = "$trans_id"."_"."$uniqueHash";
    $shopid = $transaction->shop_id;
    $shop = Shop::find($shopid);
    $financialDetails = $shop->financialDetails;
    $transaction->save();

    if($type == 'card') {
        $type = array('creditcard', 'mada');
    } else {
        $type = array('applepay');
    }

    $profile_id = "47128"; // Example profile_id
    $apiEndpoint = "https://secure.clickpay.com.sa/payment/request";
    $apiKey = config("services.clickpay.api_key");

    $secretKey = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J";

    // Main payment details
    $data = [
        "profile_id" => $profile_id,
        "cart_amount" => $amount,
        "cart_currency" => "SAR",
        "cart_id" => "$trans_id"."_"."$uniqueHash",
        "cart_description" => "Description of items/services #$trans_id",
        "paypage_lang" => "en",
        "tran_type" => "sale",
        "tran_class" => "ecom",
        "callback" => "https://hlaprint.com/handle-payment-response",
        "return" => "https://hlaprint.com/handle-payment-end/$trans_id/$page",
        "customer_details" => [
            "name" => "first last",
            "email" => "email@domain.com",
            "phone" => "0522222222",
            "street1" => "address street",
            "city" => "dubai",
            "state" => "du",
            "country" => "AE",
            "zip" => "12345"
        ],
        "shipping_details" => [
            "name" => "name1 last1",
            "email" => "email1@domain.com",
            "phone" => "+966XXXXXXXXX",
            "street1" => "street2",
            "city" => "dubai",
            "state" => "dubai",
            "country" => "AE",
            "zip" => "54321"
        ],
        // Split payouts
        "split_payout" => [
            [
                "entity_id" => 1000,
                "entity_name" => "Hlaprint",
                "item_description" => "Service Charges",
                "item_total" => "0.00",
                "msc_flag" => "z",
                "beneficiary" => [
                    "name" => "Customer Name",
                    "account_number" => "SAXX0X0000XXX000XXXXX000",
                    "country" => "SA",
                    "bank" => "bank name",
                    "bank_branch" => "",
                    "email" => "email@domain.com",
                    "mobile_number" => "+966XXXXXXXXX",
                    "address_1" => "Riyadh",
                    "address_2" => ""
                ]
            ],
            [
                "entity_id" => 1001,
                "entity_name" => $shop->name,

                "item_description" => "Delivery Fee",
                "item_total" => "15.00",
                "msc_flag" => "Z",
                "beneficiary" => [
                    "name" => "customer name",
                    "account_number" => "SAXX0X0000XXX000XXXXX000",
                    "country" => "SA",
                    "bank" => "bank name",
                    "bank_branch" => "",
                    "email" => "email@gmail.com",
                    "mobile_number" => "+966XXXXXXXXX",
                    "address_1" => "Riyadh",
                    "address_2" => ""
                ]
            ],
            [
                "entity_id" => 1002,
                "entity_name" => "Tip",
                "item_description" => "Tip",
                "item_total" => "10.00",
                "msc_flag" => "Z",
                "beneficiary" => [
                    "name" => "customer name",
                    "account_number" => "SAXX0X0000XXX000XXXXX000",
                    "country" => "SA",
                    "bank" => "Bank name",
                    "bank_branch" => "",
                    "email" => "email@domain.com",
                    "mobile_number" => "+966XXXXXXXXX",
                    "address_1" => "Riyadh",
                    "address_2" => ""
                ]
            ]
        ]
    ];

    $response = Http::withHeaders([
        "authorization" => $secretKey,
        "content-type" => "application/json"
    ])->post($apiEndpoint, $data);

    $responseData = json_decode($response, true);

    $debug_value = print_r($responseData, true);
    Storage::disk("local")->put("split_payment_debug.txt", $debug_value);

    return $responseData;
}

    public function initiatePayment($amount, $trans_id, $page, $type)

    {
         $uniqueID = uniqid(); // Generate a unique ID
        $hash = md5($uniqueID); // Create an MD5 hash
        $uniqueHash = substr($hash, 0, 4);

        $transaction = Transaction::where('id',$trans_id)->first();
        $transaction->cart_id = "$trans_id"."_"."$uniqueHash";
        $transaction->save();

        if($type== 'card')
        {
            $type = array('creditcard','mada');//"['creditcard','mada']";
        }
        else {
            $type = array('applepay');
        }


       //$profile_id = "43478";

       $profile_id = "43626";



        $apiEndpoint = "https://secure.clickpay.com.sa/payment/request";



        $apiKey = config("services.clickpay.api_key");



        $secretKey = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J"; //Original //config("services.clickpay.secret_key");
        //$secretKey = "SLJNLNGKWL-J6W6NLDMT2-KJNHTJ99JD";



        $data = [

            "profile_id" => $profile_id,



            "cart_amount" => $amount,



            "cart_currency" => "SAR",



            "cart_id" => "$trans_id"."_"."$uniqueHash",



            "cart_description" => "Print order for HLAprint #$trans_id",

            'payment_methods' => $type,



            "tran_type" => "sale",



            "tran_class" => "ecom",



            "paypage_lang" => "ar",

            "callback" =>

                "https://hlaprint.com/handle-payment-response",



            "return" => "https://hlaprint.com/handle-payment-end/$trans_id/$page",

            "customer_details" => array(

                "name" => "Naif Alhassoun",
                "email" => "customer@hlaprint.com",
                "street1" => "Unknown",
                "city" => "Riyadh",
                "state" =>"Riyadh",
                "country" => "SA",
                "ip" => $_SERVER['REMOTE_ADDR']
                ),



            // Add other required order details

        ];



        $response = Http::withHeaders([

            "authorization" => $secretKey,



            "content-type" => "application/json",

        ])->post($apiEndpoint, $data);



        $responseData = json_decode($response, true);



        if ($responseData) {

            $tranRef = $responseData;

        }

        $debug_value = print_r($responseData,true);

        Storage::disk("local")->put("khttki_paymentReq_debug.txt", $debug_value);
        return $tranRef;

    }
    public function capturePaymentTest($trans_id)
    {


            $transaction = Transaction::where('id',$trans_id)->first();

           // $profile_id = "43478";

            $profile_id = "43626";



            $apiEndpoint = "https://secure.clickpay.com.sa/payment/request";



            $apiKey = config("services.clickpay.api_key");



            $secretKey = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J"; //config("services.clickpay.secret_key");



            $data = [

                "profile_id" => $profile_id,

                "trans_ref" => $transaction->$trans_id,
                "cart_id" => $transaction->cart_id,
                "cart_description" => "Print order for HLAprint #$trans_id",
                "cart_amount" => $transaction->amount,



            "cart_currency" => "SAR",


                "tran_type" => "capture",



                "tran_class" => "ecom",

                "callback" =>

                    "https://hlaprint.com/handle-payment-response",




  // Add other required order details

            ];



            $response = Http::withHeaders([

                "authorization" => $secretKey,



                "content-type" => "application/json",

            ])->post($apiEndpoint, $data);



            $responseData = json_decode($response, true);

            print_r($responseData);



    }

    public function capturePayment($trans_id)

    {
        $transaction = Transaction::where('id',$trans_id)->first();

        // $profile_id = "43478";

         $profile_id = "43626";



         $apiEndpoint = "https://secure.clickpay.com.sa/payment/request";



         $apiKey = config("services.clickpay.api_key");



         $secretKey = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J"; //config("services.clickpay.secret_key");



         $data = [

             "profile_id" => $profile_id,

             "trans_ref" => $transaction->$trans_id,
             "cart_id" => $transaction->cart_id,
             "cart_description" => "Print order for HLAprint #$trans_id",
             "cart_amount" => $transaction->amount,



         "cart_currency" => "SAR",


             "tran_type" => "capture",



             "tran_class" => "ecom",

             "callback" =>

                 "https://hlaprint.com/handle-payment-response",




// Add other required order details

         ];



         $response = Http::withHeaders([

             "authorization" => $secretKey,



             "content-type" => "application/json",

         ])->post($apiEndpoint, $data);



         $responseData = json_decode($response, true);



        if ($responseData) {

            $tranRef = $responseData;

        }

        $debug_value = print_r($responseData,true);

        Storage::disk("local")->put("khttki_paymentReq_debug.txt", $debug_value);
        return $tranRef;

    }



    public function handlePaymentEnd($trans_id, $page, $secondTime = false)

    {

        // echo $page;exit;



        $trans_id = (int) $trans_id;

        //

        // procedure to create invoice of multiple images



        // get transaction
        $start = microtime(true); // Get the current time in microseconds

                // Wait for 5 seconds
                while (microtime(true) - $start < 5) {
                    // Do nothing, just wait
                }

        $transaction = Transaction::where("id", $trans_id)->first();

        // $currentDateTime = new DateTime();

        // // Set the timezone to GMT+5 (Eastern Standard Time)
        // $timeZone = new DateTimeZone('GMT+5');
        // $currentDateTime->setTimezone($timeZone);

        // // Format the date/time according to your requirements
        // $currentTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');

        // // Log the formatted current time
        // \Illuminate\Support\Facades\Log::debug('Transaction ('.$trans_id.') : checked at ' . $currentTimeFormatted);



        if ($transaction) {



            if ($transaction->status == "A") {

                // all print job ids

                $printJobsIds = json_decode($transaction->print_job_id);

                $printJob = PrintJob::where("id",$printJobsIds[0])->first();
                $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();





                $wa = new WAController();

                if($printJob->shop_id == 13)
                {
                    $wa->sendInvoice($printJob->code, $printJob->phone,$printJob->shop_id);
                }
                else {

                    $wa->sendInvoice($invoice->monthly_id, $printJob->phone,$printJob->shop_id);
                    event(new PaymentReceived($trans_id));

                }







                // get all data of fetched ids from transaction

                $fetchJobs=[];



                return redirect()->route("arabicSuccess");
                die();
                foreach($printJobsIds as $id){


                   $jobs =  PrintJob::where("id",$id)->first();

                   $shop =  $jobs->shop_id;

                   $code = $jobs->code;

                   $phone = $jobs->phone;

                   array_push($fetchJobs,$jobs);



                }

                 DB::table("user_codes")



                ->where("code", $code)



                ->update(["status" => "1"]);

                // get invoice data to print out invoice id number only



                $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();



                // get shop details

                if($shop){

                    $shopData=Shop::where("id", $shop)->first();

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

                }else{

                    $invoiceDetails = DB::table("invoice_details")->where("shop_id", "0")->first();



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

                $fetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$fetchJobs,$invoice);



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

                                echo "nothing";

                                exit;

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

                        print_r($bw_printers);

                        echo "<br> SHOP ID : ".$shop;

                        exit;

                    }



                    } else {

                        echo "nothing";

                        exit;

                    }

                    // $printrequest = new PrintRequestsController();





                }



                $printrequest = new PrintRequestsController();

                if($selectedPrinterColor == $selectedPrinterBW)

                {

                    $invoiceType = 'combined';

                    $fetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$finalFetchJobs,$invoice);





                    $printrequest->generateInvoice($fetchJobs,$fetchPDF,$selectedPrinterColor );

                        $wa = new WAController();

                    $wa->sendInvoice($invoice->monthly_id, $userPhone);

                }

                else {

                    $invoiceType = 'color';

                    $colorfetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$colorFetchJobs,$invoice);

                    $invoiceType = 'BW';

                    $BWfetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$BWFetchJobs,$invoice);

                        $wa = new WAController();

                    $wa->sendInvoice($invoice->monthly_id, $userPhone);



                    // echo $colorfetchPDF;

                    // echo $BWfetchPDF;

                    if($printedBlack)

                    {

                        $printrequest->generateInvoice($BWFetchJobs, $BWfetchPDF,$selectedPrinterBW );

                    }

                    if($printedColored)

                    {

                        $printrequest->generateInvoice($colorFetchJobs, $colorfetchPDF,$selectedPrinterColor );

                    }







                }





                if ($page == "arabic") {

                    return view('arabic.loading', compact('trans_id'));

                } else {

                    return redirect()->route("englishSuccess");

                }

            }
            elseif($transaction->status == "Completed"){

                if ($page == "arabic") {

                    return redirect()->route("arabicSuccess");

                }


                else {

                    return redirect()->route("englishSuccess");

                }

            }

            else if($transaction->status == "D") {

                // Transaction::where('id',$trans_id)->update(['status',=>'D']);



                if ($page == "arabic") {

                    return redirect()->route("arabicError");

                } else {

                    return redirect()->route("englishError");

                }



                return "Transaction Unsucessfull";

            }
            else {
                return redirect()->route('handle-payment-end', ['trans_id' => $trans_id, 'page' => $page]);
            }

        } else {

            return "No Transaction Found";

        }

    }

    public function CheckPaymentAgain($trans_id, $page, $secondTime = false)

    {

        // echo $page;exit;



        $trans_id = (int) $trans_id;

        //

        // procedure to create invoice of multiple images



        // get transaction

        $transaction = Transaction::where("id", $trans_id)->first();

        // $currentDateTime = new DateTime();

        // // Set the timezone to GMT+5 (Eastern Standard Time)
        // $timeZone = new DateTimeZone('GMT+5');
        // $currentDateTime->setTimezone($timeZone);

        // // Format the date/time according to your requirements
        // $currentTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');

        // // Log the formatted current time
        // \Illuminate\Support\Facades\Log::debug('Transaction ('.$trans_id.') : checked at ' . $currentTimeFormatted);



        if ($transaction) {

            if($secondTime)
            {
                return "Second Time";
            }

            if ($transaction->status == "A") {

                // all print job ids

                $printJobsIds = json_decode($transaction->print_job_id);

                $printJob = PrintJob::where("id",$printJobsIds[0])->first();
                $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();





                $wa = new WAController();

                $wa->sendInvoice($invoice->monthly_id ,
                $printJob->phone, $printJob->shop_id);





                // get all data of fetched ids from transaction

                $fetchJobs=[];


                event(new PaymentReceived($trans_id));
                return redirect()->route("arabicSuccess");
                die();
                foreach($printJobsIds as $id){


                   $jobs =  PrintJob::where("id",$id)->first();

                   $shop =  $jobs->shop_id;

                   $code = $jobs->code;

                   $phone = $jobs->phone;

                   array_push($fetchJobs,$jobs);



                }

                 DB::table("user_codes")



                ->where("code", $code)



                ->update(["status" => "1"]);

                // get invoice data to print out invoice id number only



                $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();



                // get shop details

                if($shop){

                    $shopData=Shop::where("id", $shop)->first();

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

                }else{

                    $invoiceDetails = DB::table("invoice_details")->where("shop_id", "0")->first();



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

                $fetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$fetchJobs,$invoice);



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

                                echo "nothing";

                                exit;

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

                        print_r($bw_printers);

                        echo "<br> SHOP ID : ".$shop;

                        exit;

                    }



                    } else {

                        echo "nothing";

                        exit;

                    }

                    // $printrequest = new PrintRequestsController();





                }



                $printrequest = new PrintRequestsController();

                if($selectedPrinterColor == $selectedPrinterBW)

                {

                    $invoiceType = 'combined';

                    $fetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$finalFetchJobs,$invoice);





                    $printrequest->generateInvoice($fetchJobs,$fetchPDF,$selectedPrinterColor );

                        $wa = new WAController();

                    $wa->sendInvoice($invoice->monthly_id, $userPhone);

                }

                else {

                    $invoiceType = 'color';

                    $colorfetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$colorFetchJobs,$invoice);

                    $invoiceType = 'BW';

                    $BWfetchPDF = $this->createNewInvoice($transaction,$invoiceDetails,$BWFetchJobs,$invoice);

                        $wa = new WAController();

                    $wa->sendInvoice($invoice->monthly_id, $userPhone);



                    // echo $colorfetchPDF;

                    // echo $BWfetchPDF;

                    if($printedBlack)

                    {

                        $printrequest->generateInvoice($BWFetchJobs, $BWfetchPDF,$selectedPrinterBW );

                    }

                    if($printedColored)

                    {

                        $printrequest->generateInvoice($colorFetchJobs, $colorfetchPDF,$selectedPrinterColor );

                    }







                }





                if ($page == "arabic") {

                    return view('arabic.loading', compact('trans_id'));

                } else {

                    return redirect()->route("englishSuccess");

                }

            }
            elseif($transaction->status == "Completed"){

                if ($page == "arabic") {

                    return redirect()->route("arabicSuccess");

                }


                else {

                    return redirect()->route("englishSuccess");

                }

            }
            elseif(!$secondTime)
            {

                $start = microtime(true); // Get the current time in microseconds

                // Wait for 3 seconds
                while (microtime(true) - $start < 3) {
                    // Do nothing, just wait
                }

                $this->handlePaymentEnd($trans_id, $page, true);
            }
            else {

                // Transaction::where('id',$trans_id)->update(['status',=>'D']);



                if ($page == "arabic") {

                    return redirect()->route("arabicError");

                } else {

                    return redirect()->route("englishError");

                }



                return "Transaction Unsucessfull";

            }

        } else {

            return "No Transaction Found";

        }

    }




    public function cashOrderProcess($trans_id)

    {



         $transaction = Transaction::where("id", $trans_id)->first();

        $data = [

            "status" => "PAID"];

        $transaction->update($data);



        if ($transaction) {

            if ($transaction->status == "PAID") {

                // all print job ids

                $printJobsIds = json_decode($transaction->print_job_id);

                $printJob = PrintJob::where("id",$printJobsId[0])->first();
                $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();



                event(new PaymentReceived($trans_id));

                $wa = new WAController();

                $wa->sendInvoice($invoice->monthly_id, $printJob->phone, $printJob->shop_id);

                die();

                // get all data of fetched ids from transaction

                $fetchJobs=[];



                foreach($printJobsIds as $id){

                   $jobs =  PrintJob::where("id",$id)->first();

                   $shop =  $jobs->shop_id;

                   $code = $jobs->code;

                   $phone = $jobs->phone;

                   array_push($fetchJobs,$jobs);



                }

                // get invoice data to print out invoice id number only



                $invoice = DB::table("invoices")->where("trans_id", $transaction->id)->first();



                // get shop details

                if($shop){

                    $shopData=Shop::where("id", $shop)->first();

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

                $fetchPDF = $this->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$fetchJobs,$invoiceType, $priceDetails );



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

                                echo "nothing";

                                exit;

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

                        print_r($bw_printers);

                        echo "<br> SHOP ID : ".$shop;

                        exit;

                    }



                    } else {

                        echo "nothing";

                        exit;

                    }

                    // $printrequest = new PrintRequestsController();





                }



                $printrequest = new PrintRequestsController();

                if($selectedPrinterColor == $selectedPrinterBW)

                {

                    $invoiceType = 'combined';

                    $fetchPDF = $this->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$finalFetchJobs,$invoiceType, $priceDetails );





                    $printrequest->generateInvoice($fetchJobs,$fetchPDF,$selectedPrinterColor );









                }

                else {

                    $invoiceType = 'color';

                    $colorfetchPDF = $this->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$colorFetchJobs,$invoiceType, $priceDetails );

                    $invoiceType = 'BW';

                    $BWfetchPDF = $this->createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$BWFetchJobs,$invoiceType, $priceDetails );





                    // echo $colorfetchPDF;

                    // echo $BWfetchPDF;

                    if($printedBlack)

                    {

                        $printrequest->generateInvoice($BWFetchJobs, $BWfetchPDF,$selectedPrinterBW );





                    }

                    if($printedColored)

                    {

                        $printrequest->generateInvoice($colorFetchJobs, $colorfetchPDF,$selectedPrinterColor );

                         $wa = new WAController();

                    $wa->sendInvoice($invoice->monthly_id, $userPhone);



                    }







                }





                if ($page == "arabic") {

                   return redirect()->route("arabicSuccess");

                } else {

                    return redirect()->route("englishSuccess");

                }

            } else {

                // Transaction::where('id',$trans_id)->update(['status',=>'D']);



                if ($page == "arabic") {

                    return redirect()->route("arabicError");

                } else {

                    return redirect()->route("englishError");

                }



                return "Transaction Unsucessfull";

            }

        } else {

            return "No Transaction Found";

        }



    }



    public function handlePaymentResponse(Request $request)

    {

        // dd($request->all());



        // Handle ClickPay's response here

        // $currentDateTime = new DateTime();

        // // Set the timezone to GMT+5 (Eastern Standard Time)
        // $timeZone = new DateTimeZone('GMT+5');
        // $currentDateTime->setTimezone($timeZone);

        // // Format the date/time according to your requirements
        // $currentTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');

        // // Log the formatted current time
        // \Illuminate\Support\Facades\Log::debug('Transaction ('.$trans_id.') : changed at ' . $currentTimeFormatted);



        $debug_value = print_r($request->all(), true);



        $data = $request->all();



        $trans_ref = $data["tran_ref"];

        $trans_type = $data['tran_type'];



        Storage::disk("local")->put("khttki_payment_debug.txt", $debug_value);



        $transaction = Transaction::where("trans_id", $trans_ref)->first();

        if($trans_type == 'Auth')
        {
            $transaction->status = 'Auth';
        }
        elseif($trans_type == 'Capture')
        {
            $transaction->status = "Completed";//$data["payment_result"]["response_status"];
        }
        else {
            $transaction->status = $data["payment_result"]["response_status"];
        }




        $transaction->type = "online";



        $transaction->update();



        $res = [

            "status" => "200",

        ];



        return response()->json($res);

    }

    public function createNewInvoice($transaction,$invoiceDetails,$printjobs,$invoice)
    {    $phone = 0;
        $code = 0;
        foreach($printjobs as $u)
        {
            $phone = $u->phone;
            $code = $u->code;
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



        $qrCodeContent = [

            "id" => $invoice->id,



            "invoice" => $invoice->invoice_number,



            "trans_id" => $transaction->trans_id,



            "date" => $invoice->created_at,



            "amount" => $transaction->amount,

        ];

        $jsonqrCodeContent = json_encode($qrCodeContent);

        // Generate the QR code
        $qrCode = QrCode::size(150)->generate($jsonqrCodeContent);

        $html = view("InvoiceNew",compact('priceVariation','transaction','invoiceDetails','qrCode','invoice'))->toArabicHTML();

        $pdf = PDF::loadHTML($html)->output();

        if (!File::isDirectory( public_path("/storage/WhatsApp_Files/Invoices/BW/" . $phone)  )

            ) {

                File::makeDirectory(

                    public_path("/storage/WhatsApp_Files/Invoices/BW/" .  $phone),

                    0755,

                    true,

                    true

                );

            }

            $pdfPath = public_path(

                "/storage/WhatsApp_Files/Invoices/BW/" .

                $phone .

                    "/" .

                    $code .

                    ".pdf"

            );


            file_put_contents($pdfPath, $pdf);

            $debug = print_r($pdfPath, true);

            $pdfPath = asset("public/storage/WhatsApp_Files/Invoices/BW/" .$phone . "/" . $code .".pdf");

            Storage::disk("local")->put("khttki_BWinvoice_debug.txt", $debug);

            return $pdfPath;


    }

    public function createInvoice($transaction,$name,$shopPhone,$invoice,$invoiceDetails,$fetchJobs,$invoiceType, $priceDetails ){



        $price=0;

        $phone =0;

        $shop_id=0;
        $finalPrice=0;
        $total_pages = 0;

        $priceArr = ['id' => [], 'price' => []];

        foreach($fetchJobs as $job){

            $homeRequest = new HomeController();
            // 1 tym = 0   2- tym p=2
            if($job->pages_start == '1' && $job->page_end == '1')
            {
               $total_pages = $job->total_pages;
            }
            else {
                 $total_pages = ($job->page_end - $job->pages_start) + 1;
            }

            $price =  $homeRequest->calculateTotals($job->id,$job->total_pages);

            // p = 2  2+6
            $finalPrice+=$price;

            $priceArr['id'][] = $job->id;

            $priceArr['price'][] = $price;

            $shop_id =$job->shop_id;

            $phone = $job->phone;

            $code = $job->code;

        }


        if($price<1)

        {

            $price = 1;

        }



        $qrCodeContent = [

            "id" => $invoice->id,



            "invoice" => $invoice->invoice_number,



            "trans_id" => $transaction->trans_id,



            "date" => $invoice->created_at,



            "amount" => $finalPrice*1.15,

        ];



        $code = $invoice->id;



        $jsonqrCodeContent = json_encode($qrCodeContent);



        // Generate the QR code



        $qrCode = QrCode::size(150)->generate($jsonqrCodeContent);



        $carbonDate = Carbon::parse($invoice->created_at);

        $formattedMonth = $carbonDate->format('m');

        // $month = $invoice->created_at->format('m');







        // <!-- get all price  -->

        // echo $shop_id;exit;

        if($shop_id){

            $pricing = DB::table('color_sizes')->where('shop_id',$shop_id)->first();

        }else{

            $pricing = DB::table('color_sizes')->where('shop_id','0')->first();

        }



        $size_amount_data = unserialize($pricing->size_amount);





        // send invoice data to invoice blade

        $colorPrice =  $priceDetails->color_page_amount;

        $bwPrice =  $priceDetails->black_and_white_amount;

        $doublePrice = $priceDetails->double_side;

        $singlePrice = $priceDetails->one_side;

        $invoiceData = [

            "id" => $invoice->id,



            "colorPrice" => $colorPrice,



            "bwPrice"=>$bwPrice,



            "doublePrice"=>$doublePrice,



            "singlePrice"=> $singlePrice,



            "invoiceType"=>$invoiceType,



            "shop_id"=>$shop_id,



            "size_amount_data"=> $size_amount_data ,



            "priceArr"=>$priceArr,



            "phone"=>$shopPhone,



            "invoice" => $invoice->invoice_number,



            "trans_id" => $transaction->trans_id,



            "date" => $invoice->created_at,



            "amount"=>$finalPrice*1.15,



            "shop" => $name,



            "type" => $transaction->type,



            "qrCode" => $qrCode,



            "name" => $invoiceDetails->name,



            "address" => $invoiceDetails->address,



            "license_number" => $invoiceDetails->license_number,



            "vat_number" => $invoiceDetails->vat_number,



            // Add other data here

        ];



    //    print_r(public_path("/storage/WhatsApp_Files/Invoices/Color/" . $phone));exit;



        $html = view("invoice",compact('invoiceData','fetchJobs'))->toArabicHTML();





        $pdf = PDF::loadHTML($html)->output();

        if($invoiceType == "color"){

            if (!File::isDirectory( public_path("/storage/WhatsApp_Files/Invoices/Color/" . $phone)  )

            ) {

                File::makeDirectory(

                    public_path("/storage/WhatsApp_Files/Invoices/Color/" .  $phone),

                    0755,

                    true,

                    true

                );

            }

            $pdfPath = public_path(

                "/storage/WhatsApp_Files/Invoices/Color/" .

                $phone .

                    "/" .

                    $code .

                    ".pdf"

            );





            file_put_contents($pdfPath, $pdf);

            $debug = print_r($pdfPath, true);

            $pdfPath = asset("public/storage/WhatsApp_Files/Invoices/Color/" .$phone . "/" . $code .".pdf");

            Storage::disk("local")->put("khttki_colorinvoice_debug.txt", $debug);

        }else{

            if (!File::isDirectory( public_path("/storage/WhatsApp_Files/Invoices/BW/" . $phone)  )

            ) {

                File::makeDirectory(

                    public_path("/storage/WhatsApp_Files/Invoices/BW/" .  $phone),

                    0755,

                    true,

                    true

                );

            }

            $pdfPath = public_path(

                "/storage/WhatsApp_Files/Invoices/BW/" .

                $phone .

                    "/" .

                    $code .

                    ".pdf"

            );





            file_put_contents($pdfPath, $pdf);

            $debug = print_r($pdfPath, true);

            $pdfPath = asset("public/storage/WhatsApp_Files/Invoices/BW/" .$phone . "/" . $code .".pdf");

            Storage::disk("local")->put("khttki_BWinvoice_debug.txt", $debug);



        }









        return $pdfPath;







    }

    public function applePayTest()
    {
        return view('applepayweb');
    }


    //APPLE PAY PAYMENT

    public function validateApplePay(Request $request)
    {
        $appleMerchantId = "merchant.com.hlaprint.mainPrint";//config('app.apple_merchant_id');
        $appleSslCertFile = "/certs/merchant-cert.crt"; //config('app.apple_ssl_cert_file');
        $appleCertKeyFile = "/certs/merchant-cert.key";


        // $appleSslCertFile = storage_path('/cert/'.$appleSslCertFile);
        // $appleCertKeyFile = storage_path('/cert/'.$appleCertKeyFile);

      //  return $appleSslCertFile;

        $validation_url = $request->input('vurl');
        if (!$validation_url) {
            return response('No vURL', 400);
        }

        $applepay_url = $validation_url;
        $applepay_data = [
            'merchantIdentifier' => $appleMerchantId,
            'displayName' => "Hlaprint",
            'initiative' => "web",
            'initiativeContext' => "hlaprint.com",
        ];

        $result = $this->sendRequest($applepay_url, $applepay_data, $appleSslCertFile, $appleCertKeyFile);

        return $result;
    }

    private function sendRequest($request_url, $values, $sslCertFile, $certKeyFile)
    {
        $headers = [
            'Content-Type: application/json',
        ];

        $post_params = json_encode($values);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Set certificate files from local storage
        $cert_path = Storage::path($sslCertFile);
        $key_path = Storage::path($certKeyFile);



        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLCERT, $cert_path);
        curl_setopt($ch, CURLOPT_SSLKEY, $key_path);

        $result = curl_exec($ch);

        $error_num = curl_errno($ch);
        if ($error_num) {
            $error_msg = curl_error($ch);
            return "Error response [($error_num) $error_msg], [$result]";
            ClickpayHelper::log("Error response [($error_num) $error_msg], [$result]", 1);
        }

        curl_close($ch);

        return $result;
    }

     public function processApplePayment(Request $request)
    {
        // Load configuration from .env
        $profile_id = 43626;// config('app.profile_id');
        $ap_currency = "SAR";
        $endpoint = 'click';//config('app.endpoint');
        $server_key = "SWJNLNGKWZ-JHBMLBWDKM-T6JH6G9B6J"; //config('app.server_key');

        $payment = $request->getContent();
        $payment_token = json_decode($payment, true);

        $pt_holder = new ClickpayApplePayHolder();
        $pt_holder
            ->set01PaymentCode('applepay')
            ->set02Transaction(ClickpayEnum::TRAN_TYPE_SALE, $profile_id)
            ->set03Cart('applepay_01', $ap_currency, 1.30, 'ApplePay Sample')
            ->set04CustomerDetails('Naif Alhassoun', 'customer@hlaprint.com', '0555555555', 'hlaprint applepay', 'Riyadh', 'Riyadh', 'SA', null, $_SERVER['REMOTE_ADDR'])
            ->set07URLs("https://hlaprint.com/arabic/success", null)
            ->set50ApplePay($payment_token)
            ->set99PluginInfo('PHP Pure', '1.0.0');


        $pt_body = $pt_holder->pt_build();

       // Log::info(json_encode($pt_body));

        $pt_api = ClickpayApi::getInstance($endpoint, $profile_id, $server_key);

        $result = $pt_api->create_pay_page($pt_body);

      //  Log::info(json_encode($result));

        return response()->json([
            "success" => $result->success,
            "result" => $result,
        ]);
    }

}
define('CLICKPAY_SDK_VERSION', '2.12.0');

define('CLICKPAY_DEBUG_FILE_NAME', 'debug_clickpay.log');
define('CLICKPAY_DEBUG_SEVERITY', ['Info', 'Warning', 'Error']);
define('CLICKPAY_PREFIX', 'Clickpay');
abstract class ClickpayHelper
{
    static function paymentType($key)
    {
        return ClickpayApi::PAYMENT_TYPES[$key]['name'];
    }

    static function paymentAllowed($code, $currencyCode)
    {
        $row = null;
        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if ($value['name'] === $code) {
                $row = $value;
                break;
            }
        }
        if (!$row) {
            return false;
        }
        $list = $row['currencies'];
        if ($list == null) {
            return true;
        }

        $currencyCode = strtoupper($currencyCode);

        return in_array($currencyCode, $list);
    }

    static function isClickpayPayment($code)
    {
        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if ($value['name'] === $code) {
                return true;
            }
        }
        return false;
    }

    static function isCardPayment($code, $is_international = false)
    {
        $group = $is_international ? ClickpayApi::GROUP_CARDS_INTERNATIONAL : ClickpayApi::GROUP_CARDS;

        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if ($value['name'] === $code) {
                return in_array($group, $value['groups']);
            }
        }
        return false;
    }

    static function getCardPayments($international_only = false, $currency = null)
    {
        $methods = [];

        $group = $international_only ? ClickpayApi::GROUP_CARDS_INTERNATIONAL : ClickpayApi::GROUP_CARDS;

        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if (in_array($group, $value['groups'])) {
                if ($currency) {
                    if ($value['currencies'] == null || in_array($currency, $value['currencies'])) {
                        $methods[] = $value['name'];
                    }
                } else {
                    $methods[] = $value['name'];
                }
            }
        }
        return $methods;
    }

    static function supportTokenization($code)
    {
        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if ($value['name'] === $code) {
                return in_array(ClickpayApi::GROUP_TOKENIZE, $value['groups']);
            }
        }
        return false;
    }

    static function supportAuthCapture($code)
    {
        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if ($value['name'] === $code) {
                return in_array(ClickpayApi::GROUP_AUTH_CAPTURE, $value['groups']);
            }
        }
        return false;
    }

    static function supportIframe($code)
    {
        foreach (ClickpayApi::PAYMENT_TYPES as $key => $value) {
            if ($value['name'] === $code) {
                return in_array(ClickpayApi::GROUP_IFRAME, $value['groups']);
            }
        }
        return false;
    }

    //

    static function read_ipn_response()
    {
        $response = file_get_contents('php://input');
        $data = json_decode($response);

        return $data;
    }

    /**
     * @return the first non-empty var from the vars list
     * @return null if all params are empty
     */
    public static function getNonEmpty(...$vars)
    {
        foreach ($vars as $var) {
            if (!empty($var)) return $var;
        }
        return null;
    }

    /**
     * convert non-english digits to English
     * used for fileds that accepts only English digits like: "postal_code"
     */
    public static function convertAr2En($string)
    {
        $nonEnglish = [
            // Arabic
            [
                '٠',
                '١',
                '٢',
                '٣',
                '٤',
                '٥',
                '٦',
                '٧',
                '٨',
                '٩'
            ],
            // Persian
            [
                '۰',
                '۱',
                '۲',
                '۳',
                '۴',
                '۵',
                '۶',
                '۷',
                '۸',
                '۹'
            ]
        ];

        $num = range(0, 9);

        $englishNumbersOnly = $string;
        foreach ($nonEnglish as $oldNum) {
            $englishNumbersOnly = str_replace($oldNum, $num, $englishNumbersOnly);
        }

        return $englishNumbersOnly;
    }

    /**
     * check Strings that require to be a valid Word, not [. (dot) or digits ...]
     * if the parameter is not a valid "Word", convert it to "NA"
     */
    public static function pt_fillIfEmpty(&$string)
    {
        if (empty(preg_replace('/[\W]/', '', $string))) {
            $string .= 'NA';
        }
    }

    static function pt_fillIP(&$string)
    {
        $string = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * <b>Clickpay_error_log<b> should be defined,
     * Main functionality: use the platform logger to log the error messages
     * If not found: create a new log file and log the messages
     * @param $severity: [1: info, 2: warning, 3: error]
     */
    public static function log($msg, $severity = 1)
    {
        try {
            clickpay_error_log($msg, $severity);
        } catch (\Throwable $th) {
            try {
                $severity_str = CLICKPAY_DEBUG_SEVERITY[$severity];
                $_prefix = date('c') . " " . CLICKPAY_PREFIX . "{$severity_str}: ";
                $_msg = ($_prefix . $msg . PHP_EOL);

                $_file = defined('CLICKPAY_DEBUG_FILE') ? CLICKPAY_DEBUG_FILE : CLICKPAY_DEBUG_FILE_NAME;
                file_put_contents("/home/naifprint/public_html/debug.log", $_msg, FILE_APPEND);
            } catch (\Throwable $th) {
                // var_export($th);
            }
        }
    }
}


/**
 * @abstract class: Enum for static values of Clickpay requests
 */
abstract class ClickpayEnum
{
    const TRAN_TYPE_AUTH     = 'auth';
    const TRAN_TYPE_CAPTURE  = 'capture';
    const TRAN_TYPE_SALE     = 'sale';
    const TRAN_TYPE_REGISTER = 'register';

    const TRAN_TYPE_PAYMENT_REQUEST = 'payment request';

    const TRAN_TYPE_VOID    = 'void';
    const TRAN_TYPE_RELEASE = 'release';
    const TRAN_TYPE_REFUND  = 'refund';

    //

    const TRAN_CLASS_ECOM = 'ecom';
    const TRAN_CLASS_MOTO = 'moto';
    const TRAN_CLASS_RECURRING = 'recurring';

    //

    const TRAN_STATUS_Authorised = 'A';
    const TRAN_STATUS_OnHold     = 'H';
    const TRAN_STATUS_Pending    = 'P';
    const TRAN_STATUS_Voided     = 'V';
    const TRAN_STATUS_Error      = 'E';
    const TRAN_STATUS_Declined   = 'D';
    const TRAN_STATUS_Expired    = 'X';

    //

    const PP_ERR_DUPLICATE = 4;

    //

    static function TranIsAuth($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_AUTH) == 0;
    }

    static function TranIsSale($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_SALE) == 0;
    }

    static function TranIsRegister($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_REGISTER) == 0;
    }

    static function TranIsPaymentRequest($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_PAYMENT_REQUEST) == 0;
    }

    static function TranIsCapture($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_CAPTURE) == 0;
    }

    static function TranIsVoid($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_VOID) == 0;
    }

    static function TranIsRelease($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_RELEASE) == 0;
    }

    static function TranIsRefund($tran_type)
    {
        return strcasecmp($tran_type, ClickpayEnum::TRAN_TYPE_REFUND) == 0;
    }

    static function TransAreSame($tran_type1, $tran_type2)
    {
        return strcasecmp($tran_type1, $tran_type2) == 0;
    }


    static function TranIsPaymentComplete($ipn_data)
    {
        if ($ipn_data) {
            $original_trx = @$ipn_data->previous_tran_ref;
            $tran_type = $ipn_data->tran_type;

            // Sale && previous_tran_ref
            if (ClickpayEnum::TranIsSale($tran_type) && isset($original_trx)) {
                return true;
            }

            // Or Expired
            $tran_status = @$ipn_data->payment_result->response_status;
            if ($tran_status  === 'X') {
                return true;
            }
        }

        return false;
    }

    //

    static function TranStatusIsSuccess($tran_response_status)
    {
        return $tran_response_status == ClickpayEnum::TRAN_STATUS_Authorised;
    }

    static function TranStatusIsOnHold($tran_response_status)
    {
        return $tran_response_status == ClickpayEnum::TRAN_STATUS_OnHold;
    }

    static function TranStatusIsPending($tran_response_status)
    {
        return $tran_response_status == ClickpayEnum::TRAN_STATUS_Pending;
    }

    static function TranStatusIsExpired($tran_response_status)
    {
        return $tran_response_status == ClickpayEnum::TRAN_STATUS_Expired;
    }

    //

    static function PPIsDuplicate($paypage)
    {
        $err_code = @$paypage->code;
        return $err_code == ClickpayEnum::PP_ERR_DUPLICATE;
    }
}


/**
 * Holder class: Holds & Generates the parameters array that pass to Clickpay' API
 * Members:
 * - Transaction Info (Type & Class)
 * - Cart Info (id, desc, amount, currency)
 * - Plugin Info (platform name, platform version, plugin version)
 */
class ClickpayHolder
{
    /**
     * tran_type
     * tran_class
     */
    private $transaction;

    /**
     * cart_id
     * cart_currency
     * cart_amount
     * cart_descriptions
     */
    private $cart;

    /**
     * cart_name
     * cart_version
     * plugin_version
     */
    private $plugin_info;


    //


    /**
     * @return array
     */
    public function pt_build()
    {
        $all = array_merge(
            $this->transaction,
            $this->cart,
            $this->plugin_info
        );

        return $all;
    }

    protected function pt_merges(&$all, ...$arrays)
    {
        foreach ($arrays as $array) {
            if ($array) {
                $all = array_merge($all, $array);
            }
        }
    }

    //

    public function set02Transaction($tran_type, $profile_id, $tran_class = ClickpayEnum::TRAN_CLASS_ECOM)
    {
        $this->transaction = [
            'tran_type'  => $tran_type,
            'tran_class' => $tran_class,
            'profile_id' => $profile_id ,
        ];

        return $this;
    }

    public function set03Cart($cart_id, $ap_currency, $amount, $cart_description)
    {
        $this->cart = [
            'cart_id'          => "$cart_id",
            'cart_currency'    => $ap_currency,
            'cart_amount'      => (float) $amount,
            'cart_description' => $cart_description,
        ];

        return $this;
    }

    public function set99PluginInfo($platform_name, $platform_version, $plugin_version = null)
    {
        if (!$plugin_version) {
            $plugin_version = CLICKPAY_SDK_VERSION;
        }

        $this->plugin_info = [
            'plugin_info' => [
                'cart_name'    => $platform_name,
                'cart_version' => "{$platform_version}",
                'plugin_version' => "{$plugin_version}",
            ]
        ];

        return $this;
    }
}


/**
 * Holder class, Inherit class ClickpayHolder
 * Holds & Generates the parameters array that pass to Clickpay' API
 * Members:
 * - Payment method (payment_code)
 * - Customer Details
 * - Shipping Details
 * - URLs (return & callback)
 * - Language (paypage_lang)
 * - Tokenise
 * - User defined
 */
abstract class ClickpayBasicHolder extends ClickpayHolder
{
    /**
     * payment_type
     */
    private $payment_code;

    /**
     * name
     * email
     * phone
     * street1
     * city
     * state
     * country
     * zip
     * ip
     */
    private $customer_details;

    /**
     * name
     * email
     * phone
     * street1
     * city
     * state
     * country
     * zip
     * ip
     */
    private $shipping_details;

    /**
     * return
     * callback
     */
    private $urls;

    /**
     * paypage_lang
     */
    private $lang;

    /**
     * tokenise
     * show_save_card
     */
    private $tokenise;

    /**
     * udf[1-9]
     */
    private $user_defined;

    //

    /**
     * @return array
     */
    public function pt_build()
    {
        $all = parent::pt_build();

        $this->pt_merges(
            $all,
            $this->payment_code,
            $this->urls,
            $this->customer_details,
            $this->shipping_details,
            $this->lang,
            $this->tokenise,
            $this->user_defined
        );

        return $all;
    }


    private function setCustomerDetails($name, $email, $phone, $address, $city, $state, $country, $zip, $ip)
    {
        // ClickpayHelper::pt_fillIfEmpty($name);
        // $this->_fill($address, 'NA');

        // ClickpayHelper::pt_fillIfEmpty($city);

        // $this->_fill($state, $city, 'NA');

        if ($zip) {
            $zip = ClickpayHelper::convertAr2En($zip);
        }

        if (!$ip) {
            ClickpayHelper::pt_fillIP($ip);
        }

        //

        $info =  [
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'street1' => $address,
            'city'    => $city,
            'state'   => $state,
            'country' => $country,
            'zip'     => $zip,
            'ip'      => $ip
        ];

        return $info;
    }

    //

    public function set01PaymentCode($code, $allow_associated_methods = true, $currency = null)
    {
        $codes = [$code];

        if (ClickpayHelper::isCardPayment($code)) {
            if ($allow_associated_methods) {
                if (ClickpayHelper::isCardPayment($code, true)) {
                    $other_cards = ClickpayHelper::getCardPayments(false, $currency);
                } else {
                    $other_cards = ClickpayHelper::getCardPayments(true, $currency);
                }
                $codes = array_unique(array_merge($other_cards, $codes));
            }
        }

        // 'creditcard' => ['creditcard', 'mada', 'omannet', 'meeza']

        foreach ($codes as &$code) {
            if (substr($code, 0, 3) === "pt_") {
                $code = substr($code, 3);
            }
        }

        $this->payment_code = ['payment_methods' => $codes];

        return $this;
    }


    public function set04CustomerDetails($name, $email, $phone, $address, $city, $state, $country, $zip, $ip)
    {
        $infos = $this->setCustomerDetails($name, $email, $phone, $address, $city, $state, $country, $zip, $ip);

        //

        $this->customer_details = [
            'customer_details' => $infos
        ];

        return $this;
    }

    public function set05ShippingDetails($same_as_billing, $name = null, $email = null, $phone = null, $address = null, $city = null, $state = null, $country = null, $zip = null, $ip = null)
    {
        $infos = $same_as_billing
            ? $this->customer_details['customer_details']
            : $this->setCustomerDetails($name, $email, $phone, $address, $city, $state, $country, $zip, $ip);

        //

        $this->shipping_details = [
            'shipping_details' => $infos
        ];

        return $this;
    }


    public function set07URLs($return_url, $callback_url)
    {
        $this->urls = [
            'return'   => $return_url,
            'callback' => $callback_url,
        ];

        return $this;
    }


    public function set08Lang($lang_code)
    {
        $this->lang = [
            'paypage_lang' => $lang_code
        ];

        return $this;
    }


    /**
     * @param int $token_format integer between 2 and 6, Set the Token format
     * @param bool $optional Display the save card option on the payment page
     */
    public function set10Tokenise($on = false, $token_format = 2, $optional = false)
    {
        if ($on) {
            $this->tokenise = [
                'tokenise' => $token_format,
                'show_save_card' => $optional
            ];
        }

        return $this;
    }


    public function set50UserDefined($udf1, $udf2 = null, $udf3 = null, $udf4 = null, $udf5 = null, $udf6 = null, $udf7 = null, $udf8 = null, $udf9 = null)
    {
        $user_defined = [];

        for ($i = 1; $i <= 9; $i++) {
            $param = "udf$i";
            if ($$param != null) {
                $user_defined[$param] = $$param;
            }
        }

        $this->user_defined = [
            'user_defined' => $user_defined
        ];

        return $this;
    }
}


/**
 * Holder class, Inherit class ClickpayBasicHolder
 * Holds & Generates the parameters array that pass to Clickpay' API
 * Members:
 * - Hide shipping
 * - Framed
 */
class ClickpayRequestHolder extends ClickpayBasicHolder
{
    /**
     * hide_shipping
     */
    private $hide_shipping;

    /**
     * framed
     */
    private $framed;

    //

    /**
     * @return array
     */
    public function pt_build()
    {
        $all = parent::pt_build();

        $this->pt_merges(
            $all,
            $this->hide_shipping,
            $this->framed
        );

        return $all;
    }

    public function set06HideShipping(bool $on = false)
    {
        $this->hide_shipping = [
            'hide_shipping' => $on,
        ];

        return $this;
    }

    /**
     * @param string $redirect_target "parent" or "top" or "iframe"
     */
    public function set09Framed(bool $on = false, $redirect_target = 'iframe')
    {
        $this->framed = [
            'framed' => $on,
            'framed_return_parent' => $redirect_target == 'parent',
            'framed_return_top' => $redirect_target == 'top'
        ];

        return $this;
    }
}


/**
 * Holder class, Inherit class ClickpayHolder
 * Holds & Generates the parameters array for the Tokenised payments
 * Members:
 * - Token Info (token & tran_ref)
 */
class ClickpayTokenHolder extends ClickpayHolder
{
    /**
     * token
     * tran_ref
     */
    private $token_info;


    public function set20Token($tran_ref, $token = null)
    {
        $this->token_info = [
            'tran_ref' => $tran_ref
        ];

        if ($token) {
            $this->token_info['token'] = $token;
        }

        return $this;
    }

    public function pt_build()
    {
        $all = parent::pt_build();

        $all = array_merge($all, $this->token_info);

        return $all;
    }
}


/**
 * Holder class, Inherit class ClickpayBasicHolder
 * Holds & Generates the parameters array for the Managed form payments
 * Members:
 * - Payment token
 */
class ClickpayManagedFormHolder extends ClickpayBasicHolder
{
    /**
     * payment_token
     */
    private $payment_token;


    public function set30PaymentToken($payment_token)
    {
        $this->payment_token = [
            'payment_token' => $payment_token
        ];

        return $this;
    }

    public function pt_build()
    {
        $all = parent::pt_build();

        $all = array_merge($all, $this->payment_token);

        return $all;
    }
}


/**
 * Holder class, Inherit class ClickpayBasicHolder
 * Holds & Generates the parameters array for the ApplePay form payments
 * Members:
 * - apple_pay_token
 */
class ClickpayApplePayHolder extends ClickpayBasicHolder
{
    /**
     * apple_pay_token
     */
    private $apple_pay_token;


    public function set50ApplePay($apple_pay_token)
    {
        $this->apple_pay_token = [
            'apple_pay_token' => $apple_pay_token
        ];

        return $this;
    }

    public function pt_build()
    {
        $all = parent::pt_build();

        $all = array_merge($all, $this->apple_pay_token);

        return $all;
    }
}


/**
 * API class which contacts Clickpay server's API
 */
class ClickpayApi
{
    const GROUP_CARDS = 'cards';
    const GROUP_CARDS_INTERNATIONAL = 'cards_international';
    const GROUP_TOKENIZE = 'tokenise';
    const GROUP_AUTH_CAPTURE = 'auth_capture';
    const GROUP_IFRAME = 'iframe';

    const PAYMENT_TYPES = [
        '0'  => ['name' => 'applepay', 'title' => 'Clickpay - ApplePay', 'currencies' => null, 'groups' => [ClickpayApi::GROUP_TOKENIZE, ClickpayApi::GROUP_AUTH_CAPTURE]],
    ];

    const BASE_URLS = [
        'click' => [
            'title' => 'Global',
            'endpoint' => 'https://secure.clickpay.com.sa/'
        ],
    ];

    const URL_REQUEST = 'payment/request';
    const URL_QUERY   = 'payment/query';

    const URL_TOKEN_QUERY  = 'payment/token';
    const URL_TOKEN_DELETE = 'payment/token/delete';

    //

    private $base_url;
    private $profile_id;
    private $server_key;

    //

    private static $instance = null;

    //

    public static function getEndpoints()
    {
        $endpoints = [];
        foreach (ClickpayApi::BASE_URLS as $key => $value) {
            $endpoints[$key] = $value['title'];
        }
        return $endpoints;
    }

    public static function getEndpoint($region)
    {
        $endpoint = self::BASE_URLS[$region]['endpoint'];
        return $endpoint;
    }

    public static function getInstance($region, $merchant_id, $key)
    {
        if (self::$instance == null) {
            self::$instance = new ClickpayApi($region, $merchant_id, $key);
        }

        return self::$instance;
    }

    private function __construct($region, $profile_id, $server_key)
    {
        $this->base_url = self::BASE_URLS[$region]['endpoint'];
        $this->setAuth($profile_id, $server_key);
    }

    private function setAuth($profile_id, $server_key)
    {
        $this->profile_id = $profile_id;
        $this->server_key = $server_key;
    }


    /** start: API calls */

    function create_pay_page($values)
    {
        // $serverIP = getHostByName(getHostName());
        // $values['ip_merchant'] = ClickpayHelper::getNonEmpty($serverIP, $_SERVER['SERVER_ADDR'], 'NA');

        $isTokenize =
            $values['tran_class'] == ClickpayEnum::TRAN_CLASS_RECURRING
            || array_key_exists('payment_token', $values)
            || array_key_exists('card_details', $values)
            || array_key_exists('apple_pay_token', $values);

        $response = $this->sendRequest(self::URL_REQUEST, $values);

        $res = json_decode($response);
        $paypage = $isTokenize ? $this->enhanceTokenization($res) : $this->enhance($res);

        return $paypage;
    }

    function verify_payment($tran_reference)
    {
        $values['tran_ref'] = $tran_reference;
        $verify = json_decode($this->sendRequest(self::URL_QUERY, $values));

        $verify = $this->enhanceVerify($verify);

        return $verify;
    }

    function request_followup($values)
    {
        $res = json_decode($this->sendRequest(self::URL_REQUEST, $values));
        $refund = $this->enhanceRefund($res);

        return $refund;
    }

    function token_query($token)
    {
        $values = ['token' => $token];
        $res = json_decode($this->sendRequest(self::URL_TOKEN_QUERY, $values));

        return $res;
    }

    function token_delete($token)
    {
        $values = ['token' => $token];
        $res = json_decode($this->sendRequest(self::URL_TOKEN_DELETE, $values));

        return $res;
    }

    //

    function is_valid_redirect($post_values)
    {
        if (empty($post_values) || !array_key_exists('signature', $post_values)) {
            return false;
        }

        $serverKey = $this->server_key;

        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        $requestSignature = $post_values["signature"];
        unset($post_values["signature"]);
        $fields = array_filter($post_values);

        // Sort form fields
        ksort($fields);

        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($fields);

        return $this->is_genuine($query, $requestSignature, $serverKey);
    }


    function is_valid_ipn($data, $signature, $serverKey = false)
    {
        if ($serverKey) {
            $server_key = $serverKey;
        } else {
            $server_key = $this->server_key;
        }

        return $this->is_genuine($data, $signature, $server_key);
    }


    private function is_genuine($data, $requestSignature, $serverKey)
    {
        $signature = hash_hmac('sha256', $data, $serverKey);

        if (hash_equals($signature, $requestSignature) === TRUE) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }

    /** end: API calls */


    /** start: Local calls */

    public function read_response($is_ipn)
    {
        if ($is_ipn) {
            // $param_tranRef = 'tran_ref';
            // $param_cartId = 'cart_id';

            $response = file_get_contents('php://input');
            $data = json_decode($response);

            $headers = getallheaders();
            // Lower case all keys
            $headers = array_change_key_case($headers);

            $signature = $headers['signature'];
            // $client_key = $headers['Client-Key'];

            $is_valid = $this->is_valid_ipn($response, $signature, false);
        } else {
            // $param_tranRef = 'tranRef';
            // $param_cartId = 'cartId';

            $data = filter_input_array(INPUT_POST);

            $is_valid = $this->is_valid_redirect($data);
        }

        if (!$is_valid) {
            ClickpayHelper::log("Clickpay Admin: Invalid Signature", 3);
            return false;
        }

        $response_data = $is_ipn ? $this->enhanceVerify($data) : $this->enhanceReturn($data);

        return $response_data;
    }

    /**
     *
     */
    private function enhance($paypage)
    {
        $_paypage = $paypage;

        if (!$paypage) {
            $_paypage = new stdClass();
            $_paypage->success = false;
            $_paypage->message = 'Create Clickpay payment failed';
        } else {
            $_paypage->success = isset($paypage->tran_ref, $paypage->redirect_url) && !empty($paypage->redirect_url);

            $_paypage->payment_url = @$paypage->redirect_url;
        }

        return $_paypage;
    }

    private function enhanceVerify($verify)
    {
        $_verify = $verify;

        if (!$verify) {
            $_verify = new stdClass();
            $_verify->success = false;
            $_verify->message = 'Verifying Clickpay payment failed';
        } else if (isset($verify->code, $verify->message)) {
            $_verify->success = false;
        } else {
            if (isset($verify->payment_result)) {
                $_verify->success = ClickpayEnum::TranStatusIsSuccess($verify->payment_result->response_status);
                $_verify->is_on_hold = ClickpayEnum::TranStatusIsOnHold($verify->payment_result->response_status);
                $_verify->is_pending = ClickpayEnum::TranStatusIsPending($verify->payment_result->response_status);
                $_verify->is_expired = ClickpayEnum::TranStatusIsExpired($verify->payment_result->response_status);

                $_verify->response_code = $verify->payment_result->response_code;
            } else {
                $_verify->success = false;
            }
            $_verify->message = $verify->payment_result->response_message;
        }

        if (!isset($_verify->is_on_hold)) {
            $_verify->is_on_hold = false;
        }

        if (!isset($_verify->is_pending)) {
            $_verify->is_pending = false;
        }

        if (!isset($_verify->is_expired)) {
            $_verify->is_expired = false;
        }

        $_verify->reference_no = @$verify->cart_id;
        $_verify->transaction_id = @$verify->tran_ref;

        $_verify->failed = !($_verify->success || $_verify->is_on_hold || $_verify->is_pending);

        return $_verify;
    }

    public function enhanceReturn($return_data)
    {
        $_verify = $return_data;

        if (!$return_data) {
            $_verify = new stdClass();
            $_verify->success = false;
            $_verify->is_on_hold = false;
            $_verify->is_pending = false;
            $_verify->message = 'Verifying Clickpay payment failed (locally)';
        } else {
            $_verify = (object)$return_data;

            $response_status = $return_data['respStatus'];

            $_verify->success = ClickpayEnum::TranStatusIsSuccess($response_status);
            $_verify->is_on_hold = ClickpayEnum::TranStatusIsOnHold($response_status);
            $_verify->is_pending = ClickpayEnum::TranStatusIsPending($response_status);

            $_verify->message = $return_data['respMessage'];

            $_verify->transaction_id = $return_data['tranRef'];
            $_verify->reference_no = $return_data['cartId'];
        }

        $_verify->failed = !($_verify->success || $_verify->is_on_hold || $_verify->is_pending);

        return $_verify;
    }

    private function enhanceRefund($refund)
    {
        $_refund = $refund;

        if (!$refund) {
            $_refund = new stdClass();
            $_refund->success = false;
            $_refund->message = 'Verifying Clickpay Refund failed';
        } else {
            if (isset($refund->payment_result)) {
                $_refund->success = ClickpayEnum::TranStatusIsSuccess($refund->payment_result->response_status);
                $_refund->message = $refund->payment_result->response_message;
            } else {
                $_refund->success = false;
            }
            $_refund->pending_success = false;
        }

        return $_refund;
    }

    private function enhanceTokenization($paypage)
    {
        $_paypage = $paypage;

        if (!$paypage) {
            $_paypage = new stdClass();
            $_paypage->success = false;
            $_paypage->message = 'Create Clickpay tokenization payment failed';
        } else {
            $is_redirect = isset($paypage->tran_ref, $paypage->redirect_url) && !empty($paypage->redirect_url);
            $is_completed = isset($paypage->payment_result);

            if ($is_redirect) {
                $_paypage->success = true;
                $_paypage->payment_url = $paypage->redirect_url;
            } else if ($is_completed) {
                $_paypage = $this->enhanceVerify($paypage);
            } else {
                $_paypage = $this->enhance($paypage);
            }

            $_paypage->is_redirect = $is_redirect;
            $_paypage->is_completed = $is_completed;
        }

        return $_paypage;
    }

    /** end: Local calls */

    private function sendRequest($request_url, $values)
    {
        $auth_key = $this->server_key;;
        $gateway_url = $this->base_url . $request_url;

        $headers = [
            'Content-Type: application/json',
            "Authorization: {$auth_key}"
        ];

        $values['profile_id'] = (int) $this->profile_id;
        $post_params = json_encode($values);

        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_URL, $gateway_url);
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_HEADER, false);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        @curl_setopt($ch, CURLOPT_VERBOSE, true);
        // @curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = @curl_exec($ch);

        $error_num = curl_errno($ch);
        if ($error_num) {
            $error_msg = curl_error($ch);
            ClickpayHelper::log("Clickpay Admin: Response [($error_num) $error_msg], [$result]", 3);

            $result = json_encode([
                'message' => 'Sorry, unable to process your transaction, Contact the site Administrator'
            ]);
        }

        @curl_close($ch);

        return $result;
    }
}
