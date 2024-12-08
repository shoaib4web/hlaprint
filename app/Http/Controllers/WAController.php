<?php

namespace App\Http\Controllers;

use Howtomakeaturn\PDFInfo\PDFInfo;

use App\Models\PrintJob;

use App\Models\UserCodes;

use App\Models\WhatsAppModel;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendWhatsAppReply;
use App\Jobs\CancelReplyJob;
use Illuminate\Support\Facades\Log;

// dev code

use App\Models\WhatsAppRequest;

use App\Models\PrintJobsModel;

use App\Models\Transaction;

use App\Models\Invoice;

use App\Models\WhatsAppTextMessagesModel;

use App\Models\PdfFilesModel;

use Illuminate\Http\Request;

use File;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WAController extends Controller
{
    public $data = [];

    public $allowed_Files = ['pdf','ppt','pptx','doc','docx','xlsx','csv','ods','jpeg','jpg','png', 'xls'];

    public $token = "EAAlAigYje8QBO59eGxviZCxydg1ulQ3T6gaZCfQlkdOBRZCxLw9NRbcKCcjAZCTVx9Wr57IwSPkYAnkXTrER0FVUZASotyzcZCld0wSFqqL5gT1cZAXjGN9uEsmPXeQRVQYqXgUWwRfS3YLQCvttTJG2fjDgsOZAedzhSNSKNiZAOoM3hvrd7xbAuOus1McjkpGwZA";

    public function index(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //Storage::disk('local')->put('example.txt', $data);

        // Storage::disk('public')->put('images.json', $request);

        return $request["hub_challenge"];
    }

    public function clear_debug()
    {
        $res = [
            "status" => "success",
        ];

        return response()->json($res);
    }

    public function checkPhoneNumberId($id)
    {
        return $id == '107831068970298';
    }

    public function newReply($contactId)
    {

        $existingJob = Cache::get('reply_job_for_' . $contactId);
       // $totalmsg = Cache::put('total_messages_by'.$contactId, $index,60);


    if ($existingJob) {
        // Reset the timer by deleting the existing job
        dispatch(new CancelReplyJob($existingJob));
    }

    // Dispatch a new job with a delay of 3 seconds
    $job = (new SendWhatsAppReply($contactId, $this->data['phone_id']));
    dispatch($job)->delay(now()->addSeconds(3));


    // Store the job identifier in the cache
  // Cache::put('reply_job_for_' . $contactId, $job->job->getJobId(), 10);
    Cache::put('new_message_for' . $contactId, true,10);

    }

    public function getShopID($waID)
    {
        $shop = DB::table('whatsapp_shops')->where('WA_id',$waID)->first();

        if($shop)
        {
            return $shop->shop_id;
        }
        else
        {
            return 7;
        }

    }

    public function sendCodeLink($code)
    {

        $shop_id = $this->getShopId($this->data['phone_id']);

        Storage::disk("local")->put("SendCodeLInk.txt", "haha :".$this->data["phone"]);
      $message = [
            "messaging_product" => "whatsapp",

            "recipient_type" => "individual",

            "to" => $this->data["phone"],

            "type" => "text",

            "text" => [
                "preview_url" => true,

                "body" =>
                    "يرجى اتباع هذا الرابط للدفع وخيارات الطباعة\n\n Please Follow this link for payment and Printing Options. \n\n " .
                    url("/") .
                    "/arabicView/" .
                    $code."/$shop_id",
            ],
        ];

         try {
                $response = Http::withToken($this->token)->POST(
                    "https://graph.facebook.com/v16.0/".$this->data['phone_id']."/messages/",

                    $message
                );

                 // Get the body of the response as a JSON string
    $jsonResponse = $response->body();

    // Store the JSON response in a file
    Storage::disk('local')->put('LinkError.json', $jsonResponse);


            } catch (\Exception $e) {
                $error = $e->getMessage();

                Storage::disk("local")->put("LinkError.txt", $e);
            }
    }

    public function interactiveResponse()
    {
        $buttonID = $this->data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['id'];



        if($buttonID == 'no')
        {
            $code = Cache::get('user_code_'.$this->data['phone']);
            if($code)
            {
                Cache::forget('user_code_'.$this->data['phone']);
                $this->sendCodeLink($code);
            }


        }
        else {
            return;
        }

    }

    public function indexPost(Request $request)
    {
        $this->data = $request->all();

        $debug_value = print_r($this->data, true);


        Storage::disk("local")->put("khttki_intial_debug.txt", $debug_value);


        $this->data['phone_id'] = $this->data['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];

        Log::info("New Message Recieved for ".$this->data['phone_id']);

        // if(!true)//$this->checkPhoneNumberId( $this->data['phone_id']))
        // {
        //     $debug_value = "Rejected : $this->data['phone_id']";
        //     Log::info("Rejected : $this->data['phone_id']");


        // Storage::disk("local")->put("khttki_reject_debug.txt", $debug_value);
        //     $res = ["status" => "200"];

        //         return response()->json($res);
        //         exit;
        // }





        try {
            //dev code to get waid

            $this->data["msg_id"] =
                $this->data["entry"][0]["changes"][0]["value"]["messages"][0][
                    "id"
                ];

            $this->data["WA_number"] =
                $this->data["entry"][0]["changes"][0]["value"]["contacts"][0][
                    "wa_id"
                ];

             Cache::put('last_message_timestamp_' . $this->data['WA_number'], now()->timestamp, 10);

            $checkMsgId = WhatsAppRequest::where(
                "WA_message_id",
                $this->data["msg_id"]
            )->first();

            if ($checkMsgId) {
                $res = ["status" => "200"];

                return response()->json($res);
            }

            $record = new WhatsAppRequest();

            $record->WA_message_id = $this->data["msg_id"];

            $record->WA_number = $this->data["WA_number"];

            $record->WA_timestamp = date("Y-m-d H:i:s");

            $record->save();
        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("requestError.txt", $error);
        }

        if (
            !isset(
                $this->data["entry"][0]["changes"][0]["value"]["messages"][0][
                    "type"
                ]
            )
        ) {
            $res = [
                "status" => "200",
            ];

            return response()->json($res);
        }

        $this->data["file_type"] =
            $this->data["entry"][0]["changes"][0]["value"]["messages"][0][
                "type"
            ];

        $this->data["phone"] = explode(
            "/",

            $this->data["entry"][0]["changes"][0]["value"]["contacts"][0][
                "wa_id"
            ]
        )[0];



        if ($this->data["file_type"] == "text") {
            $this->data["message"] =
                $this->data["entry"][0]["changes"][0]["value"]["messages"][0][
                    "text"
                ]["body"];

            $this->save_text_message_to_database(); // <------------------------------------------------------ TEXT MESSAGE -> DATABASE

            $res = [
                "status" => "200",
            ];

            return response()->json($res);
        } elseif($this->data["file_type"] == "interactive") {

            $this->interactiveResponse();
        }

        else{
            $printRequest = new PrintRequestsController();
            if(!$printRequest->onlineCheck($this->getShopID($this->data['phone_id'])))//$printRequest->onlineCheck(7))

            {

                $this->sorry_reply();

                $res = [

                    "status" => "200",

                ];

                return response()->json($res);

            }

            $this->newReply($this->data['WA_number']);


//


            $id =
                $this->data["entry"][0]["changes"][0]["value"]["messages"][0][
                    $this->data["file_type"]
                ]["id"];

            if (
                isset(
                    $this->data["entry"][0]["changes"][0]["value"][
                        "messages"
                    ][0][$this->data["file_type"]]["caption"]
                )
            ) {
                $this->data["message"] =
                    $this->data["entry"][0]["changes"][0]["value"][
                        "messages"
                    ][0][$this->data["file_type"]]["caption"];
            } else {
                $this->data["message"] = "No Caption Provided";
            }

            $this->get_image($id); // <------------------------------------------------------ GET IMAGE

            $res = [
                "status" => "200",
            ];

            return response()->json($res);
        }
    }

    public function unsupported_response()
    {
        $message = [
            "messaging_product" => "whatsapp",

            "recipient_type" => "individual",

            "to" => $this->data["phone"],

            "type" => "text",


            "text" => [


                "body" =>
                    "عذرًا، لا يدعم Hla Print تنسيق الملف الخاص بك \n\n Sorry, Hla Print doesnt support your file format. \n\n ",

            ],
        ];


        try {
            $response = Http::withToken($this->token)->POST(
                "https://graph.facebook.com/v16.0/".$this->data['phone_id']."/messages/",

                $message
            );


        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("Unsuppirted.txt", $e);
        }
    }

    public function getWAIDbyShop($id)
    {
        $shop = DB::table('whatsapp_shops')->where('shop_id',$id)->first();

        if($shop)
        {
            return $shop->WA_id;
        }
        else
        {
            return 7;
        }
    }


public function sendInvoice($invoiceNumber, $phone, $shop_id = 7){
    $arrinvoiceNumber = " ".$invoiceNumber;
    $phoneId = $this->getWAIDbyShop($shop_id);

    $arr_text = " "."  شكرا ، با.مكانك استلام مطبوعاتك برقم الفاتورة".$arrinvoiceNumber;
    $english_text = "Thank you , you can collect your prints by invoice number ".$invoiceNumber;
    $message = [
        "messaging_product" => "whatsapp",

        "recipient_type" => "individual",

        "to" => $phone,

        "type" => "text",


        "text" => [


            "body" =>
                $arr_text."\n\n".$english_text,

        ],
    ];


    try {
        $response = Http::withToken($this->token)->POST(
            "https://graph.facebook.com/v16.0/".$phoneId."/messages/",

            $message
        );
        Storage::disk("local")->put("InvoiceSend.txt", $response);
        Log::info("SENT MESSAGE RESPONSE :".$response);


    } catch (\Exception $e) {
        $error = $e->getMessage();

        Storage::disk("local")->put("InvoiceSend.txt", $e);
        Log::info("Error in Message :".$e);
    }
}
    public function welcome_response()
    {
        $message = [
            "messaging_product" => "whatsapp",

            "recipient_type" => "individual",

            "to" => $this->data["phone"],

            "type" => "text",


            "text" => [


                "body" =>
                    "شكرًا لك! لقد استلمنا ملفك، وسوف تتلقى رابطًا يتضمن خيارات الطباعة قريبًا.\n\n Thank  you! we have recieved your file, you will recieve a link with printing options shortly. \n\n ",

            ],
        ];


        try {
            $response = Http::withToken($this->token)->POST(
                "https://graph.facebook.com/v16.0/".$this->data['phone_id']."/messages/",

                $message
            );


        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("Welcomerror.txt", $e);
        }
    }

    private function getToken($appSid, $appKey)
    {
        $url = "https://api.aspose.cloud/connect/token";

        $postData = [
            "grant_type" => "client_credentials",

            "client_id" => $appSid,

            "client_secret" => $appKey,
        ];

        $options = [
            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => http_build_query($postData),

            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded",

                "Accept: application/json",
            ],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        curl_close($curl);

        $tokenData = json_decode($response, true);

        return $tokenData["access_token"];
    }

    public function save_print_job()
    {
        // echo 'here';exit;

        $PrintJobIds = [];

        $model = new PrintJob();

        $model->phone = $this->data["phone"];
        $model->shop_id = $this->getShopID($this->data['phone_id']);

        $model->filename = $this->data["filename"];

        $model->code = $this->data["user_code"];

        $model->color = false;

        $model->double_sided = false;

        $model->pages_start = 0;

        $model->page_end = 0;

        $model->status = "Queued";



        // $model->save();

        $total_amount = 0;

        $res = $model->save();



        if (Str::endsWith($this->data['filename'], ['.jpeg', '.png', '.jpg'])) {
            $file = asset(
                "storage/app/public/WhatsApp_Files/" .
                    $this->data["phone"] .
                    "/" .
                    $this->data["filename"]
            );

            $insert = DB::table('imgs_files')->insert([
                'printjob_id' => $model->id,
                'filepath' => $file
            ]);

        }else {
            $pdfFile = new PdfFilesModel();

            $pdfFile->phone = $this->data['phone'];
            $pdfFile->fileName = $this->data['og_filename'];
            $pdfFile->fileLocation = "Dummy";
            $pdfFile->status = 0;
            $pdfFile->printjob_id = $model->id;
            $pdfFile->save();
        }

        $file = asset(
            "public/storage/WhatsApp_Files/" .
                $this->data["phone"] .
                "/" .
                $this->data["filename"]
        );

        $debug_value = print_r($file, true);

        Storage::disk("local")->put("khttki_getting_file_from_Storage_debug.txt", $debug_value);
        Log::info("Starting Converstion in WA Controller");

        $convert_request = new ConvertController();

        $name = $convert_request->convert(
            $file,

            $this->data["phone"],

            $this->data["user_code"]
        );

        $fileNameParts = explode(".", $name);
        Log::info("Converted FILE : ".$name);
        $exactFileName = $fileNameParts[1];
        $exactFileName = explode("/", $exactFileName);
        $exactFileName = end($exactFileName);

        $pdfNEW = public_path("storage/WhatsApp_Files/".$this->data["phone"]."/".$exactFileName.".pdf");
        Log::info("NEW FILE for Page Counting : ".$pdfNEW);

        $khttki_debug = print_r($pdfNEW, true);

        Storage::disk("local")->put("khttki_pages_debug.txt", $khttki_debug);

        $pdf = new PDFInfo($pdfNEW);

        $pageNumber = $pdf->pages;

        Log::info("pages : ".$pdf->pages);



        $total_pages = $pageNumber;

        $model->total_pages = $total_pages;

        $model->filename = $name;

        $model->save();

        array_push($PrintJobIds, $model->id);

        // if ($res) {

        //     // dev code to save transaction pending

        //     $transaction = new Transaction();

        //     $transaction->print_job_id = json_encode($PrintJobIds);

        //     $transaction->invoice_id = 0;

        //     $transaction->amount = $total_amount;

        //     $transaction->currency = "SAR";

        //     $transaction->type = "cash";

        //     $transaction->status = 'Queued';

        //     $transaction->date = date("Y-m-d H:i:s");

        //     $transaction->created_at = date("Y-m-d H:i:s");

        //     Storage::disk("local")->put("transaction_record_debug.txt",  $transaction);

        //     $transaction->save();

        //     $this->updateTransaction(json_encode($model->id), $total_amount);

        // dev code to save transaction pending

        // }
    }

    public function updateTransaction($print_job_id, $total_amount)
    {
        $transactions = Transaction::where(
            "print_job_id",

            $print_job_id
        )->get();

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

                    $trans->save();
                } else {
                    return back()->with("error", "No invoice generated");
                }
            }
        }
    }

    public function handleInteractive()
    {
        $temp = explode("_", $this->data["message"]);

        $debug_value = print_r($temp, true);

        Storage::disk("local")->put("khttki_handler_debug.txt", $debug_value);

        $action = $temp[1];

        $code = $temp[0];

        switch ($action) {
            case "color":
                $model = PrintJobsModel::where(
                    "phone",

                    "=",

                    $this->data["phone"]
                )

                    ->where("code", "=", $code)

                    ->first();

                $model->color = true;

                $model->save();

                $this->interactiveFlow("sides", $code);

                break;

            case "double-sided":
                $model = PrintJobsModel::where(
                    "phone",

                    "=",

                    $this->data["phone"]
                )

                    ->where("code", "=", $code)

                    ->first();

                $model->double_sided = true;

                $model->save();

                $this->interactiveFlow("payment", $code);

                break;

            case "single-sided":
                $this->interactiveFlow("payment", $code);

                break;

            case "bw":
                $this->interactiveFlow("sides", $code);

                break;
        }
    }

    public function interactiveFlow($template, $code)
    {
        switch ($template) {
            case "sides":
                $message = [
                    "messaging_product" => "whatsapp",

                    "recipient_type" => "individual",

                    "to" => $this->data["phone"],

                    "type" => "interactive",

                    "interactive" => [
                        "type" => "button",

                        "header" => [
                            "type" => "text",

                            "text" => "Select Sides to Print",
                        ],

                        "body" => [
                            "text" => "Please Select the Sides to Print",
                        ],

                        "footer" => [
                            "text" => "Your current code is " . $code,
                        ],

                        "action" => [
                            "buttons" => [
                                [
                                    "type" => "reply",

                                    "reply" => [
                                        "id" => $code . "_single-sided",

                                        "title" => "Single Sided Print",
                                    ],
                                ],

                                [
                                    "type" => "reply",

                                    "reply" => [
                                        "id" => $code . "_double-sided",

                                        "title" => "Double Sided Print",
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];

                break;

            case "payment":
                $message = [
                    "messaging_product" => "whatsapp",

                    "recipient_type" => "individual",

                    "to" => $this->data["phone"],

                    "type" => "text",

                    "text" => [
                        "preview_url" => false,

                        "body" =>
                            "Please visit link below for selecting number of pages and checkout\n\n " .
                            url("/") .
                            "/arabicView/" .
                            $code."/7",
                    ],
                ];
        }

        $response = Http::withToken($this->token)->POST(
            "https://graph.facebook.com/v16.0/".$this->data['phone_id']."/messages/",

            $message
        );

        $debug_value = print_r($response->json(), true);

        Storage::disk("local")->put(
            "khttki_2nd_message_debug.txt",

            $debug_value
        );
    }

    public function get_image($id)
    {
        $response = Http::withToken($this->token)->get(
            "https://graph.facebook.com/v16.0/" . $id . "/"
        );

        $debug_value = print_r($response, true);

        Storage::disk("local")->put("khttki_getImage_debug.txt", $debug_value);

        // $this->data['file_size'] = $response['file_size'];

        if(!$this->save_file($response["url"]))
        {
            $this->unsupported_response();
            return;
        }
    }

    public function save_file($url)
    {
        $this->data["ext"] = explode(
            "/",

            $this->data["entry"][0]["changes"][0]["value"]["messages"][0][
                $this->data["file_type"]
            ]["mime_type"]
        )[1];




        if(!in_array($this->data["ext"], $this->allowed_Files) && $this->data["file_type"] == 'document')
        {


            $filename = explode(
                "/",

                $this->data["entry"][0]["changes"][0]["value"][
                    "messages"
                ][0]["document"]["filename"]
            )[0];




            $pattern = '/\.[^.]+$/';

             if(preg_match($pattern, $filename, $matches))
             {
                $this->data["ext"] = str_replace('.', '', $matches[0]);
             }


        }

        if(in_array($this->data["ext"], $this->allowed_Files)){//
            //$this->welcome_response();
            //$this->sendInvoice();
        }
        else {
            return false;
        }

        try {
            $this->data["dir"] = "WhatsApp_Files/" . $this->data["phone"];

            //Storing File in Storage/app/

            $file = Http::withToken($this->token)->get($url);



            $now = \DateTime::createFromFormat("U.u", microtime(true));

            if ($this->data["file_type"] == "document") {
                // $this->data["filename"] = explode(
                //     "/",

                //     $this->data["entry"][0]["changes"][0]["value"][
                //         "messages"
                //     ][0]["document"]["filename"]
                // )[0];

                // $this->data["filename"] = preg_replace(
                //     '/\.(?![^.]+$)/',
                //     "_",
                //     $this->data["filename"]
                // );

                // $this->data["filename"] = str_replace(
                //     " ",
                //     "_",
                //     $this->data["filename"]
                // );

                // if (strlen($this->data["filename"]) > 20) {
                //     $this->data["filename"] = substr(
                //         $this->data["filename"],
                //         -20
                //     ); // Cut from the start to keep the last 50 characters
                // }

                //$this->data['filename'] = urlencode($this->data['filename']);

                $this->data['og_filename'] = explode(
                    "/",

                    $this->data["entry"][0]["changes"][0]["value"][
                        "messages"
                    ][0]["document"]["filename"]
                )[0];

                $this->data["filename"] =
                    $this->data["phone"] .
                    "_" .
                    $now->format("H_i_s_u") .
                    "_file." .
                    $this->data["ext"];
            } else {
                $this->data["filename"] =
                    $this->data["phone"] .
                    "_" .
                    $now->format("H_i_s_u") .
                    "." .
                    $this->data["ext"];
            }



            $debug_value = print_r($this->data['filename'], true);
            Log::info("File saved as ".$this->data['filename']);

        Storage::disk("local")->put("khttki_filename_debug.txt", $debug_value);

            Storage::disk("public")->put(
                $this->data["dir"] . "/" . $this->data["filename"],

                $file
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("error.txt", $error);
        }

        $this->reply_message();
        return true; // <------------------------------------------------------ REPLY MESSAGE
    }

    public function save_file_record_to_database()
    {
        // echo $this->data['filename'];exit;

        try {
            $record = new WhatsAppModel();

            $record->message = $this->data["message"];

            $record->phone = $this->data["phone"];

            $record->file_type = $this->data["file_type"];

            $record->file_name = $this->data["filename"];

            $record->file_path = $this->data["dir"];

            // $record->file_size = $this->data['file_size'];

            $record->file_size = 0;

            $record->user_code = $this->data["user_code"];

            $record->save();
        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("error.txt", $error);
        }
    }

    public function save_text_message_to_database()
    {
        try {
            $record = new WhatsAppTextMessagesModel();

            $record->phone = $this->data["phone"];

            $record->message = $this->data["message"];

            $record->save();
        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("error.txt", $error);
        }

        $message = [
            "messaging_product" => "whatsapp",

            "recipient_type" => "individual",

            "to" => $this->data["phone"],

            "type" => "text",

            "text" => [
                "preview_url" => false,

                "body" =>
                    "شكرًا لك على رسالتك ، ولكننا نتوقع ملف مرفق ترغب في طباعته. يرجى مشاركة الملف. \n\nThank you for your message, however we are expecting a file attachment that you would like to print. Please share the file.",
            ],
        ];

        try {
            $response = Http::withToken($this->token)->POST(
                "https://graph.facebook.com/v16.0/".$this->data['phone_id']."/messages/",

                $message
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("error.txt", $e);
        }
    }

    public function sorry_reply()
    {
        $message = [
            "messaging_product" => "whatsapp",

            "recipient_type" => "individual",

            "to" => $this->data["phone"],

            "type" => "text",

            "text" => [
                "preview_url" => true,

                "body" => "نأسف لأن نظامنا يبدو غير متصل بالإنترنت في الوقت الحالي.



                    \n\n We are sorry our system appear offline for now. \n\n ",
            ],
        ];

        try {
            $response = Http::withToken($this->token)->POST(
                "https://graph.facebook.com/v16.0/".$this->data['phone_id']."/messages/",

                $message
            );

            $debug_value = print_r($response->json(), true);

            Storage::disk("local")->put(
                "khttki_sorry_message_debug.txt",

                $debug_value
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();

            Storage::disk("local")->put("error.txt", $e);
        }
    }

    public function reply_message()
    {
        //تم استلام طلبك وهنا رمزك ( $code ) للمضي قدمًا. من فضلك لا تشارك هذا الرم     ز مع أي شخص.\n\n

        if(Cache::has('user_code_'.$this->data['phone']))
        {
            $code = Cache::get('user_code_'.$this->data['phone']);
        }

        else {
            do {
                $code = rand(1000, 9999);
                $codeExists = UserCodes::where('code', $code)->exists();
            } while ($codeExists);

            Cache::put('user_code_'.$this->data['phone'],$code,600);
        }


        $this->data["user_code"] = $code;






        $uc = new UserCodes();

        $uc->phone = $this->data["phone"];

        $uc->code = $this->data["user_code"];

        $uc->status = false;

        $uc->expiry = date("Y-m-d", strtotime(" +1 day"));

        $uc->save();

        $this->save_print_job();


    }
}
