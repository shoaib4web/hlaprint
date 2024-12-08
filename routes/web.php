<?php



use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PrintRequestsController;

use App\Http\Controllers\WAController;

use UltraMsg\WhatsAppApi;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Request;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\ConvertController;

use App\Http\Controllers\PrintJobController;

use App\Http\Controllers\ShopController;

use App\Http\Controllers\TransactionController;

use App\Http\Controllers\UploadController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\ArtisanController;

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\FinancialDetailsController;

/**
 * INVOICE DEBUG INCLUDES
 */
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Transaction;
use App\Models\PrintJob;
use App\Models\Invoice;


/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider and all of them will

| be assigned to the "web" middleware group. Make something great!

|

*/



Route::get('/', [HomeController::class, 'index'])->name('index');



Route::group(['middleware' => ['auth', 'verified']], function () {

     Route::get('sales', [TransactionController::class, 'sales']);
    Route::post('sales', [TransactionController::class, 'filterSales']);
    Route::get('updatePassword/{userId}/{password}', [UserController::class, 'updatePassword'])->middleware('is_superadmin');


    Route::get('/refund/{trans_id}',[App\Http\Controllers\PaymentController::class, 'refund']);
    Route::get('/price-options',[App\Http\Controllers\PriceOptionsController::class, 'index'])->name('price-options');
Route::get('/create-price-options',[App\Http\Controllers\PriceOptionsController::class, 'create']);
Route::get('/edit-price/{id}',[App\Http\Controllers\PriceOptionsController::class, 'edit']);
Route::post('/update-price',[App\Http\Controllers\PriceOptionsController::class, 'update']);
     Route::post('/store-pricing',[App\Http\Controllers\PriceOptionsController::class, 'store']);

    //User Routes...

    Route::get('/dashboard', [HomeController::class, 'Dashboard'])->name('dashboard');

    Route::any('/users', [UserController::class, 'index']);

    Route::get('/create-user', [UserController::class, 'createUser'])->middleware('is_superadmin');

    Route::post('/store_user', [UserController::class, 'storeUser'])->middleware('is_superadmin');

    Route::get('/edit_user/{id}', [UserController::class, 'editUser'])->middleware('is_superadmin');

    Route::get('/assignShop/{id}',[UserController::class, 'assignShop'])->middleware('is_superadmin');

    Route::get('/deleteUser/{id}',[UserController::class, 'deleteUser'])->middleware('is_superadmin');

    Route::post('/storeAssignedShop', [UserController::class, 'storeAssignedShop'])->middleware('is_superadmin')->name('storeAssignedShop');


    Route::post('/update_user', [UserController::class, 'updateUser'])->middleware('is_superadmin');



    //Print Jobs Routes..

    Route::get('/color-size', [UserController::class, 'colorSize']);

    Route::get('/create-color-size/{id}', [UserController::class, 'createColorSize']);

    Route::get('/edit-color-size', [UserController::class, 'editColorSize']);

    Route::get('/edit-shop-color-size/{id}', [UserController::class, 'editShopColorSize'])->name('editShopColor');

    Route::post('/store_color_size', [UserController::class, 'storeColorSize']);





    //Shop Routes...

    Route::any('shops', [ShopController::class, 'index']);

    Route::get('create-shop', [ShopController::class, 'createShop'])->middleware('is_superadmin');

    Route::post('store_shop', [ShopController::class, 'storeShop'])->middleware('is_superadmin');

    Route::get('/edit_shop/{id}', [ShopController::class, 'editShop'])->middleware('is_superadmin');

    Route::post('/update_shop', [ShopController::class, 'updateShop'])->middleware('is_superadmin');

    Route::get('delete_shop', [ShopController::class, 'deleteShop'])->middleware('is_superadmin');

    Route::get('addWAToShop/{shop_id}/{wa_id}',[ShopController::class, 'addWAToShop'])->middleware('is_superadmin');
    Route::get('getWAofShop/{shop_id}',[ShopController::class, 'getWAofShop'])->middleware('is_superadmin');
    Route::get('deleteWAfromShop/{shop_id}',[ShopController::class, 'deleteWAfromShop'])->middleware('is_superadmin');
    Route::get('deleteUnusedWA',[ShopController::class, 'deleteUnused'])->middleware('is_superadmin');




    //Print Job Routes...

    Route::get('print_jobs/{shop_id?}', [PrintJobController::class, 'printJobs']);

    Route::get('create-print-job', [PrintJobController::class, 'createPrintJob']);

    Route::post('store_print_job', [PrintJobController::class, 'storePrintJob']);

    Route::any('print-jobs', [PrintJobController::class, 'index']);

    Route::any('cash-order', [PrintJobController::class, 'cashOrder']);

    Route::get('update_status', [PrintJobController::class, 'updateStatus']);



    //approve cash payement records

    Route::any('approve-order/{code}', [HomeController::class, 'cashApprove'])->name('cashApprove');
    Route::get('toggleSystem/{shop_id}/{status}',[HomeController::class, 'toggleSystem']);



    //Transaction Routes...

    Route::get('transaction', [TransactionController::class, 'index']);

    Route::post('transaction', [TransactionController::class, 'filterTransaction']);





    Route::get('generate-invoice/{id}', [TransactionController::class, 'generateInvoice']);

    Route::get('get-invoice/{id}', [TransactionController::class, 'getInvoice']);





    Route::get('createInvoiceDetail/{shop_id?}', [TransactionController::class, 'invoiceDetail']);
    Route::get('editInvoiceDetail/{shop_id?}', [TransactionController::class, 'editInvoiceDetail']);

    Route::post('/store_invoice_details', [TransactionController::class, 'storeInvoiceDetail']);
    Route::put('/store_invoice_details', [TransactionController::class, 'storeInvoiceDetail']);





    Route::get('rePrint/{id}', [TransactionController::class, 'rePrint']);



});







Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/codeInput',[HomeController::class, 'getCodeInput']);

});



