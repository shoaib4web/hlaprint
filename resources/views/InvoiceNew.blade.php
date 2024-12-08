<!DOCTYPE html>
<html dir="rtl">
   <head>
      <title>Arabic Invoice </title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
         * {
         font-family: DejaVu Sans !important;
         }
         body {
         border-style:  solid;
         border-color : grey;
         border:  solid grey;
         border-width: thin;
         margin: 5px;
         font-size: 16px;
         font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
         color: #777;
         }
         body {
         color: #777;
         text-align: right;
         }
         body h1 {
         color: #000;
         }
         body h3 {
         color: #555;
         }
         body a {
         color: #06f;
         }
         @page {
         size: a4;
         margin: 0;
         padding: 0;
         }
         .invoice-box table {
         direction: ltr;
         width: 100%;
         text-align: right;
         border: 1px solid;
         font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
         }
         .column {
         display: block;
         page-break-before: avoid;
         page-break-after: avoid;
         align:center!important;
         }
         table {
         /* font-family: arial,dejavusans,sans-serif; */
         border-collapse: collapse;
         width: 50%;
         }
         td, th {
         border: 1px solid #dddddd;
         text-align: center;
         padding: 2px;
         }
         tr:nth-child(even) {
         background-color: ;
         }
         .center {
         margin-left: auto;
         margin-right: auto;
         }
         table { table-layout: fixed; }
         td { width: 33%; }
         td { width: 20%; }
         p{line-height:5px}
         .page-break{
         page-break-after: always;
         }
         .IMAGE{
            position: relative;
         }
         .logo{
            width : 200px;
            height : auto;
            position : absolute;
         }
         .logo1{
            top : 0;
            left : 0
         }
         .logo2{
            top : 0;
            right : 0
         }
         .logo3{
            top : 722px;
            left : 0
         }
         .logo4{
            top : 722px;
            right : 0
         }
      </style>
   </head>

   @php
   $totalPages = 0;
   foreach ($priceVariation as $variation) {
    $totalPages += array_sum($variation);
}

   @endphp

   <body>
      <div style="display:table; margin-right: auto;
         margin-left: auto;">

         <h1 class="text-darky" style="align-center";margin-bottom:2px;!important >فاتورة # <span>{{$invoice->monthly_id?str_pad($invoice->monthly_id,'0',STR_PAD_LEFT):0;}}</span>
         <h1>
         <p  style="margin-top:0px;align-center!important" >{{$invoiceDetails->name}}</p>
         <p>العنوان: <span>{{$invoiceDetails->address}}</span></p>
         <p>  تاريخ :  <span>{{$invoice->created_at}}</span></p>
         <p>  <span> هاتف: {{getShopContact($invoiceDetails->shop_id)}}</span></p>

         <p class="text-darky">  VAT # : <span>{{$invoiceDetails->vat_number}}</span> </p>
         <p class="text-darky">  إجمالي الصفحات:  <span>{{$totalPages}}</span></p>
      </div>
      <div class="invoice-box">
         <table>
            <tr class="item">
               <th>
                  <h6 class="text-sm text-medium">Sub Total</h6>
               </th>
               <th>
                  <h6 class="text-sm text-medium">Quantity(Pages)</h6>
               </th>
               <th>
                  <h6 class="text-sm text-medium">Price</h6>
               </th>
               <th class="item-name">
                  <h6 class="text-sm text-medium">Option</h6>
               </th>
            </tr>
            @foreach($priceVariation as $item => $pages)
            <tr>
                @php
                $price = invoiceGetPriceOptions($shop_id,$item);
                 @endphp
               <!-- code to get per job price  -->

               <td >

               ر س <span>{{invoiceCalculateSubtotals(collect($pages)->sum(), $price->no_of_pages, $price->base_price)}}</span>
               </td>
               <td><span>{{collect($pages)->sum()}}</span> </td>
               <td ><span>{{$price->base_price}}</span><span>ر س</span> <span>{{$price->no_of_pages}}</span><span>ب</span></td>
               <td style=" text-align: right;">
                {{$price->page_size.",".$price->color_type.",".$price->sidedness}}

               </td>

            </tr>
            @endforeach

            <tr style="line-height:5px;">
               <td> <span> ر س </span><span>{{$transaction->amount??0}}</span> </td>
               <td col-span="3">
                  <h6 class="text-sm text-medium"> Total (with VAT)</h6>
               </td>
               <td style="border:0px;"></td>
               <td style="border:0px;"></td>
            </tr>
         </table>
         <!-- <p>شامل ضريبة القيمة المضافة</p> -->
         <div>
            <div style="display:table; margin-right: auto;
               margin-left: auto; ">
               <!-- <p>السعر الإجمالي (شامل ضريبة القيمة المضافة)</p> -->
               <br >
               <p lang="ar">
                  <img height="60px" width="60px"  src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
               </p>
            </div>
         </div>
      </div>

   </body>
</html>
