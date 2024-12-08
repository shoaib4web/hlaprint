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











    </style>







</head>















<body>







<div style="display:table; margin-right: auto;

  margin-left: auto;">









@php $inv =explode("-",$invoiceData['invoice']) ;



@endphp

            <h1 class="text-darky" style="align-center";margin-bottom:2px;!important >   {{$invoiceData['invoice']}} فاتورة # <h1>

         <p  style="margin-top:0px;align-center!important" >{{$invoiceData['name']}} </p>

        <p>العنوان:{{$invoiceData['address']}} </p>



            <p>{{$invoiceData['date']}}تاريخ : </p>









        <p><span>{{$invoiceData['phone']}}</span> هاتف:</p>

            <<p class="text-darky"> <span>{{$invoiceData['vat_number']}}</span># VAT</p>

    



   







   



    



    </div>



    







    <div class="invoice-box">







        <table>







            <tr class="item">



            <th>







                <h6 class="text-sm text-medium">المجموع</h6>







                </th>



                <th>







                <h6 class="text-sm text-medium">ضريبة القيمة المضافة</h6>







            </th>





                <th>



                <h6 class="text-sm text-medium">الكمية</h6>



                </th>



                <th class="item-name">



                <h6 class="text-sm text-medium">إسم الصنف</h6>



                </th>



            </tr>



            @foreach($fetchJobs as $jobs)





            <tr>

                <!-- code to get per job price  -->

              

                <td >

                <?php

                 $jobId = $jobs->id;

                 $price = null;

             

                 // Check if the job's 'id' exists in the 'id' array within priceArr

                 $idKey = array_search($jobId, $invoiceData['priceArr']['id']);

             

                 if ($idKey !== false) {

                     $price = $invoiceData['priceArr']['price'][$idKey];

                     $showPrice = $price * 1.15;

                 }

             

                 if ($price !== null) {

                    $showPrice = round( $showPrice, 2);

                    

                    echo'<span> ر س </span>'.$showPrice.'</span>'; 

                }

              ?>



                <!-- ر س<span>100.15</span> -->



                </td>



                <td>{{round($price * 0.15,2)}}</td>



                <td >{{$jobs['copies']}} </td>



                <td style=" text-align: right;">



                خدمة الطباعة



                <br>



                @if($jobs['double_sided'] == 'true')

                <span>(<span> ر س </span>{{$invoiceData['doublePrice']}}.00)</span>  <span>بجانبين </span>



                @else

                <span> (<span> ر س </span>{{$invoiceData['singlePrice']}}.00)</span>     <span>جانب واحد </span>





               



                @endif



                <br>



                @if($jobs['color'] == 'true')



             

               <span> (<span> ر س </span>{{$invoiceData['colorPrice']}}.00)</span>  <span> ملون </span>   





                @else



              



               <span>   (<span> ر س </span>{{$invoiceData['bwPrice']}}.00)</span>   <span>  أحادية اللون </span>





                @endif



                <br>

                



               



                @if($jobs['page_size'])

              

                    @foreach ($invoiceData['size_amount_data'] as $size => $amount)



                    @if($jobs['page_size'] == $size)

                         (<span> ر س </span>{{ $amount}}.00 )</span> <span> {{$jobs['page_size'] }}</span>

                    @endif

                

                    @endforeach



                <!-- {{$jobs['page_size']}} -->



                @else

                    @foreach ($invoiceData['size_amount_data'] as $size => $amount)



                        @if($size == "A4")

                         <span>(<span> ر س </span>{{ $amount}}.00)</span><span> A4</span>

                         @else

                            <span>(<span> ر س </span>{{ $amount}}.00)</span><span> {{$size}}</span>

                        @endif



                



                    @endforeach

                @endif



                </td>



                



               







            </tr>



            @endforeach



            <tr style="line-height:5px;">

            

            <td> <span> ر س </span><span>{{$invoiceData['amount']}}</span> </td>    



            <td col-span="3"> <h6 class="text-sm text-medium"> المبلغ الإجمالي</h6></td>

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







            <img height="60px" width="60px"  src="data:image/png;base64,{{ base64_encode($invoiceData['qrCode']) }}" alt="QR Code">







            </p>



        </div>







        </div>







    </div>







</body>















</html>







