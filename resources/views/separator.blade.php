<!DOCTYPE html>
<html dir="rtl">
   <head>
      <title>Arabic Separator </title>
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

  
 
   <body>
      
      <div class="IMAGE">
         <img class="logo logo1" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
         <img class="logo logo2" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
         <img class="logo logo3" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
         <img class="logo logo4" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
      </div>

      <div class="page-break">

      </div>
      <div class="IMAGE">
         <img class="logo logo1" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
         <img class="logo logo2" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
         <img class="logo logo3" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
         <img class="logo logo4" src="{{asset('public/assets/img/invoiceLogo.png')}}" alt="Logo">
      </div>
   </body>
</html>