require __DIR__ . '/auth.php';



Route::post('/print_request', [PrintRequestsController::class, 'print_request'])->name('print_request');



Route::webhooks('wa')->name('wa');



Route::post('whatsapp', [WAController::class, 'indexPost'])->name('indexPost');



Route::get('whatsapp', [WAController::class, 'index'])->name('whatsapp');


Route::get('/check-payment-status/{orderId}', [TransactionController::class, 'checkStatus']);
Route::get('/test-del/{printJobId}', [PrintRequestsController::class, 'testDelete']);


//print log function



Route::get('/log', function (Request $request) {

    dd(DB::getQueryLog());

});



Route::get('/print', [PrintRequestsController::class, 'print'])->name('print');

Route::get('/test', [WAController::class, 'test'])->name('test');





Route::get('/code', [HomeController::class, 'code'])->name('code');

Route::get('/document', [HomeController::class, 'document'])->name('document');

Route::get('/pay', [HomeController::class, 'pay'])->name('pay');

Route::get('/processing', [HomeController::class, 'processing'])->name('processing');

Route::get('/englishShare', [HomeController::class, 'arabic-share'])->name('arabic-share');

Route::get('/success', [HomeController::class, 'arabicSuccess'])->name('success');
Route::get('/successful', [HomeController::class, 'selfSetviceSuccess'])->name('successful');

Route::post('/submitCode', [HomeController::class, 'submitCode'])->name('submitCode');





Route::get('/page', [HomeController::class, 'page'])->name('page');

Route::get('/arabicCode', [HomeController::class, 'arabicCode'])->name('arabicCode');

//Route::get('/arabicDocument', [HomeController::class, 'arabicDocument'])->name('arabicDocument');

Route::get('/arabicPay', [HomeController::class, 'arabicPay'])->name('arabicPay');

Route::get('/arabicProcessing', [HomeController::class, 'arabicProcessing'])->name('arabicProcessing');

Route::get('/arabicShare', [HomeController::class, 'arabicShare'])->name('arabicShare');

Route::post('/arabicSubmitCode', [HomeController::class, 'arabicSubmitCode'])->name('arabicCode');

Route::get('/error', [HomeController::class, 'arabicError'])->name('arabicError');





Route::get('/english-error', [HomeController::class, 'englishError'])->name('englishError');

Route::get('/englishCode', [HomeController::class, 'englishCode'])->name('englishCode');

Route::get('/englishDocument', [HomeController::class, 'englishDocument'])->name('englishDocument');

Route::get('/englishPay', [HomeController::class, 'englishPay'])->name('englishPay');

Route::get('/englishProcessing', [HomeController::class, 'englishProcessing'])->name('englishProcessing');

Route::get('/englishShare', [HomeController::class, 'englishShare'])->name('englishShare');

Route::post('/englishSubmitCode', [HomeController::class, 'englishSubmitCode'])->name('englishCode');

Route::post('/englishQrCode', [HomeController::class, 'englishQrCode'])->name('englishQrCode');

Route::get('/englishQrCode', [HomeController::class, 'englishQrCode'])->name('englishQrCode');

Route::get('/getOptions/{code}/{shop_id?}', [HomeController::class, 'getOptions'])->name('getOptions');

Route::get('/getArabicOptions/{code}/{shop_id?}', [HomeController::class, 'getArabicOptions'])->name('getArabicOptions');





Route::get('/shop/{id}', [HomeController::class, 'shop'])->name('shop');

Route::get('/financials', [HomeController::class, 'financials'])->name('financials');



Route::post('/select_printer', [PrintRequestsController::class, 'selectPrinter'])->name('printer_select');

