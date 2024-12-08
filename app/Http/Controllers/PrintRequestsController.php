<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Rawilk\Printing\Facades\Printing;

use Illuminate\Support\Facades\Auth;

use App\Models\Shops;

use App\Models\Shop_printers;
// use App\Http\Controllers\Home;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Transaction;
use App\Models\PrintJob;

use Illuminate\Support\Facades\Http;
// use App\Models\Shop;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use PDF;

use Illuminate\Support\Facades\Storage;

class PrintRequestsController extends Controller
{
    public $printerBrands = [
        "HP",

        "Canon",

        "Epson",

        "Brother",

        "Samsung",

        "Xerox",

        "Lexmark",

        "Ricoh",

        "Dell",

        "Kyocera",

        "Panasonic",

        "OKI",

        "Sharp",

        "Fujitsu",

        "Konica Minolta",

        "Zebra Technologies",

        "Toshiba",

        "Riso",

        "Primera Technology",

        "Printronix",

        "Citizen Systems",

        "Sato",

        "TSC",

        "Datamax-O'Neil",

        "Dymo",

        "Star Micronics",

        "Seiko Instruments",

        "Intermec",

        "Godex",

        "TallyGenicom",

        "Rongta",

        "Dascom",

        "WeP Solutions",

        "TVS Electronics",

        "CognitiveTPG",

        "Pantum",

        "Rongta",

        "Casio",

        "Evolis",

        "Postek",

        "TSC Auto ID",

        "Novexx Solutions",

        "Brady",

        "Axiohm",

        "C.ITOH",

        "Diagraph",

        "Printronix Auto ID",

        "Honeywell",

        "TSC Printronix Auto ID",

        "BIXOLON",

        "TSC Printronix Auto ID",
        "iR",
    ];

    public $printer_name = ["360"];

    public $printer_id = [0];

    public $data = [];

    public function print_request(Request $request)
    {
        echo "<pre>";

        print_r($request->all());

        return view("dashboard")->with("request", $request);
    }

    public function containsBrand($name)
    {
        $containsBrand = false;

        foreach ($this->printerBrands as $brand) {
            if (stripos($name, $brand) !== false) {
                $containsBrand = true;

                break;
            }
        }

        return $containsBrand;
    }

