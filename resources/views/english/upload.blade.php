<!-- <head>



    <link rel="stylesheet" href="./css/styleEng.css">



    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>



    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />



</head> -->











@extends('english.main_document')







@section('content')







@section('page', 'document')







@section('title', 'Document')







@php use App\Models\Color_size; @endphp



<body>















    <style>



html{



    font-size: 62.5%;



}



        .name_input {







            width: 100%;







            padding: 10px;







            border: 1px solid #ccc;







            border-radius: 4px;







            font-size: 16px;







        }



        .design{

            font-size: 1.3rem;

            font-weight: 600;

            color: #00448E40;

        }













        .alert {







            padding: 15px;







            background-color: #4CAF50;







            color: white;







            border-radius: 4px;







            margin-bottom: 15px;







        }















        /* Style for the success alert */







        .alert.success {







            background-color: #4CAF50;







        }















        /* Style for the close button */







        .closebtn {







            margin-left: 15px;







            color: white;







            font-weight: bold;







            float: right;







            font-size: 18px;







            line-height: 16px;







            cursor: pointer;







        }















        .closebtn:hover {







            color: black;







        }







        .modal .container .size select {







            width: 14.8rem;







            padding: 0;







            text-align: center;







        }



.UploadFiles{



    height: auto !important;



    min-height: 18.4rem;



}



.dropzone{



    margin-top: 2rem;



    display: none;



}



.dz-image-preview{



      display: none !important;



    }



    .uploaded-image{



        margin: 5px;



      cursor: pointer;



      width: 10.2rem;



      border: 1px solid rgba(128, 128, 128, 0.301);



      height: 10.2rem;



      border-radius: 2rem;



      display: flex;



      justify-content: center;



      align-items: center;



      



    }



    .uploaded-image img{



      max-height: 80%;



      max-width: 80%;



      width: auto;



      min-width: 50%;



    }



    .uploaded-image:hover{

        box-shadow: 5px 5px 5px #8080801c;

    }







    .AllOptions .Option{



        display: none;



    }



    .AllOptions .Option.active{

        

        display: block;

        

    }

    .optionsAccordianButton{

        width: 100%;

    height: 5rem;

    justify-content: space-between;

    background: white;

    display: flex;

    text-transform : capitalize;

    cursor : pointer;

    align-items: center;

    margin-top: 2rem;

    border-radius: 0.8rem;

    border: 1px solid rgba(138, 138, 138, 0.20);

    background: #FFF;

    box-shadow: 0px 2px 14px 0px rgba(0, 68, 142, 0.16);

    font-size: 2rem;

    font-weight: 600;

    color: #8A8A8A;

    padding: 3rem 4.1rem;

}

.AllOptions .Option .accordianMenu{

    display : none

}