Route::get('/getAllPrinters', [PrintRequestsController::class, 'getPrinters']);

Route::get('/getPrinters', [PrintRequestsController::class, 'getDataPrinter']);

Route::post('/store_shop_printer', [PrintRequestsController::class, 'storeShopPrinter']);



Route::get('/getComputers', [PrintRequestsController::class, 'getComputers']);

Route::post('/store_shop_computer', [PrintRequestsController::class, 'storeShopComputer']);





Route::post('/submitDocument', [HomeController::class, 'submitDocument'])->name('submitDocument');



Route::get('convert', [ConvertController::class, 'convertToPdf'])->name('convertToPdf');

Route::get('/upload', [UploadController::class, 'uploadview'])->name('upload');





Route::post('/upload', [UploadController::class, 'upload'])->name('upload');

Route::get('/uploadShop/{id}', [UploadController::class, 'uploadShop'])->name('uploadShop');



Route::POST('/destroy-modal-session', [HomeController::class, 'removeModalSession'])->name('remove_modal_session');



Route::get('/convertPDF', [PrintRequestsController::class, 'convertPDF'])->name('convertPDF');

Route::get('convert/{phone}/{filename}', [HomeController::class, 'convert'])->name('convert');

Route::post('save-pdf', [HomeController::class, 'save_pdf']);





Route::group(['prefix' => 'arabic'], function () {

    Route::get('/code', [HomeController::class, 'arabicCode'])->name('arabicCode');

    //Route::get('/document', [HomeController::class, 'arabicDocument'])->name('arabicDocument');

    Route::get('/pay', [HomeController::class, 'arabicPay'])->name('arabicPay');

    Route::get('/processing', [HomeController::class, 'arabicProcessing'])->name('arabicProcessing');

    Route::get('/share', [HomeController::class, 'arabicShare'])->name('arabicShare');

    Route::post('/code', [HomeController::class, 'arabicSubmitCode'])->name('arabicSubmitCode');

    Route::post('/document', [HomeController::class, 'arabicSubmitDocument'])->name('aSubmitDocument');

    Route::get('/success', [HomeController::class, 'arabicSuccess'])->name('arabicSuccess');





    Route::get('/convert/{phone}/{filename}', [HomeController::class, 'arabicConvert'])->name('arabicConvert');

    Route::post('save-pdf', [HomeController::class, 'arabicSave_pdf']);

});



Route::group(['prefix' => 'english'], function () {

    Route::get('/code', [HomeController::class, 'englishCode'])->name('englishCode');

    Route::get('/document', [HomeController::class, 'englishDocument'])->name('englishDocument');

    Route::get('/pay', [HomeController::class, 'englishPay'])->name('englishPay');

    Route::get('/processing', [HomeController::class, 'englishProcessing'])->name('englishProcessing');

    Route::get('/share', [HomeController::class, 'englishShare'])->name('englishShare');

    Route::post('/code', [HomeController::class, 'englishSubmitCode'])->name('englishSubmitCode');

    Route::post('/document', [HomeController::class, 'englishSubmitDocument'])->name('eSubmitDocument');

    Route::get('/success', [HomeController::class, 'englishSuccess'])->name('englishSuccess');

    Route::get('/convert/{phone}/{filename}', [HomeController::class, 'englishConvert'])->name('englishConvert');

    Route::post('save-pdf', [HomeController::class, 'englishSave_pdf']);

});



Route::get('/test', [HomeController::class, 'test'])->name('test');

Route::get('/pdf/preview', [App\Http\Controllers\PDFController::class, 'preview'])->name('pdf.preview');



Route::get('createMigration/{tablename}', [App\Http\Controllers\ArtisanController::class, 'generateMigrationAndModel']);

Route::get('runSpecificMigration/{migrationClassName}', [App\Http\Controllers\ArtisanController::class, 'runSpecificMigration']);



//gettotalpages



Route::any('getTotalPages', [HomeController::class, 'getTotalPages']);



//cache clear

Route::get('/cache', function () {

    \Artisan::call('optimize:clear');

    \Artisan::call('config:cache');

     echo 'All cache Cleared';

});





// printnode request data



Route::post('printJobStatus',[PrintRequestsController::class, 'printJobStatus']);

Route::post('removePrintJob',[PrintRequestsController::class, 'removePrintJob']);

Route::get('testCapture/{id}',[PaymentController::class, 'capturePaymentTest']);



Route::get('hitWebhook',[PrintRequestsController::class, 'hitWebhook']);



Route::any('arabicView/{code}/{shop?}', [HomeController::class, 'getArabicViewOptions'])->name("arabicView");


Route::get('/transPrintJob',[PrintJobController::class, 'TransPrintJob']);
Route::get('/TransCashJob',[PrintJobController::class, 'TransCashJob']);

