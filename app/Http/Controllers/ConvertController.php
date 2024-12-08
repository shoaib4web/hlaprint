<?php

namespace App\Http\Controllers;

use Session;

use Aspose\Words\WordsApi;

use App\Models\PdfFilesModel;

use App\Models\PrintJob;

use Illuminate\Support\Facades\Storage;

use Aspose\Words\Model\Requests\{ConvertDocumentRequest};

//use File;

use Aspose\Slides\Cloud\Sdk\Api\Configuration;

use Aspose\Slides\Cloud\Sdk\Api\SlidesApi;

use Aspose\Slides\Cloud\Sdk\Api\SlidesAsyncApi;

use Aspose\Slides\Cloud\Sdk\Model\ExportFormat;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\DB;

use PhpOffice\PhpPresentation\IOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use \Aspose\Imaging\ImagingApi;
use \Aspose\Imaging\Configuration as AsposeImagingConfiguration;
use \Aspose\Imaging\Model;
use \Aspose\Imaging\Model\Requests;
use \Aspose\Imaging\Model\Requests\ConvertImageRequest;


class ConvertController extends Controller
{
    public $data = [];

    public $pdfApi;

    public $nameArray;

    public $inc;

    public function test()
    {
        $file = asset("public/storage/001800.pdf");

        $phone = "0000000";

        return view("english.document", compact("file", "phone"));
    }

    public function convert($file, $phone, $code)
    {
        // print_r($file);
        $fileNameParts = explode(".", $file);

        $exactFileName = $fileNameParts[1];
        $exactFileName = explode("/", $exactFileName);
        $exactFileName = end($exactFileName);
        $extension = end($fileNameParts);
        $fileToUpload = $exactFileName . "." . $extension;
        if ($extension == "pdf") {
            if (
                !File::isDirectory(
                    public_path("/storage/WhatsApp_Files/" . $phone)
                )
            ) {
                File::makeDirectory(
                    public_path("/storage/WhatsApp_Files/" . $phone),
                    0755,
                    true,
                    true
                );
            }
            $sourcePath = Storage::path(
                "public/WhatsApp_Files/" .
                    $phone .
                    "/" .
                    $exactFileName .
                    ".pdf"
            );

            $destinationPath = public_path(
                "storage/WhatsApp_Files/" .
                    $phone .
                    "/" .
                    $exactFileName .
                    ".pdf"
            );

            File::move($sourcePath, $destinationPath);

           

            $name = asset(
                "public/storage/WhatsApp_Files/$phone/$exactFileName.pdf"
            );
            
           // $name = $destinationPath;
            
            return $name;
            
        } elseif ($extension == "ppt" || $extension == "pptx") {
           // $name = $this->powerpointToPDF($fileToUpload, $phone, $code);
           //$name = $this->powerpointToPDF($fileToUpload, $phone, $code);
           $name = $this->ConvertAllLocal($fileToUpload,$phone,$code);

            return $name;
        } elseif ($extension == "doc" || $extension == "docx") {
            // echo 'here';exit;
            //$name = $this->wordToPDF($fileToUpload, $phone, $code);
            //$name = $this->wordToPDF($fileToUpload, $phone, $code);
            $name = $this->ConvertAllLocal($fileToUpload,$phone,$code);

            return $name;
        } elseif (
            $extension == "xlsx" ||
            $extension == "xls" ||
            $extension == "csv" ||
            $extension == "ods"
        ) {
            //$tkn = $this->generateToken();

           // $name = $this->excelToPDF($tkn, $fileToUpload, $phone, $code);
            $name = $this->ConvertAllLocal($fileToUpload,$phone,$code);

            return $name;
        } elseif (
            $extension == "jpeg" ||
            $extension == "jpg" ||
            $extension == "png"
        ) {
            //$tkn = $this->generateToken();

            // //  echo $tkn;exit;

            //$name = $this->imageToPDF($tkn, $fileToUpload, $phone, $code);
            $name = $this->ConvertAllLocal($fileToUpload,$phone,$code);

            return $name;
        }
    }

    public function convertToPdf()
    {
        $clientId = "cdc7500e-a452-4389-ac6e-5dd134d87428";

        $secret = "ab70885c5908c1e87a4760fb2df25cb7";

        $wordsApi = new WordsApi($clientId, $secret);

        if (Session::has("FileName")) {
            $fileName = Session::get("FileName");

            $wordFile = public_path("uploads/" . $fileName);
        }

        // $requestDocument = public_path('input.doc');

        $requestDocument = $wordFile;

        $outputFileName = "outputFile.pdf";

        $convertRequest = new ConvertDocumentRequest(
            $requestDocument,

            "pdf",

            null,

            null,

            null,

            null,

            null,

            null,

            null
        );

        try {
            // Convert the document

            $response = $wordsApi->convertDocument($convertRequest);

            // Retrieve the converted document content

            $fileContent = file_get_contents($response->getPathname());

            // Save the content to a file

            file_put_contents(public_path($outputFileName), $fileContent);

            $path = public_path($outputFileName);

            return response()->download($path);

            // ... rest of the code ...
        } catch (\Exception $e) {
            // Handle the error case

            // Display an error message or perform appropriate error handling

            $error = $e->getMessage();
        }
    }