.AllOptions .Option .accordianMenu.active{

    display : block

}

    </style>







    @php 







    if(isset($data['pricing']))







    {







        $pricing = $data['pricing'];







    }else{







        if(!empty($shop)){







            $pricing = Color_size::where('shop_id',$shop)->first();







        }else{







            $pricing = Color_size::where('shop_id','0')->first();







        }







       







    }







    @endphp







    @if(isset($data['file']))







        $file = $data['file'];







    @endif







    <section class="document">







        <div class="left">







            <img src="{{ asset('public/assets/img/lines1.png') }}" class="line1" alt="">







            <img src="{{ asset('public/assets/img/lines2.png ') }}" class="line2" alt="">







            <div class="header">







                <img src="{{ asset('public/assets/img/logo.png') }}" alt="">







               







            </div>







            <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">







        </div>







        <div class="right">







            <div class="container">















                <form method="post" action="{{ route('upload') }}" id="form1 " class="form1" enctype="multipart/form-data">







                    @csrf







                    <input type="hidden" name="file"







                        value="@if (isset($file)) @php echo $file; @endphp @endif">







                    <input type="hidden" name="phone"







                        value="@if (isset($phone)) @php echo $phone; @endphp @endif">















                    <input type="hidden" name="code" value="{{ isset($code) ?$code: '' }}">







                    







                    @if(!empty($shop))







                    <input type="hidden" name="shop_id" value="{{ $shop}}">







                    @endif



                    <h2 class="mainHeading">







                        Select Printing Options





                        @if (Session::has('message'))







                            <div class="alert success" style="margin-top: 13px;">







                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>







                                {{ session('message') }}







                            </div>







                        @endif















                    </h2>







                    <div class="box color ">







                        <div class="top">







                            <h3>







                               Upload Document







                            </h3>







                        </div>







                        <div class="wa ">



                        



                            <input  id="fileupload"  type="file" name="file[]" accept=".xls, .xlsx, .docx, .doc, .pdf, .jpeg, .jpg, .png" required>



                            <div class="dropzone" id="myDropzone">



                            </div>



                        </div>







                    </div>







                    <div class="AllOptions">

                    </div>



                    <div class="box copies total">







                        <div class="top">



                        



                            <h3>



                        



                                Total



                        



                            </h3>



                        



                        </div>



                        



                        <div class="input">



                        



                        



                        



                            <p><span class="totalPriceValue">1</span>  SAR</p>



                        



                            <input hidden type="text" id="price" value="" name="price" >



                        



                        



                        



                        </div>



                        



                        </div>



                    <div class="box payment">







                        <div class="top">







                            <h3>







                                Select Payment Options







                            </h3>







                        </div>







                        <div class="input payInput active" onclick="ActiveOption(this, 'payInput', null)">







                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"







                                fill="none">







                                <path







                                    d="M17.0499 20.28C16.0699 21.23 14.9999 21.08 13.9699 20.63C12.8799 20.17 11.8799 20.15 10.7299 20.63C9.28992 21.25 8.52992 21.07 7.66992 20.28C2.78992 15.25 3.50992 7.59003 9.04992 7.31003C10.3999 7.38003 11.3399 8.05003 12.1299 8.11003C13.3099 7.87003 14.4399 7.18003 15.6999 7.27003C17.2099 7.39003 18.3499 7.99003 19.0999 9.07003C15.9799 10.94 16.7199 15.05 19.5799 16.2C19.0099 17.7 18.2699 19.19 17.0399 20.29L17.0499 20.28ZM12.0299 7.25003C11.8799 5.02003 13.6899 3.18003 15.7699 3.00003C16.0599 5.58003 13.4299 7.50003 12.0299 7.25003Z"







                                    class="svgs" />







                            </svg>







                            <p style="margin-bottom:0 ">Apple Pay</p>







                            <input type="checkbox" value="apple" checked name="type" class="checkbox">







                        </div>







                        <div class="input payInput" onclick="ActiveOption(this, 'payInput', null)">







                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18"







                                fill="none">







                                <path







                                    d="M1 9C1 5.229 1 3.343 2.172 2.172C3.343 1 5.229 1 9 1H13C16.771 1 18.657 1 19.828 2.172C21 3.343 21 5.229 21 9C21 12.771 21 14.657 19.828 15.828C18.657 17 16.771 17 13 17H9C5.229 17 3.343 17 2.172 15.828C1 14.657 1 12.771 1 9Z"







                                    class="svgs" stroke-width="1.5" />







                                <path d="M9 13H5M13 13H11.5M1 7H21" class="svgs" stroke-width="1.5"







                                    stroke-linecap="round" />







                            </svg>







                            <p style="margin-bottom:0 ">Credit Card</p>







                            <input type="checkbox" value="online" name="type"  class="checkbox">







                        </div>







                        <div class="input payInput" onclick="ActiveOption(this, 'payInput', null)">







                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"







                                viewBox="0 0 24 24" fill="none">







                                <path







                                    d="M12.0001 7.7143V5.14287M12.0001 7.7143C10.5773 7.7143 9.42871 7.7143 9.42871 9.42858C9.42871 12 14.5716 12 14.5716 14.5714C14.5716 16.2857 13.423 16.2857 12.0001 16.2857M12.0001 7.7143C13.423 7.7143 14.5716 8.36573 14.5716 9.42858M9.42871 14.5714C9.42871 15.8572 10.5773 16.2857 12.0001 16.2857M12.0001 16.2857V18.8572"







                                    class="svgs" stroke-linecap="round" stroke-linejoin="round" />







                                <path







                                    d="M12 23.1429C18.1541 23.1429 23.1429 18.154 23.1429 12C23.1429 5.84597 18.1541 0.857147 12 0.857147C5.846 0.857147 0.857178 5.84597 0.857178 12C0.857178 18.154 5.846 23.1429 12 23.1429Z"







                                    class="svgs" stroke-linecap="round" stroke-linejoin="round" />







                            </svg>







                            <p style="margin-bottom:0 ">Cash</p>







                            <input type="checkbox" name="type" value="cash" class="checkbox"







                                id="cash_order">







                        </div>















                    </div>







                    {{-- <button type="button" onclick="confirmModal(event, true)" class="pay">







                        CONFIRM PAY







                    </button> --}}















                    <button type="button" onclick="confirmModal(event, true)" class="pay">







                        CONFIRM PAY







                    </button>







            </div>







        </div>





        <div class="modal confirmModal " style id="confirmModal">







            <div class="container" style="display: block;">







            <div class="close" onclick="confirmModal(event, false)">







                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"







                        fill="none">







                        <path







                            d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"







                            fill="#00448E" />







                    </svg>







            </div>







                <h2 class="mainHeading">Confirm</h2>























                <div class="row range">







                    <p class="title">Full Name</p>







                    <input type="text" name="name" class="name_input" placeholder="Enter your full name">







                </div>







                <button type="submit">DONE</button>







            </div>







        </div>







        







        @if(session('show_modal'))







        <div class="modal confirmModal  successModal" id="cashOrder" >







            <div class="container" style="margin-top:50px;!important">







            <div class="close" onclick="successModal()">







                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"







                        fill="none">







                        <path







                            d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"







                            fill="#00448E" />







                    </svg>







            </div>







           







                <img src="{{ asset('public/assets/img/lineIcon.png') }}" alt="" style="width: 9.2rem; height: 9.2rem;">























                    <p class="infoP">please visit the counter to pay and collect your print</p>







                <button onclick="successModal()" type="">Ok</button>







        </div>







        @endif















        </form>







    </section>