Route::get('generate-new-print/{code}', [TransactionController::class, 'newRePrint']);
Route::any('approve-cash-order/{code}', [PrintJobController::class, 'cashApprove']);



//get arabic nw view

// Route::get('/arabicView', function () {

//     return view('arabic.arraysarabic',compact('shop','code'));

// });



//get arabic nw view

Route::get('/englishView', function () {

    return view('english.english');

});





// download invoice pdf

Route::any('downloadPdf', [HomeController::class, 'downloadPdf']);

Route::get('testProcessing', [HomeController::class, 'PaymentLoader']);

Route::get('/printFromCode/{code}/{id}', [HomeController::class, 'PrintCodeInput'])->name('printFromCode');;





//get test view of pdf

Route::get('/invoice', function () {
    $transaction = Transaction::find(286);
    $printjobs_id = json_decode($transaction->print_job_id);
    $printjobs = PrintJob::whereIn('id',$printjobs_id)->get();
    $invoiceDetails = DB::table('invoice_details')->where('shop_id', 7)->first();
    $invoice = Invoice::where('id',$transaction->invoice_id)->first();
    $phone = 0;
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

    return view("InvoiceNew",compact('priceVariation','transaction','invoiceDetails','qrCode','invoice'));

});





Route::get('/pagination', function () {

    \Artisan::call('vendor:publish', [

        '--tag' => 'laravel-pagination',

    ]);

    echo 'Vendor Done';

});



Route::get('/QRcode', function () {

    $provider = 'SimpleSoftwareIO\QrCode\QrCodeServiceProvider';

    \Artisan::call('vendor:publish', [

        '--provider' => $provider,

    ]);

    echo 'QRcode package Done';

});



//for invoice details

Route::get('refreshSpecificMigration',function(){

    \Artisan::call('migrate:refresh', [

        '--path' => 'database/migrations/2023_09_14_070603_create_invoice_detail_table.php',

    ]);

    echo 'migration refresh';

});





Route::post('/initiate-payment', 'PaymentController@initiatePayment');

Route::any('/handle-payment-response', [App\Http\Controllers\PaymentController::class, 'handlePaymentResponse'])->name('handlePaymentResponse');
Route::get('/refund/{{trans_id}}',[App\Http\Controllers\PaymentController::class, 'refund']);

Route::any('/handle-payment-end/{trans_id}/{page}', [App\Http\Controllers\PaymentController::class, 'handlePaymentEnd'])->name('handlePaymentEnd');
Route::get('/validate-apple-pay',[App\Http\Controllers\PaymentController::class, 'validateApplePay'] )->name('applePayMerchantValidate');
Route::post('/process-apple-payment',[App\Http\Controllers\PaymentController::class, 'processApplePayment'] )->name('processApplePayment');
Route::get('/apple-pay-test',[App\Http\Controllers\PaymentController::class, 'applePayTest']);

//Client Routes

Route::post('/Verify',[App\Http\Controllers\HomeController::class, 'verifyCredentials'])->withoutMiddleware(['csrf']);

//WEBSCOKETS
Route::get('/test-socket',[App\Http\Controllers\HomeController::class, 'khttki_Sockets']);
Route::get('/DownloadFiles/{trans_id}', [HomeController::class, 'Download'])->withoutMiddleware(['csrf']);
Route::get('/convert-pptx-to-pdf', [ConvertController::class, 'PPTXtoPDF'])->name("testPPTX")->withoutMiddleware(['csrf']);
Route::get('/pptx-test', function (){return view('testing_pptx');});
Route::any('test-view/{code}/{shop?}', [HomeController::class, 'getTestArabicViewOptions']);
Route::get('/checkFiles/{code}', [HomeController::class, 'checkIfFileConverted']);

//Shop Options
use App\Http\Controllers\ShopOptionController;

Route::get('shop_options/{id}/edit', [ShopOptionController::class, 'edit'])->name('shop_options.edit');
Route::put('shop_options/{id}', [ShopOptionController::class, 'update'])->name('shop_options.update');

// Route to show the form for creating financial details for a shop
Route::get('/financial-details', [FinancialDetailsController::class, 'load'])->name('financial-details');

// Route to store the financial details for a shop
Route::post('/store_financial_details', [FinancialDetailsController::class, 'store']);

// Route to show the form for editing financial details for a shop
Route::get('/financial-details/edit', [FinancialDetailsController::class, 'edit'])->name('financial-details.edit');

// Route to update the financial details for a shop
Route::put('/update_financial_details/{id}', [FinancialDetailsController::class, 'update'])->name('update_financial_details');

//Split Payment testing
Route::get('/split-payment-test', [HomeController::class, 'SplitTesting']);



Route::get('/documentation/api',function(){
  return view('documentation');
});



include 'websockets.php';
