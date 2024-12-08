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

    //User Routes...
    Route::get('/dashboard', [HomeController::class, 'Dashboard'])->name('dashboard');
    Route::any('/users', [UserController::class, 'index']);
    Route::get('/create-user', [UserController::class, 'createUser'])->middleware('is_superadmin');
    Route::post('/store_user', [UserController::class, 'storeUser'])->middleware('is_superadmin');;
    Route::get('/edit_user/{id}', [UserController::class, 'editUser'])->middleware('is_superadmin');;
    Route::post('/update_user', [UserController::class, 'updateUser'])->middleware('is_superadmin');;

    //Print Jobs Routes..
    Route::get('/color-size', [UserController::class, 'colorSize']);
    Route::get('/create-color-size/{id}', [UserController::class, 'createColorSize']);
    Route::get('/edit-color-size', [UserController::class, 'editColorSize']);
    Route::get('/edit-shop-color-size/{id}', [UserController::class, 'editShopColorSize']);
    Route::post('/store_color_size', [UserController::class, 'storeColorSize']);
    

    //Shop Routes...
    Route::any('shops', [ShopController::class, 'index']);
    Route::get('create-shop', [ShopController::class, 'createShop'])->middleware('is_superadmin');
    Route::post('store_shop', [ShopController::class, 'storeShop'])->middleware('is_superadmin');
    Route::get('/edit_shop/{id}', [ShopController::class, 'editShop'])->middleware('is_superadmin');
    Route::post('/update_shop', [ShopController::class, 'updateShop'])->middleware('is_superadmin');
    Route::get('delete_shop', [ShopController::class, 'deleteShop'])->middleware('is_superadmin');

    //Print Job Routes...
    Route::get('print_jobs/{shop_id?}', [PrintJobController::class, 'printJobs']);
    Route::get('create-print-job', [PrintJobController::class, 'createPrintJob']);
    Route::post('store_print_job', [PrintJobController::class, 'storePrintJob']);
    Route::any('print-jobs', [PrintJobController::class, 'index']);
    Route::any('cash-order', [PrintJobController::class, 'cashOrder']);
    Route::get('update_status', [PrintJobController::class, 'updateStatus']);

    //Transaction Routes...
    Route::get('transaction', [TransactionController::class, 'index']);
    Route::get('generate-invoice/{id}', [TransactionController::class, 'generateInvoice']);
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::post('/print_request', [PrintRequestsController::class, 'print_request'])->name('print_request');

Route::webhooks('wa')->name('wa');

Route::post('whatsapp', [WAController::class, 'indexPost'])->name('indexPost');

Route::get('whatsapp', [WAController::class, 'index'])->name('whatsapp');


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
Route::get('/success', [HomeController::class, 'success'])->name('success');
Route::post('/submitCode', [HomeController::class, 'submitCode'])->name('submitCode');


Route::get('/page', [HomeController::class, 'page'])->name('page');
Route::get('/arabicCode', [HomeController::class, 'arabicCode'])->name('arabicCode');
Route::get('/arabicDocument', [HomeController::class, 'arabicDocument'])->name('arabicDocument');
Route::get('/arabicPay', [HomeController::class, 'arabicPay'])->name('arabicPay');
Route::get('/arabicProcessing', [HomeController::class, 'arabicProcessing'])->name('arabicProcessing');
Route::get('/arabicShare', [HomeController::class, 'arabicShare'])->name('arabicShare');
Route::post('/arabicSubmitCode', [HomeController::class, 'arabicSubmitCode'])->name('arabicCode');

Route::get('/englishCode', [HomeController::class, 'englishCode'])->name('englishCode');
Route::get('/englishDocument', [HomeController::class, 'englishDocument'])->name('englishDocument');
Route::get('/englishPay', [HomeController::class, 'englishPay'])->name('englishPay');
Route::get('/englishProcessing', [HomeController::class, 'englishProcessing'])->name('englishProcessing');
Route::get('/englishShare', [HomeController::class, 'englishShare'])->name('englishShare');
Route::post('/englishSubmitCode', [HomeController::class, 'englishSubmitCode'])->name('englishCode');
Route::post('/englishQrCode', [HomeController::class, 'englishQrCode'])->name('englishQrCode');
Route::get('/englishQrCode', [HomeController::class, 'englishQrCode'])->name('englishQrCode');
Route::get('/getOptions/{code}', [HomeController::class, 'getOptions'])->name('getOptions');
Route::get('/shop/{id}', [HomeController::class, 'shop'])->name('shop');
Route::post('/select_printer', [PrintRequestsController::class, 'selectPrinter'])->name('printer_select');

Route::post('/submitDocument', [HomeController::class, 'submitDocument'])->name('submitDocument');

Route::get('convert', [ConvertController::class, 'convertToPdf'])->name('convertToPdf');
Route::get('/upload', [UploadController::class, 'uploadview'])->name('upload');
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
Route::get('/uploadShop/{id}', [UploadController::class, 'uploadShop'])->name('uploadShop');

Route::get('/convertPDF', [PrintRequestsController::class, 'convertPDF'])->name('convertPDF');
Route::get('convert/{phone}/{filename}', [HomeController::class, 'convert'])->name('convert');
Route::post('save-pdf', [HomeController::class, 'save_pdf']);


Route::group(['prefix' => 'arabic'], function () {
    Route::get('/code', [HomeController::class, 'arabicCode'])->name('arabicCode');
    Route::get('/document', [HomeController::class, 'arabicDocument'])->name('arabicDocument');
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