<style>







    .modal-backdrop.show{







        display : none







    }







</style>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>







    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>







    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.min.js"></script>







    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>















    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"







        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">















    </script>







 <script>







        var sessionValue = "{{ session('key') }}";







         $(document).ready(function(){

             $('#cashOrder').modal('show');

        });







    </script>







    



<script>



    // Initialize Dropzone



    Dropzone.autoDiscover = false; // Prevent Dropzone from auto-discovering forms



    var myDropzone = new Dropzone("#myDropzone", {



      url: "/getTotalPages",



      success: function(file, response) {

        document.getElementById("startRangePrice").innerHTML = response.total_pages;

        document.getElementById("endRangePrice").innerHTML = response.total_pages;

    },

   



      autoProcessQueue: true, // Disable automatic uploading



      maxFiles: 10, // Set a maximum number of files



      clickable: false, // Disable clicking to open the file dialog

      

    



    });

    var i = 0



    console.log(myDropzone);

    



    // Event listener for when a file is added using the input field



    document.getElementById('fileupload').addEventListener('change', function (e) {



        document.querySelector("#myDropzone").style.display = "flex"



       var files = e.target.files;

  

       for (var i = 0; i < files.length; i++) {



        const file = files[i];

        console.log(`Selected file ${i + 1}: ${file.name}`);

      

        myDropzone.addFile(files[i]);



        



        const AllOptions = document.querySelector(".AllOptions")





    const template = document.createElement("template")



    template.innerHTML = `



    <div class="Option Option${i}">



                    <div onclick="OptionAccordian(this, 'Option${i}')" class="optionsAccordianButton">

                    ${files[i].name}

                    <i class="fa-solid fa-chevron-down"></i>

                    </div>

<div class="accordianMenu">

        <div class="box color">







<div class="top">







    <h3>







        Select Color







    </h3>







</div>







<!--  -->























<div class="input mInput   blackwhite active" onclick="totalPriceCalculation('color', 'blackwhite', 'Option${i}'); ActiveOption(this, 'mInput', 'blackwhite', 'Option${i}')">







    <div class="price">















        <p class="title" style="margin-bottom:0;">Black/White</p>







        <p  style="margin-bottom:0;">















            <span id="bwPrice">{{isset($pricing)?$pricing->black_and_white_amount:"0.333"}} </span>SAR







        </p>







    </div>







    <input type="checkbox" name="color${i}" value="false"







        @if (isset($print_job)) checked="{{ !$print_job->color }}" @else checked="true" @endif







        class="checkbox">







</div>







<div class="input mInput" onclick="totalPriceCalculation('color', 'color', 'Option${i}'); ActiveOption(this, 'mInput', 'color', 'Option${i}') ">







    <div class="price">















        <p class="title" style="margin-bottom:0;">Color</p>







        <p style="margin-bottom:0;">







            <span id ="colorPrice">{{isset($pricing)?$pricing->color_page_amount:"2"}} </span>SAR







        </p>







    </div>







    <input type="checkbox" name="color${i}" value="true"







        @if (isset($print_job)) checked="{{ $print_job->color }}" @endif







        class="checkbox">







</div>







<input type="text" class="colorInput" hidden value="blackwhite">















<div class="options" style="cursor : pointer" onclick="Modal(event, 'Option${i}', true)">







    <img src="{{ asset('public/assets/img/plus.png') }}" alt="">







    <p>Advanced Options</p>







</div>







</div>







<div class="box copies">







<div class="top">







    <h3>







        No. Of Copies







    </h3>







</div>























<div class="input">







<button type="button" onclick="Copies('noofcopies', 'Option${i}', 'subt')">-</button>







    







    <p class="noofcopies" style="margin-bottom:0">1</p>







    <input type="hidden" class="noofcopies" value="1" name="copies${i}" id="copies">







    <button type="button" onclick="Copies('noofcopies', 'Option${i}', 'add')" style="border-left : 1px solid rgba(0, 68, 142, 0.25)">+</button>















</div>







</div>

</div>



















































<div class="modal" id="advance_modal" style="    position: fixed;



    width: 100vw;



    height: 100vh;



    top: 0;



    left: 0;">







<div class="container" style="    width: 77rem;



    height: 79rem;



    padding: 7.9rem 13.7rem;">







    <div class="close" onclick="Modal(event, 'Option${i}', false)">







        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"







            fill="none">







            <path







                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"







                fill="#00448E" />







        </svg>







    </div>







    <h2 class="mainHeading">Advanced Options</h2>







    <div class="row">







        <h3 style="padding-left : 0">Select Side</h3>







        <div class="inputs" style="justify-content: space-between; padding-left : 0;">







             <p hidden id="singlePrice${i}">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>







             <p hidden id="doublePrice${i}">{{isset($pricing)?$pricing->double_side:"0.333"}} </p>



             <p   id="startRangePrice${i}"> </p>

             <p hidden  id="endRangePrice${i}"> </p>







             















            <div class="input modalinput active" onclick="totalPriceCalculation('sides', 'oneside', 'Option${i}'); ActiveOption(this, 'modalinput', 'onesided', 'Option${i}')" >







                <p class="title" style="margin-bottom:0">One Sided <span class = "design">( + {{isset($pricing)?$pricing->one_side:"0.333"}} SAR) </span></p>







                <input type="checkbox" name="sides${i}" value="one"







                    @if (isset($print_job)) checked="{{ !$print_job->single_sided }}" @else checked="true" @endif







                    class="checkbox">







            </div>







            <div class="input modalinput" onclick="totalPriceCalculation('sides', 'twoside', 'Option${i}'); ActiveOption(this, 'modalinput', 'twosided', 'Option${i}')">







                <p class="title" style="margin-bottom:0">Two Sided <span class = "design">( + {{isset($pricing)?$pricing->double_side:"0.333"}} SAR)</span></p>







                <input type="checkbox" name="sides${i}" value="two"







                    @if (isset($print_job)) checked="{{ $print_job->double_sided }}"  @endif







                    class="checkbox">







            </div>







        </div>







    </div>



<input type="text" class="sideInput" value="oneside" hidden>



    <div class="row range">







        <h3  style="padding-left : 0">Page Range</h3>















        <div class="input rangeinput active" onclick="ActiveOption(this, 'rangeinput', 'all', 'Option${i}')">







            <p class="title" style="margin-bottom:0">All</p>







            <input type="checkbox" name="range${i}" value="all" checked="true" class="checkbox">







        </div>







        <div class="input  input2">







            <div class="custom rangeinput" onclick="ActiveOption(this, 'rangeinput', 'custom', 'Option${i}')">















                <p class="title">Custom</p>







                <input type="checkbox" name="range${i}" value="custom" class="checkbox">







            </div>







            <div class="pages">







                <div class="from">















                    <p style="margin-bottom:0">From</p>







                    <select class="frompage" name="pages_start${i}" id="startRange" onchange="PageRange(event, 'from')" >







                        <option value="1">1</option>







                        <option value="2">2</option>







                        <option value="4">4</option>







                        <option value="5">5</option>







                    </select>







                </div>







                <div class="to">















                    <p style="margin-bottom:0">To</p>







                    <select name="page_end${i}" class="topage" id="endRange" onchange="PageRange(event, 'to')">







                        <option value="1" selected>1</option>







                        <option value="2" >2</option>







                        <option value="3">3</option>







                        <option value="4">4</option>







                        <option value="5">5</option>







                    </select>







                </div>







            </div>







        </div>







    </div>







    <div class="row size"  style="display : flex; justify-content:space-between;">







        <h3 style="padding-left : 0; width :auto; margin-bottom:0;">Page Size</h3>







        @php $data = unserialize($pricing->size_amount); @endphp







        <select name="page_size${i}" class="page_size" id="" onchange="Page(event, 'page')">







        @foreach ($data as $size => $amount)





            <option  @if($size == 'A4') selected @endif value="{{ $amount }}">{{ $size }} @if($size != 'A4') <span style= " font-weight: 600;!important">( + {{ $amount }} SAR)</span> @endif</option>

            





        @endforeach







            







        </select>







    </div>







    <button type="button" onclick="HideModal('Option${i}');">DONE</button>







</div>







</div>



    `



    AllOptions.appendChild(template.content.firstElementChild)



AllOptions.querySelector(".Option:nth-child(1)").classList.add("active")







    }



    });







    let indexFiles = -1



     myDropzone.on('addedfile', function (file) {



        indexFiles++



      // Display the file in the #uploadedFiles div as an image



      var fileDiv = document.createElement('div');



      var img = document.createElement('img');



      img.src = URL.createObjectURL(file);



      fileDiv.className = 'uploaded-image';



      fileDiv.setAttribute("onclick", `ImageOptions(this, 'Option${indexFiles}')`)



      fileDiv.appendChild(img);



      document.getElementById('myDropzone').appendChild(fileDiv);



    });