    public function onlineCheck($shop_id = null)
    {
        if (!isset($shop_id) || !$shop_id) {
            $shop_id = 0;
        }
        
        $shop = Shops::where('id',$shop_id)->first();
        
        if(!$shop->online)
        {
            return false;
        }
        else {
            return true;
        }

        // $shop_printers = Shop_printers::where(
        //     "shop_id",
        //     "=",
        //     $shop_id
        // )->first();

        // if (!$shop_printers) {
        //     return false;
        // }

        // $bw_printers = json_decode($shop_printers->bw_printer_id);

        // $color_printers = json_decode($shop_printers->color_printer_id);

        // $bw_state = false;

        // $color_state = false;

        // if (isset($bw_printers) && is_array($bw_printers)) {
        //     foreach ($bw_printers as $bw_printer) {
        //         $printer = Printing::printer($bw_printer);
        //         if($printer)
        //         {
        //              if ($printer->isOnline()) {
        //                 $bw_state = true;

        //                 break;
        //             }
        //         }
              
               
        //     }
        // } elseif (isset($bw_printers)) {
        //     $printer = Printing::printer($bw_printers);
            
        //     if($printer)
        //     {
        //         if ($printer->isOnline()) {
        //             $bw_state = true;
        //         }
        //     }
        //     else {
        //         return false;
        //     }

            
        // }

        // if (isset($color_printers) && is_array($color_printers)) {
        //     foreach ($color_printers as $color_printer) {
        //         $printer = Printing::printer($color_printer);
                
        //         if($printer)
        //         {
        //          if ($printer->isOnline()) {
        //                 $color_state = true;

        //                 break;
        //             }   
        //         }
                

                
        //     }
        // } elseif (isset($color_printers)) {
        //     $printer = Printing::printer($color_printers);
            
        //     if($printer)
        //     {
        //         if ($printer->isOnline()) {
        //             $color_state = true;
        //         }
        //     }
        //     else {
        //         return false;
        //     }

            
        // }
        
        

        // if ($color_state && $bw_state) {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    

    public function printRun($printjob, $printer_id)
    {
        // get invoice pdf
       
        $copies = $printjob->copies;

        if($printjob->double_sided == 'true'){
            $sides = "long-edge";
        }else{
            $sides =  "one-sided";
        }
       
        
        $color = $printjob->color ? true : false;
        
        if($printjob->pages_start == '1' && $printjob->page_end == '1')
        {
            $range = "-";
        }
        else {
            $range = $printjob->pages_start."-".$printjob->page_end;
        }
        

        $file = $printjob->filename;
        if(!$printjob->copies)
        {
            $printjob->copies = 1;
        }

    
        $printerJob = Printing::newPrintTask()

            ->printer($printer_id)
            // $printjob->filename
            ->url($printjob->filename)

            ->option("duplex", $sides)

            ->option("color", $color)

            ->option("pages", $range)
            
            ->option("paper", $printjob->page_size)

            ->copies($printjob->copies)

            ->send();
            
            $debug = print_r($printerJob->id(),true);
            
            Storage::disk("local")->put("khttki_printRun_debug.txt", $debug);
            
        $data = [
            "printNodeID" => $printerJob->id(),
            "status" => "Sent to Print"
            ];
            
        $printjob->update($data);
            
            

    }

    public function testDelete($printJobId)
    {
        //$printJob = Printing::deletePrintJob($printJobId);
        //echo "Testing";
        $printJob = Printing::newPrintTask()
                ->printer('72716188')
                ->url("https://files.testfile.org/PDF/200MB-TESTFILE.ORG.pdf")
                ->option("duplex", "long-edge")
                ->option("color", false)
                ->option("paper","A4")
                ->copies("1")
                ->send();
        var_dump($printJob);
    }
    public function generateInvoice($jobs,$pdf,$printer_id){
    
        try {
            $printJob = Printing::newPrintTask()
                ->printer($printer_id)
                ->url($pdf)
                ->option("duplex", "long-edge")
                ->option("color", false)
                ->option("paper","A4")
                ->copies("1")
                ->send();
           
            
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            Storage::disk("local")->put("khttki_print_request_data_error.txt",  $e->getMessage());

        }

        
        // $printJob = Printing::newPrintTask()

        // ->printer($printer_id)

        // ->url($pdf)

        // ->option("color", false)

        // ->copies("1")

        // ->send();
        // echo'<pre>';
        // print_r($printJob);exit;
        // $response = $printJob->getPrintJobStatus();
        // Storage::disk("local")->put("khttki_print_request_data.txt", $response);


    }

    public function printIt(Request $request)
    {
        $copies = $request->copies;

        $sides = $request->sides;

        $color = $request->color ? true : false;

        $range = $request->range;

        $selectedPrinter;

        if (!$request->hasFile("file")) {
            exit();
        }

        $printers = Printing::printers();

        foreach ($printers as $printer) {
            if ($this->containsBrand($printer->name())) {
                if (!$color) {
                    if (
                        !isset($printer->capabilities()["color"]) &&
                        $printer->isOnline()
                    ) {
                        $selectedPrinter = $printer->id();

                        break;
                    }
                } elseif ($printer->isOnline()) {
                    $selectedPrinter = $printer->id();

                    break;
                }
            }
        }

        if ($sides) {
            $printJob = Printing::newPrintTask()

                ->printer($request->printer)

                ->url($request->file)

                ->option("duplex", $sides)

                ->option("color", $color)

                ->option("pages", $range)

                ->copies($copies)

                ->send();
        } else {
            $printJob = Printing::newPrintTask()

                ->printer($request->printer)

                ->url($request->file)

                ->option("color", $color)

                ->option("pages", $range)

                ->copies($copies)

                ->send();
        }
    }

    public function printCashOrderIt(
        $print_job,
        $copies,
        $sides,
        $color,
        $range,
        $printer_id,
        $file
    ) {
        $id = [];
        array_push($id, $print_job->id);
        $transaction = DB::table("transactions")
            ->where("print_job_id", json_encode($id))
            ->first();
        // echo $transaction->id;exit;
        $invoice = DB::table("invoices")
            ->where("trans_id", $transaction->id)
            ->first();

        $printjob = PrintJob::where(
            "id",
            json_decode($transaction->print_job_id)
        )->first();

        if ($printjob) {
            $shop = Shops::where("id", $printjob->shop_id)->first();
        } else {
            $shop = [];
        }
        if ($shop) {
            $name = $shop;

            $invoiceDetails = DB::table("invoice_details")
                ->where("shop_id", $shop->id)
                ->first();
        } else {
            $name = "Admin";

            $invoiceDetails = DB::table("invoice_details")
                ->where("shop_id", "0")
                ->first();
        }

        $printers = DB::table("shop_printers")
            ->where("shop_id", 0)
            ->first();

        $color_printers = json_decode($printers->color_printer_id);

        $bw_printers = json_decode($printers->bw_printer_id);

        $qrCodeContent = [
            "id" => $invoice->id,

            "invoice" => $invoice->invoice_number,

            "trans_id" => $transaction->trans_id,

            "date" => $invoice->created_at,

            "amount" => $transaction->amount,
        ];

        $jsonqrCodeContent = json_encode($qrCodeContent);
        // / Generate the QR code

        $qrCode = QrCode::size(150)->generate($jsonqrCodeContent);

        $invoiceData = [
            "id" => $invoice->id,
            "invoice" => $invoice->invoice_number,
            "trans_id" => $transaction->trans_id,
            "date" => $invoice->created_at,
            "amount" => $transaction->amount,
            "color" => $printjob->color,
            "shop" => $name,
            "type" => $transaction->type,
            "copy" => $printjob->copies,
            "qrCode" => $qrCode,
            "name" => $invoiceDetails->name,
            "address" => $invoiceDetails->address,
            "license_number" => $invoiceDetails->license_number,
            "vat_number" => $invoiceDetails->vat_number,
            // Add other data here
        ];

        $pdf = PDF::loadView("invoice", $invoiceData);

        if (
            !File::isDirectory(
                public_path(
                    "/storage/WhatsApp_Files/Invoices/" . $printjob->phone
                )
            )
        ) {
            File::makeDirectory(
                public_path(
                    "/storage/WhatsApp_Files/Invoices/" . $printjob->phone
                ),
                0755,
                true,
                true
            );
        }
        $pdfPath = public_path(
            "/storage/WhatsApp_Files/Invoices/" .
                $printjob->phone .
                "/" .
                $printjob->code .
                ".pdf"
        );
        $pdf->save($pdfPath);
        $pdfPath = asset(
            "public/storage/WhatsApp_Files/Invoices/" .
                $printjob->phone .
                "/" .
                $printjob->code .
                ".pdf"
        );
        $debug = print_r($pdfPath, true);
        Storage::disk("local")->put("khttki_invoice_debug.txt", $debug);

        $copies = $copies;

        $sides = $sides;

        $color = $color ? true : false;

        $range = 1;

        $selectedPrinter = intval($printer_id);

        if (!$file) {
            exit();
        }
        if ($sides) {
            $printJob = Printing::newPrintTask()

                ->printer($selectedPrinter)

                ->url($file)

                ->option("duplex", $sides)

                ->option("color", $color)

                ->option("pages", $range)

                ->copies($copies)

                ->send();
        } else {
            $printJob = Printing::newPrintTask()

                ->printer($selectedPrinter)

                ->url($file)

                ->option("color", $color)

                ->option("pages", $range)

                ->copies($copies)

                ->send();

            $printJob = Printing::newPrintTask()

                ->printer($printer_id)

                ->url($pdfPath)

                ->option("color", false)

                ->copies("1")

                ->send();

            return 1;
        }
    }

    public function selectPrinter(Request $request)
    {
        $copies = $request->copies;

        $sides = $request->sides;

        $color = $request->color ? true : false;

        $range = $request->range;

        if ($sides) {
            $printJob = Printing::newPrintTask()

                ->printer($request->printer)

                ->url($request->file)

                ->option("duplex", $sides)

                ->option("color", $color)

                ->option("pages", $range)

                ->copies($copies)

                ->send();
        } else {
            $printJob = Printing::newPrintTask()

                ->printer($request->printer)

                ->url($request->file)

                ->option("color", $color)

                ->option("pages", $range)

                ->copies($copies)

                ->send();
        }

        echo "<pre>";

        print_r($printJob);

        echo "</pre>";
    }

    public function getPrinters()
    {
        $printers = Printing::printer(72677397);
        
        echo "<pre>";

                print_r($printers);

                echo "</pre>";
        exit;

        foreach ($printers as $printer) {
            if ($this->containsBrand($printer->name())) {
                echo "<pre>";

                print_r($printer);

                echo "</pre>";
            }
        }
    }

    public function print($file)
    {
        $printers = Printing::printers();

        $counter = 0;

        $printers_array = [];

        $printer_ids = [];

        foreach ($printers as $printer) {
            array_push($printers_array, $printer->name());

            array_push($printer_ids, $printer->id());

            echo "<pre>";

            print_r($printer);

            echo "</pre>";
        }

        $counter = 0;

        for ($i = 0; $i < count($printers_array); $i++) {
            foreach ($this->printer_name as $printer_name) {
                if (stristr($printers_array[$i], $printer_name)) {
                    $this->printer_id[$counter] = $printer_ids[$i];

                    $counter++;
                }
            }
        }

        foreach ($this->printer_id as $printer_id) {
            echo $printer_id;

            echo "<br>";
        }

        $copies = 1;

        return view("english.printer_select", compact("file", "printers"));

        //   $printJob = Printing::newPrintTask()

        //         ->printer($this->printer_id[0])

        //         ->url($file)

        //         ->copies($copies)

        //         ->send();

        //     $status = $printJob;
    }

    public function getDataPrinter()
    {
        $printers = Printing::printers();

        $debug_value = print_r($printers, true);

        Storage::disk("local")->put("khttki_printers_debug.txt", $debug_value);

        $color_array = [];

        $BW_array = [];

        foreach ($printers as $p) {
            if ($this->containsBrand($p->name())) {
                if (
                    isset($p->capabilities()["color"]) &&
                    $p->capabilities()["color"] == 1
                ) {
                    // array_push($computer_array,$p)
                    array_push($color_array, $p);

                    array_push($BW_array, $p);
                } else {
                    array_push($BW_array, $p);
                }
            }
        }

        // echo'<pre>';
        // print_r($color_array[0]->getComputer());
        // exit;

        $data["shops"] = [];

        $shopPrinters = [];
        $computerArray = [];
        if (Auth::user()->role != "superadmin") {
            $data["shops"] = Shops::Where("owner_id", Auth::user()->id)->get();

            if ($data["shops"]) {
                foreach ($data["shops"] as $s) {
                    $shopPrinters = Shop_printers::where(
                        "shop_id",
                        "=",
                        $s->id
                    )->get();
                    
                   
                    if($shopPrinters)
                    {
                        $computers = $shopPrinters[0]["computer_id"];
                        $comp = json_decode($computers);
                        foreach ($comp as $computer) {
                            array_push($computerArray, $computer);
                        }
                    }

                    
                }
            }
        } else {
            $shopPrinters = Shop_printers::where("shop_id", "=", "0")->get();
            if ($shopPrinters) {
                $computers = $shopPrinters[0]["computer_id"];
                $comp = json_decode($computers);
                foreach ($comp as $computer) {
                    array_push($computerArray, $computer);
                }
            }
        }

        return view(
            "admin.printer.index",
            compact(
                "color_array",
                "BW_array",
                "data",
                "shopPrinters",
                "computerArray"
            )
        );
    }

    public function storeShopPrinter(Request $request)
    {
        $printers = Shop_printers::where(
            "shop_id",
            "=",
            $request->shop_id
        )->first();

        if (!$printers) {
            $printers = new Shop_printers();
        }

        $color_printers = json_encode($request->color_printer_id);

        $printers->color_printer_id = $color_printers;

        $bw_printers = json_encode($request->bw_printer_id);

        $printers->bw_printer_id = $bw_printers;

        $printers->shop_id = $request->shop_id;

        $res = $printers->save();

        if ($res) {
            return redirect("getPrinters")->with(
                "success",
                "Shop printer added successfully"
            );
        } else {
            return back()->with("error", "Failed, Shop printer not addedd!");
        }
    }

    public function errorInPrinting($transaction_id)
    {
        return view('arabic.error')->with('message', 'تعذر الإتصال بالطابعة ، 
        الرجاء المحاولة لاحقا ' );
    }

    // get api base for all computers
    public function getComputers()
    {
        $api = app(\Rawilk\Printing\Api\PrintNode\PrintNode::class);
        $response = $api->computers();

        $computers = $response->computers;
        $computerArray = [];
        // print_r($computers);exit;
        foreach ($computers as $computer) {
            array_push($computerArray, $computer);
        }
        $data["shops"] = Shops::all();

        $shopPrinters = [];

        if (Auth::user()->role != "superadmin") {
            // $data['shops'] = Shops::Where('owner_id',Auth::user()->id)->get();

            if ($data["shops"]) {
                foreach ($data["shops"] as $s) {
                    $shopPrinters = Shop_printers::where(
                        "shop_id",
                        "=",
                        $s->id
                    )->get();
                }
            }
        } else {
            $shopPrinters = Shop_printers::where("shop_id", "=", "0")->get();
        }

        return view(
            "admin.computer.index",
            compact("computerArray", "shopPrinters", "data")
        );
    }
    public function storeShopComputer(Request $request)
    {
        $computers = Shop_printers::where(
            "shop_id",
            "=",
            $request->shop_id
        )->first();

        if (!$computers) {
            $computers = new Shop_printers();
        }

        $comp = json_encode($request->computer_id);

        $computers->computer_id = $comp;

        if ($request->shop_id) {
            $computers->shop_id = $request->shop_id;
        } else {
            $computers->shop_id = "0";
        }

        $res = $computers->save();

        if ($res) {
            return redirect("getComputers")->with(
                "success",
                "Shop Computer added successfully"
            );
        } else {
            return back()->with("error", "Failed, Shop Computer not addedd!");
        }
    }
    public function specificComputerPrinters()
    {
        $api = app(\Rawilk\Printing\Api\PrintNode\PrintNode::class);
        $response = $api->computers();

        $computers = $response->computers;
        $computerArray = [];
        print_r($computers);
        exit();
        foreach ($computers as $computer) {
            array_push($computerArray, $computer);
        }
        // get all computer

        foreach ($computerArray as $c) {
            $response = $client->get("https://api.printnode.com/computers", [
                "headers" => [
                    "Authorization" => "Basic " . $base64_encoded_api_key,
                    "Content-Type" => "application/json",
                ],
            ]);
        }
    }
    public function printJobStatus(Request $request){
        $response = $request->all();
        $responsedebug = print_r($response,true);
        Storage::disk("local")->put("khttki_print_request_data.txt", $responsedebug);
        
       $latestStatuses = []; // Initialize an array to store the latest status for each unique printJobId

        foreach ($response as $entry) {
            // Check if the entry has the type "print job state"
            if ($entry['type'] === 'print job state') {
                // Check if the entry has a printJobId
                if (isset($entry['data']['printJobId'])) {
                    $printJobId = $entry['data']['printJobId'];
                    
                    // Check if this entry's creation date is the latest for this printJobId
                    if (!isset($latestStatuses[$printJobId]) || strtotime($entry['createdAt']) > strtotime($latestStatuses[$printJobId]['createdAt'])) {
                        $latestStatuses[$printJobId] = [
                            'state' => $entry['data']['state'],
                            'createdAt' => $entry['createdAt'],
                        ];
                    }
                }
            }
        }
        
        // Now $latestStatuses contains the latest status for each unique printJobId
        foreach ($latestStatuses as $printJobId => $latestStatus) {
            $state = $latestStatus['state'];
            $createdAt = $latestStatus['createdAt'];
            
            $printJob = PrintJob::where('printNodeID', $printJobId);
            
            $data = ['status' => $state];
            
            $printJob->update($data);
            
            if($state == 'done')
            {
                // $payment = new PaymentController();
                // $payment->capturePayment($printJob->transaction_id);
                
            }

            if($state == 'error')
            {
                $transaction = Transaction::find($printJob->transaction_id);
                if($transaction->status != 'Refunded' || $transaction->status != "Refund Failed")
                {
                    $payment = new PaymentController();
                    $payment->refund($transaction->id);
                }
            }
        
            // You can use or display these variables as needed
            
        }
        
    $res = [
        'status' => 'success',
        'code' => '200',
        'message' => 'Operation was successful.',
        // You can add more data here if needed
    ];

    return response()->json($res);

    }

    public function removePrintJob(Request $request)
    {
        $printJob_id = $request->printJobId;
        $printJob = PrintJob::where("id",$printJob_id)->first();


       $action =  $printJob->delete();

        if($action)
        {
            $response = [
                'success' => true,
                'message' => 'Operation was successful.',
            ];
        
            return response()->json($response);
        
        }
        else {
            $response = [
                'success' => false,
                'message' => 'Operation was unsuccessful.',
            ];
        
            return response()->json($response);
        }
    }

    public function isPrinterOnline($printer_ID)
    {
       $printer = Printing::printer($printer_ID);
       return $printer->isOnline();

    }

    
}