    public function wordToPDF($fileName, $phone, $code)
    {
        $this->data["fileLocation"] =
            "storage/app/public/WhatsApp_Files/" . $phone;

        $this->data["fileName"] = $fileName;

        $clientId = "cdc7500e-a452-4389-ac6e-5dd134d87428";

        $secret = "ab70885c5908c1e87a4760fb2df25cb7";

        $wordsApi = new WordsApi($clientId, $secret);

        $this->data["requestDocument"] =
            $this->data["fileLocation"] . "/" . $this->data["fileName"];

        $this->data["outputFileName"] = $code; //explode('_', $this->data['fileName'])[4];

        $convertRequest = new ConvertDocumentRequest(
            $this->data["requestDocument"],

            "pdf",

            null,

            null,

            null,

            null,

            null,

            null,

            null
        );

        try {
            $response = $wordsApi->convertDocument($convertRequest);

            $fileContent = file_get_contents($response->getPathname());

            if (
                !File::isDirectory(
                    public_path(
                        "/storage/WhatsApp_Files/" .
                            explode("/", $this->data["fileLocation"])[4]
                    )
                )
            ) {
                File::makeDirectory(
                    public_path(
                        "/storage/WhatsApp_Files/" .
                            explode("/", $this->data["fileLocation"])[4]
                    ),
                    0777,
                    true,
                    true
                );
            }

            file_put_contents(
                public_path(
                    "/storage/WhatsApp_Files/" .
                        explode("/", $this->data["fileLocation"])[4] .
                        "/" .
                        $this->data["outputFileName"] .
                        ".pdf"
                ),
                $fileContent
            );

            $this->data["file"] = public_path(
                $this->data["fileLocation"] .
                    "/" .
                    $this->data["outputFileName"] .
                    ".pdf"
            );

            $this->data["public_path"] = public_path(
                "/storage/WhatsApp_Files/" .
                    explode("/", $this->data["fileLocation"])[4] .
                    "/" .
                    $this->data["outputFileName"] .
                    ".pdf"
            );

            $this->data["public_path"] = array_slice(
                explode("/", $this->data["public_path"]),
                5
            );

            $this->data["public_path"] = implode(
                "/",
                $this->data["public_path"]
            );

            $this->data["public_path"] = url($this->data["public_path"]);

           

            $name = asset("public/storage/WhatsApp_Files/$phone/$code.pdf");

            return $name;

            // $addText = new HomeController();

            $fileForCode = public_path(
                "/storage/WhatsApp_Files/" . $phone . "/" . $code . ".pdf"
            );

            // $this->updateTotalPages($code,$fileForCode);

            echo $fileForCode;
            exit();

            $result = PrintJob::where("code", $code)->update([
                "filename" => $fileForCode,
                "status" => "Recieved",
            ]);

            // $print_request = new PrintRequestsController();

            //$print_request->print($this->data['public_path']);
        } catch (\Exception $e) {
            $error = $e;

            Log::error('Error occurred in Word Coversion Line 340: ' . $e->getMessage());
        }
    }

    function updateTotalPages($code, $file)
    {
        $printJob = PrintJob::where("code", "=", $code)->first();

        if ($printJob) {
            $printJob->total_pages = countPages($file);
        }
    }

    function countPages($path)
    {
        $pdftext = file_get_contents($path);

        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

        return $num;
    }