const OptionAccordian=(e, MainParent)=>{

    document.querySelector(`.${MainParent} .accordianMenu`).classList.toggle("active")

    if(document.querySelector(`.${MainParent} .accordianMenu`).classList.contains("active")){

        e.querySelector("i").classList.remove("fa-chevron-down")

        e.querySelector("i").classList.add("fa-chevron-up")

    }else{

        e.querySelector("i").classList.add("fa-chevron-down")

        e.querySelector("i").classList.remove("fa-chevron-up")



    }

}





    const ImageOptions = (e,cl)=>{



    console.log(cl)



    const uploadedImages = document.querySelectorAll(".uploaded-image")



    for (let index = 0; index < uploadedImages.length; index++) {



        const element = uploadedImages[index];



        element.style.border = "1px solid rgba(128, 128, 128, 0.301)"

        element.style.boxShadow = "none"

        

        

    }

    

    e.style.border = "1px solid blue"

    e.style.boxShadow = "5px 5px 5px #8080801c"



    document.querySelectorAll(`.Option`).forEach(element => {



        element.classList.remove("active")



    });



    document.querySelector(`.${cl}`).classList.add("active")



}



    // Event listener for when a file is added by dragging and dropping



   



















  </script>



<script>















const successModal = ()=>{







    document.querySelector(".successModal").style.display = "none"







}























