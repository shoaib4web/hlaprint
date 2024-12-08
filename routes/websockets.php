<?php
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\Events\NewEvent;
use App\Events\PaymentReceived;
use App\Events\PrintedSuccess;
use App\Events\FilesDownloaded;
use App\Events\RequestRecieved;
use App\Models\Transaction;
use App\Models\PrintJob;
use App\Models\Invoice;

Route::get('/event', function () {
    event(new RequestRecieved(123));

    return 'Message sent Success!';
});

Route::get('/FilesDownloaded/{trans_id}', function ($trans_id)
{
    event(new FilesDownloaded($trans_id));
    return 200;
})->withoutMiddleware(['csrf']);

Route::get('/FilesPrintedSuccessfully/{trans_id}', function ($trans_id)
{
    event(new PrintedSuccess($trans_id));
    return 200;
})->withoutMiddleware(['csrf']);

Route::get('/separator', function() {
 return view('separator');
});

Route::get('/Payment/{trans_id}',[App\Http\Controllers\HomeController::class, 'paymentStart']);

Route::get('/PrintInvoices/{trans_id}/{color?}', function($trans_id,$color = 'all')
{

    $transaction = Transaction::find($trans_id);
    $printjobs_id = json_decode($transaction->print_job_id);
    if($color == 'bw')
    {
        $printjobs = PrintJob::whereIn('id',$printjobs_id)->where('color','false')->get();
    }
    else if($color == 'color')
    {
        $printjobs = PrintJob::whereIn('id',$printjobs_id)->where('color','true')->get();
    }
    else {
        $printjobs = PrintJob::whereIn('id',$printjobs_id)->get();
    }

    $shop_id = $printjobs[0]->shop_id;

    $invoiceDetails = DB::table('invoice_details')->where('shop_id', $shop_id)->first();
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

    $jsonqrCodeContent = "https://hlaprint.com/PrintInvoices/".$transaction->id;//json_encode($qrCodeContent);

    // Generate the QR code
    $qrCode = QrCode::size(150)->generate($jsonqrCodeContent);

    return view("InvoiceNew",compact('priceVariation','transaction','invoiceDetails','qrCode','invoice', 'shop_id'));
});

Route::get('/PrintBWInvoices/{trans_id}', function($trans_id)
{
    $transaction = Transaction::find($trans_id);
    $printjobs_id = json_decode($transaction->print_job_id);
    $printjobs = PrintJob::whereIn('id',$printjobs_id)->where('color', 'false')->get();
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
//WebSocketsRouter::webSocket('/connect', App\WebSocket\WsHandler::class);