    function excelToPDF($tkn, $file, $phone, $code)
    {
        $url = "https://api.aspose.cloud/v3.0/cells/convert?format=pdf";

        $headers = [
            "accept: multipart/form-data",

            "Content-Type: multipart/form-data",

            "Authorization: Bearer " . $tkn,
        ];

        $data = [
            "File" => new \CURLFile(
                "storage/app/public/WhatsApp_Files/" . $phone . "/" . $file
            ),
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        curl_close($ch);

        if ($response === false) {
            echo "cURL error: " . curl_error($ch);
        } else {
            if (
                !File::isDirectory(
                    public_path("/storage/WhatsApp_Files/" . $phone)
                )
            ) {
                File::makeDirectory(
                    public_path("/storage/WhatsApp_Files/" . $phone),
                    0755,
                    true,
                    true
                );
            }

            file_put_contents(
                public_path(
                    "storage/WhatsApp_Files/" . $phone . "/" . $code . ".pdf"
                ),
                $response
            );

           
            $name = asset("public/storage/WhatsApp_Files/$phone/$code.pdf");

            return $name;

            // $addText = new HomeController();

            // $fileForCode = public_path('/storage/WhatsApp_Files/'.$phone.'/'.$code . '.pdf');

            //  $addText->addTextToPDF($fileForCode, $code);
        }
    }

    public $i = 1;

    public function ConvertAllLocal($file,$phone,$code)
    {
        // Set Headers and URL for CURL
$url = "http://localhost:5000/api/documents/convert-to-pdf"; // Local API URL

$headers = [
    "accept: multipart/form-data",
    "Content-Type: multipart/form-data",
    // Remove the Authorization header if your local API doesn't require it
    // "Authorization: Bearer " . $token,
];

// Get the file from Storage
$data = [
    "File" => new \CURLFile(
        storage_path("app/public/WhatsApp_Files/" . $phone . "/" . $file)
    ),
];

// Set Curl Options
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
Log::info('Converting... ');

// Handle Error
if (!$response) {
    Log::info('Converting Error ' . curl_error($ch));
} else {
    // new code
    if (!File::isDirectory(public_path("storage/WhatsApp_Files/" . $phone))) {
        File::makeDirectory(
            public_path("storage/WhatsApp_Files/" . $phone),
            0755,
            true,
            true
        );
    }
    
    $file = explode(".", $file);
    $file = $file[0];

    file_put_contents(
        public_path("storage/WhatsApp_Files/" . $phone . "/" . $file . ".pdf"),
        $response
    );

    $name = asset("public/storage/WhatsApp_Files/$phone/$file.pdf");

    $result = PrintJob::where("code", $code)->update([
        "status" => "Received",
    ]);

    $printJob = PrintJob::where("code", $code)->first();

    return $name;
}
    }

    public function imageToPDF($token, $file, $phone, $code)
    {

        

        //    Set Headers and URL for CURL

        $url = "https://api.aspose.cloud/v3.0/imaging/convert?format=pdf";

        $headers = [
            "accept: multipart/form-data",

            "Content-Type: multipart/form-data",

            "Authorization: Bearer " . $token,
        ];

        // Get the file from Storage

        $data = [
            "File" => new \CURLFile(
                "storage/app/public/WhatsApp_Files/" . $phone . "/" . $file
            ),
        ];

        // Set Curl Options

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

       
        //Handle Error
        if (!$response) {
            
            Log::info('Converting Error ' . curl_error($ch));
        } else {
            //newcode

            if (
                !File::isDirectory(
                    public_path("/storage/WhatsApp_Files/" . $phone)
                )
            ) {
                File::makeDirectory(
                    public_path("/storage/WhatsApp_Files/" . $phone),
                    0755,
                    true,
                    true
                );
            }
            Log::info('Converting Response ');
            $file = explode(".", $file);

            $file = $file[0];

            file_put_contents(
                public_path(
                    "storage/WhatsApp_Files/" . $phone . "/" . $file . ".pdf"
                ),
                $response
            );

            $name = asset("public/storage/WhatsApp_Files/$phone/$file.pdf");

    

            $result = PrintJob::where("code", $code)->update([
                "status" => "Recieved",
            ]);

            $printJob = PrintJob::where("code", $code)->first();

            

            return $name;


        }
    }

    public function PPTXtoPDF()
    {
        $config = new Configuration();

        $config->setAppSid("cdc7500e-a452-4389-ac6e-5dd134d87428");

        $config->setAppKey("ab70885c5908c1e87a4760fb2df25cb7");

        $api = new SlidesAsyncApi(null, $config);

        $presentationFile = fopen(
            "storage/app/public/WhatsApp_Files/" . $phone . "/" . $file,
            "r"
        );
        $operationId = $api->startConvert($presentationFile, ExportFormat::PDF);

        if (
            !File::isDirectory(public_path("/storage/WhatsApp_Files/" . $phone))
        ) {
            File::makeDirectory(
                public_path("/storage/WhatsApp_Files/" . $phone),
                0755,
                true,
                true
            );
        }

        while (true)
        {
            

            // Wait for 3 seconds
            sleep(3);
            $operation = $api->getOperationStatus($operationId);
            
            if ($operation->getStatus() == "Canceled")
            {
                break;
            }
            else if ($operation->getStatus() == "Failed")
            {
                Log::error($operation->getError());
                break;
            }
            else if ($operation->getStatus() == "Finished")
            {
                $converted = $api->getOperationResult($operationId);
                $fileContent = file_get_contents($converted->getPathname());
                file_put_contents(public_path(
                    "storage/WhatsApp_Files/" . $phone . "/" . $code . ".pdf"
                ), $fileContent);
               
                break;
            }
}

        
    }

    public function powerpointToPDF($file, $phone, $code)
    {
        $config = new Configuration();

        $config->setAppSid("cdc7500e-a452-4389-ac6e-5dd134d87428");

        $config->setAppKey("ab70885c5908c1e87a4760fb2df25cb7");

        $api = new SlidesAsyncApi(null, $config);

        $presentationFile = fopen(
            "storage/app/public/WhatsApp_Files/" . $phone . "/" . $file,
            "r"
        );
        $operationId = $api->startConvert($presentationFile, ExportFormat::PDF);

        if (
            !File::isDirectory(public_path("/storage/WhatsApp_Files/" . $phone))
        ) {
            File::makeDirectory(
                public_path("/storage/WhatsApp_Files/" . $phone),
                0755,
                true,
                true
            );
        }

        while (true)
        {
            

            // Wait for 3 seconds
            sleep(3);
            $operation = $api->getOperationStatus($operationId);
            
            if ($operation->getStatus() == "Canceled")
            {
                break;
            }
            else if ($operation->getStatus() == "Failed")
            {
                Log::error($operation->getError());
                break;
            }
            else if ($operation->getStatus() == "Finished")
            {
                $converted = $api->getOperationResult($operationId);
                $fileContent = file_get_contents($converted->getPathname());
                file_put_contents(public_path(
                    "storage/WhatsApp_Files/" . $phone . "/" . $code . ".pdf"
                ), $fileContent);
               
                break;
            }
        }
        
      
        $name = asset("public/storage/WhatsApp_Files/$phone/$code.pdf");

        return $name;
    }

    //---------------------------------------------------------------------- Convert

    public function convertx(Request $request, $phone, $filename)
    {
        $request->session()->put("phone", $phone);

        $request->session()->put("filename", $filename);

        return view("arabic.convert");
    }

    //---------------------------------------------------------------------- Save PDF

    public function save_pdf(Request $request)
    {
        $file = $request->file("file");

        $file->move(
            public_path("storage/WhatsApp_Files/") . session("phone") . "/",
            $file->getClientOriginalName()
        );

        return response()->json(["success" => true]);
    }

    public function page()
    {
        $data["greyScale"] = 10;

        $data["color"] = 15;

        $data["oneSide"] = 10;

        $data["twoSide"] = 15;

        $data["allPages"] = 10;

        $data["perCopy"] = 10;

        $data["total"] = 10;

        dd($data);

        return view("english.document")->with($data);
    }

    function generateToken()
    {
        $url = "https://api.aspose.cloud/connect/token";

        $data =
            "grant_type=client_credentials&client_id=cdc7500e-a452-4389-ac6e-5dd134d87428&client_secret=ab70885c5908c1e87a4760fb2df25cb7";

        $headers = [
            "Content-Type: application/x-www-form-urlencoded",

            "Accept: application/json",
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        $debug_value = print_r($response, true);

        Storage::disk("local")->put("khttki_token_debug.txt", $data);

        curl_close($ch);

        if ($response === false) {
            echo "cURL error: " . curl_error($ch);

            return false;
        } else {
            $json = json_decode($response);

            $accessToken = $json->access_token;

            return $accessToken;
        }
    }

   public function convertPptxToPdf(Request $request)
    {
        // Validate the incoming request
        
    
        // Get the uploaded PowerPoint file
        $pptxFile = $request->file('pptx_file');
    
        // Load the PowerPoint file
        $objReader = IOFactory::createReader('PowerPoint2007');
        $objPHPPresentation = $objReader->load($pptxFile->getPathname());
    
        // Create Dompdf instance
        $dompdf = new Dompdf();
    
        // Create options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
    
        // Set options
        $dompdf->setOptions($options);
    
        // Loop through each slide and render as image
        $slides = $objPHPPresentation->getAllSlides();
        foreach ($slides as $slide) {
            // Create a new Drawing object for the slide
            $drawing = $slide->getShapeCollection()[0];
    
            // Check if the shape is a Drawing object
            if ($drawing instanceof PhpOffice\PhpPresentation\Shape\Drawing) {
                // Get image contents
                ob_start();
                $imageContents = imagejpeg($drawing->getImageResource());
                ob_end_clean();
    
                // Convert image contents to base64
                $imageData = 'data:image/jpeg;base64,' . base64_encode($imageContents);
    
                // Add image to Dompdf
                $dompdf->getCanvas()->drawImageBlob($imageData, 0, 0, null, null);
                $dompdf->addPage();
            }
        }
    
        // Output PDF content
        $dompdf->render();
        return $dompdf->output();
    }
}