const  totalPriceValue = document.querySelector(".totalPriceValue")







const  noofcopies = document.querySelector(".noofcopies")







const  frompage = document.querySelector(".frompage")







const  topage = document.querySelector(".topage")







const  page_size_value = document.querySelector(".page_size")























const totalPriceCalculation = (varname, value, MainParent)=>{







if(varname == "color"){





    // color = value

document.querySelector(`.${MainParent} .colorInput`).value = value

// console.log(document.querySelector(`.${MainParent} .colorInput`).value)





}else if(varname == "sides"){

    

    

    document.querySelector(`.${MainParent} .sideInput`).value = value

    // console.log(document.querySelector(`.${MainParent} .sideInput`).value)



}















}























const calculatePrice = () => {





    



















const AllUploadedImages = document.querySelectorAll(".AllOptions .Option")



let TotalPriceForAllDocuments = 0;

AllUploadedImages.forEach(element => {

    let color = "blackwhite";







let sides = "oneside";

    var numCopies = parseInt(element.querySelector(`.noofcopies`).innerHTML);

    var colorInput = element.querySelector(`.colorInput`);

    var sideInput = element.querySelector(`.sideInput`);

    // console.log(sideInput.value)





    if(colorInput.value == "color"){

color = "color"

    }else{

        color = "blackwhite"

    }



    if(sideInput.value == "oneside"){

        sides = "oneside"

        // console.log(sides)

    }else{

        sides = "twoside"

        // console.log(sides)

    }

    

    

    

    var fromPage = parseInt(element.querySelector(`  .frompage`).value);

    

    

    

    var toPage = parseInt(element.querySelector(` .topage`).value);

    

    

    

    var totalPages = toPage - fromPage + 1;

    











    

    let basePrice = 0;















    if (color === "blackwhite") {







        var bwPrice = document.getElementById("bwPrice").innerHTML;







        basePrice = bwPrice; 







    } else if (color === "color") {







        var colorPrice =document.getElementById("colorPrice").innerHTML;







        basePrice = colorPrice; 







        // console.log(basePrice)







    }







    







    let sidednessMultiplier = 0;







    if (sides === "oneside") {







         sidednessMultiplier = document.getElementById("singlePrice").innerHTML;







        // sidednessMultiplier = 1;







        // console.log("one")







    }else if (sides == "twoside"){







         sidednessMultiplier =document.getElementById("doublePrice").innerHTML;







        // sidednessMultiplier = 2; 







        // console.log("two")







    }

    

    

    var page_size_price  = parseInt(element.querySelector(` .page_size`).value);

    // console.log(element)

    const totalPrice = numCopies * totalPages * (parseInt(basePrice) + parseInt(sidednessMultiplier) + page_size_price);



    TotalPriceForAllDocuments += totalPrice

    console.log(totalPrice)

});

    





    return TotalPriceForAllDocuments;







    // console.log(totalPrice)















};







// calculatePrice()







const updatePrice = (MainParent) => {







    const price = calculatePrice();





    var t = price+'.00'

    totalPriceValue.textContent = t;







    console.log(price)







    







    $("#price").val(price);















};



















        const ActiveOption = (e, input, value, MainParent) => {



            console.log(MainParent)



            let inputs;



            if(MainParent == null){



                inputs = document.querySelectorAll(`.${input}`)



            }else{



                inputs = document.querySelectorAll(`.${MainParent} .${input}`)



            }











            for (let index = 0; index < inputs.length; index++) {







                const element = inputs[index];







                element.classList.remove("active")







                for (let index = 0; index < element.childNodes.length; index++) {







                    const element2 = element.childNodes[index];















                    if (element2.classList != undefined && element2.classList.contains("checkbox")) {







                        element2.checked = false







                    }







                }















            }







            e.classList.add("active")







            for (let index = 0; index < e.childNodes.length; index++) {







                const element = e.childNodes[index];







                if (element.classList != undefined && element.classList.contains("title")) {







                    // element.classList.add("active")







                }







                if (element.classList != undefined && element.classList.contains("checkbox")) {







                    element.checked = true







                }







            }



if(MainParent == null){



    console.log("MainParent is null")



}else{







    updatePrice(MainParent)



}







        }







// let fromPageValue = 1







// let toPageValue = 2















const PageRange = (e, value)=>{







    if(value == "from"){







        fromPageValue = e.target.value







        







    }else{







        toPageValue = e.target.value







    }







    totalPages = parseInt(toPageValue) - parseInt(fromPageValue) + 1







    pages = totalPages







    updatePrice()















}







let pageValue = 1















const Page = (e, value)=>{







   







    if(value == "page"){







        pageValue = e.target.value







        







    }







    // totalPages = parseInt(toPageValue) - parseInt(fromPageValue) + 1







    updatePrice()















}















        const Copies = (e, MainParent, value) => {







            const p = document.querySelector(`.${MainParent} .${e}`)







            const copy = document.querySelector(`.${MainParent} #copies`);







            if (value == "add") {







                p.innerHTML = parseInt(p.innerHTML) + 1







                copy.value = parseInt(copy.value) + 1







                







            } else {







                if (p.innerHTML == 0) return







                p.innerHTML = parseInt(p.innerHTML) - 1







                copy.value = parseInt(copy.value) - 1







                







                







            }







        







            updatePrice(MainParent)















        }























        const Modal = (e,MainParent, value) => {







            const modal = document.querySelector(`.${MainParent} .modal` )







            if (value) {







                modal.style.display = "flex"







            } else {







                modal.style.display = "none"







            }















        }















        const getSelectedPaymentMethod = () => {







            var pay_methods = document.querySelectorAll('input[name="type"]');







            for(pay_method of pay_methods)







            {







                if(pay_method.checked)







                {







                    return pay_method.value;







                }







            }







        }















        const confirmModal = (e, value) => {







            const modal = document.getElementById("confirmModal")







            // document.querySelector(".modal-backdrop").style.display = "none"







            const pay_method = getSelectedPaymentMethod()







            const form = document.querySelector('.form1')



            console.log(document.querySelector('.form1'))







            if(value){















                if (pay_method == 'cash') {







                    modal.style.display = "flex"







                } else {







                    modal.style.display = "none"







                    form.submit()







                }







            }else{







                modal.style.display = "none"







            }















        }















        const HideModal = (MainParent) => {







            const modal = document.querySelector(`.${MainParent} .modal`);







            modal.style.display = "none";







        }







    </script>















    <script>







        var Numpages = 1







        var TotalPrice = 1.00







        // Define variables for PDF loading and rendering







        let pdfDoc = null;







        let pageNum = 1;







        let pageRendering = false;







        let pageNumPending = null;







        let scale = 1.5;







       







       







        















        // Function to load and render the PDF







        const loadPDF = async (url) => {







            try {







                const loadingTask = pdfjsLib.getDocument(url);







                pdfDoc = await loadingTask.promise;















                Numpages = pdfDoc._pdfInfo.numPages







                //renderPage(pageNum);







            } catch (error) {







                console.error("Error loading PDF:", error);







            }







        };















        // Load and render the PDF on page load







        document.addEventListener("DOMContentLoaded", () => {







            const pdfUrl =







                "@if (isset($file)){{ $file }}@endif";







            loadPDF(pdfUrl);







            console.log(pdfUrl);







        });































    </script>







    <script>







    // const startRangeSelect = document.getElementById('startRange');







    // const endRangeSelect = document.getElementById('endRange');





</script>















   













   







</body>







@endsection















{{--







    <section class="w-100 m-auto code">







        <div class="container m-auto">







            @include('english.layouts.top')







            <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">







                @csrf







            <div class="middle d-flex justify-content-center align-items-start">







                <div class="center">







                    <h2>Upload File</h2>















                        <div class="wa d-flex">







                            <input  id="exampleFormControlFile1" multiple type="file" name="file[]" accept=".xls, .xlsx, .docx, .doc, .pdf, .jpeg, .jpg, .png" required>







                        </div>







                </div>







            </div>







            <div class="bottom d-flex justify-content-between align-items-center">







                <a href="{{ route('englishShare') }}" class="backbtn d-flex justify-content-center align-items-center">







                    <







                </a>







                







                    @if(isset($shop))







                        <input type="hidden" name="shop" value="{{ $shop }}">







                    @endif







                    <button type="submit" class="text-white d-flex justify-content-center align-items-center">







                        Continue >







                    </button>







                </form>







            </div>







        </div>







    </section>







--}}