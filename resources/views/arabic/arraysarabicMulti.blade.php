@extends('arabic.main_document')
@section('content')
@section('page', 'document')
@section('title', 'Hla Print')
@php use App\Models\Color_size; @endphp
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

@php
$colorPageAmount = 0;
$bwPageAmount = 0;
$colorPrice = 0.0;
$bwPrice = 0.0;


foreach ($priceOptions as $option) {
    if ($option->page_size == 'A4' && $option->color_type == 'color' && $option->sidedness == 'oneSide') {
        $colorPageAmount = $option->no_of_pages;
        $colorPrice = (float)$option->base_price;
    } elseif ($option->page_size == 'A4' && $option->color_type == 'mono' && $option->sidedness == 'oneSide') {
        $bwPageAmount = $option->no_of_pages;
        $bwPrice = (float)$option->base_price;
    }
}

@endphp

<body>
    @if(isset($cashResponse))
    {{$cashResponse}}
    @endif
    <style>
        /* Loader container */
        .loader-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* White background with opacity */
            z-index: 9999; /* Ensure the loader appears above other content */
        }

        /* Loader */
        .loader {
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 10rem;
            height: 10rem;
            animation: spin 2s linear infinite;
            position: absolute;
            top: calc(50% - 5rem);
            left: calc(50% - 5rem);
        }

        /* Loader text */
        .loader-text {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            text-align: center;
            position: absolute;
            top: calc(50% + 10rem); /* Adjust to position text below the loader */
            left: 50%;
            transform: translateX(-50%);
            z-index: 100000; /* Ensure the text appears above the loader */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .UploadFiles .content{
            height: auto !important;
            min-height: 18.4rem;
        }

        button.disabled {
            background-color: #ccc !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .dropzone {
            margin-top: 0;
            display: none;
            border: 0;
            min-height: auto;
            flex-wrap: wrap;
            padding: 0;
            justify-content: flex-end;
        }

        .dz-image-preview,
        .dz-preview,
        .dz-file-preview,
        .dz-processing,
        .dz-complete {
            display: none !important;
        }

        #myDropzone>div.dz-default.dz-message {
            display: none;
        }

        .uploaded-image {
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

        .uploaded-image img {
            max-height: 100%;
            max-width: 100%;
            width: auto;
            min-width: 50%;
        }

        .uploaded-image:hover {
            box-shadow: 5px 5px 5px #8080800f;
        }

        .AllOptions .Option {
            display: none;
        }
        .AllOptions .Option .btn.save{
            padding: 1.5rem 2.5rem;
            font-size: 2.6rem;
    background: #007FFF;
    color: white;
    border-radius: .8rem;
    margin-top: 1.5rem;
    margin-left: auto;
    display: block;
        }

        .AllOptions .Option.active {
            display: block;
        }

        #myDropzone {
            display: flex;
        }

        .document .right .box.uploadFiles .top {
            margin-bottom: 1.4rem;
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
        }

        .document .right .box.uploadFiles .top .buttons {
            display : flex;
        }
        .document .right .box.uploadFiles .top .buttons .selectAll:nth-child(1){
            margin-right: 1rem;
        }
        .document .right .box.uploadFiles .top .selectAll {
            padding: 1rem 2rem;
            cursor: pointer;
            border: 1px solid #007FFF;
            font-size: 1.6rem;
            background: white;
            border-radius: 1rem;
        }
        .document .right .box.uploadFiles .top .selectAll.active {
            background : #007FFF;
            color : white
        }

        .uploadFilesModal .container .modal .container {
            display: block !important
        }
    </style>
    </div>
    <!-- Loader container -->
    <div class="loader-container" id="loader-container">
        <!-- Loader -->
        <div class="loader" id="loader"></div>
        <div class="loader-text">الملفات تحت المعالجة</div>
    </div>
    <script>
       $(document).ready(function() {
        var repeat = false;
            function checkIfTwoSecondsPassed() {
        repeat = true;
        // Add your code here to execute after 2 seconds
    }

    setTimeout(checkIfTwoSecondsPassed, 3000);
    // Function to make AJAX call and handle response
    function makeAjaxCall() {
        // Show loader when AJAX call starts
        $('#loader-container').show();

        // Make AJAX call
        $.ajax({
            url: '/checkFiles/{{$code}}', // Replace with your route
            method: 'GET',
            success: function(response) {
                // Handle response
                console.log(response);
                // Example: If response is true, hide loader and stop repeating the request
                if (response === true) {
                    $('#loader').hide();
                    $('#loader-container').hide();
                    clearInterval(interval);
                    if(repeat)
                    {
                        location.reload();
                    } // Stop repeating the request
                }
            },
            error: function(xhr, status, error) {
                // Hide loader when AJAX call fails
                $('#loader').hide();
                console.error(xhr.responseText);
            }
        });
    }

    // Call makeAjaxCall() initially
    makeAjaxCall();

    // Repeat the AJAX call every 2 seconds
    var interval = setInterval(makeAjaxCall, 2000); // 2000 milliseconds = 2 seconds
});
    </script>
    <section class="document">
        <div class="left">
            <img src="{{ asset('public/assets/img/lines1.png') }}" class="line1" alt="">
            <img src="{{ asset('public/assets/img/lines2.png ') }}" class="line2" alt="">
            <div class="header">
                <!-- <img src="{{ asset('public/assets/img/logo.png') }}" alt=""> -->
                @if($shop)
                <a href="{{route('getOptions',[$code,$shop])}}" class="btn">English</a>
                @else
                <a href="{{route('getOptions',[$code])}}" class="btn">English</a>
                @endif
            </div>
            <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">
        </div>
        <div class="right">
            <div class="uploadFilesModal">
                <div class="container screen1 active">
                    <div>
                        <button class="close" id="close_upload">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                    fill="#00448E" />
                            </svg>
                        </button>
                    </div>
                    <div class="uploadBox">
                        <img src="{{ asset('public/assets/img/upload.png')}}" alt="">
                        <p>قم بتحميل الملف الخاص بك هنا</p>
                        <button type="button">رفع
                            <input id="fileupload" mutliple type="file" name="file[]"
                                accept=".xls, .xlsx, .docx, .doc, .pdf, .jpeg, .jpg, .png" required>
                        </button>
                    </div>
                    <div class="uplaodedFilesDisplay">
                    </div>
                    <button type="button" class="next disabled" id="nextBtn_upload"
                        onclick="nextUploadScreen('screen1', `Option1`, `UploadModal1`)">التالي</button>
                </div>
                <script>
                </script>
                <div class="container screen2 Op0 UploadModal0">
                    <div class="box color">
                        <div class="top">
                            <h3>
                                إختر لون
                            </h3>
                        </div>
                        <div id="bw" class="input mInput   bwcolor blackwhite active"
                            onclick="ActiveOption(this, 'UploadModal0', 'mInput')">
                            <div class="price">
                                <p class="title">أسود / أبيض</p>
                                <p id="bwPrice" style="display:flex"><span
                                        style="margin-right : .4rem;font-size: 1.3rem;font-weight: 600;color: #007FFF40;">رس</span>{{$bwPageAmount}}
                                    ورقة &nbsp; ب <span style="margin-left:0.1em">
                                        {{$bwPrice}}</span>
                            </div>
                            <input type="checkbox" name="color[]" value="false" class="checkbox" checked>
                        </div>
                        <div id="color" class="input mInput  colored"
                            onclick="ActiveOption(this,'UploadModal0', 'mInput')">
                            <div class="price">
                                <p class="title">ملون</p>
                                <p id="colorPrice" style="display:flex"><span style="margin-right : .4rem">رس</span>
                                    {{$colorPageAmount}} ورقة&nbsp; ب <span
                                        style="margin-left:0.1em"> {{$colorPrice}}</span>
                                </p>
                            </div>
                            <input type="checkbox" name="color[]" value="true" class="checkbox">
                        </div>
                        <div class="options" onclick="Modal(this,'UploadModal0', true)">
                            <img src="{{ asset('public/assets/img/plus.png')}}" alt="">
                            <p>خيارات متقدمة</p>
                        </div>
                    </div>
                    <div class="box copies">
                        <div class="top">
                            <h3>
                                عدد النسخ
                            </h3>
                        </div>
                        <div class="input">
                            <button type="button" id="inc"
                                onclick="Copies('noofcopies','UploadModal0', 'add')">+</button>
                            <p class="noofcopies" style="margin-bottom:0">1</p>
                            <input type="hidden" class="" value="1" name="copies[]" id="copies">
                            <button type="button" id="dec"
                                onclick="Copies('noofcopies','UploadModal0', 'subt')">-</button>
                        </div>
                    </div>
                    <!-- <div class="box copies Alltotal">
                            <div class="top">
                                <h3>
                                All totaal  المجموع
                                </h3>
                            </div>
                            <div class="input">
                                <p id="Alltotal" class="AlltotalPriceValue">
                                1.00
                                </p>
                                <span  style="margin-right : .4rem"> </span>
                            </div>
                        </div> -->
                    <div class="box copies total">
                        <div class="top">
                            <h3>
                            <span style="font-size:1rem">( للاختيار الحالي )</span> المجموع
                            </h3>
                        </div>
                        <div class="input">
                            <p class="totalPriceValue">1</p> <span style="margin-right : .4rem">رس</span>
                        </div>
                    </div>
                    <button type="button" class="next save"
                        onclick="nextUploadScreen('screen2'); SetOptionsValues(this)">
                        يحفظ
                    </button>
                    <div class="modal" style="position: fixed; width: 100vw; height: 100vh; top: 0px; left: 0px; ">
                        <div class="container">
                            <div class="close" onclick="Modal(this,'UploadModal0', false)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                        fill="#00448E"></path>
                                </svg>
                            </div>
                            <h2 class="mainHeading">خيارات متقدمة</h2>
                            <p style="display:none;" id="singlePrice${i}">{{isset($pricing)?$pricing->one_side:"0.33"}}
                            </p>
                            <p style="display:none;" id="doublePrice${i}">
                                {{isset($pricing)?$pricing->double_side:"0.33"}} </p>
                            <div class="row">
                                <h3>حدد جانب</h3>
                                <div class="inputs">
                                    <div class="input modalinput   oneSide active "
                                        onclick="totalPriceCalculation('sides', 'oneside');ActiveOption(this,'UploadModal0', 'modalinput')">
                                        <p class="title">من جانب واحد {{isset($pricing)?$pricing->one_side:"0.333"}} .00
                                            ريال </p>
                                        <input type="checkbox" name="sides[]" value="one" class="checkbox" checked>
                                    </div>
                                    <div class="input modalinput   twoSide"
                                        onclick="totalPriceCalculation('sides', 'twoside');ActiveOption(this,'UploadModal0', 'modalinput')">
                                        <p class="title">وجهان {{isset($pricing)?$pricing->double_side:"0.333"}} ريال
                                        </p>
                                        <input type="checkbox" name="sides[]" value="two" class="checkbox">
                                    </div>
                                </div>
                            </div>
                            <div class="row range">
                                <p style="display:none;" id="singlePrice">{{isset($pricing)?$pricing->one_side:"0.33"}}
                                </p>
                                <p style="display:none;" id="doublePrice">
                                    {{isset($pricing)?$pricing->double_side:"0.33"}}</p>
                                <p style="display:none;" id="optionStartRangePrice"> </p>
                                <p style="display:none;" n id="optionEndRangePrice"> </p>
                                <h3>نطاق الصفحات</h3>
                                <div class="input rangeinput  active"
                                    onclick="ActiveOption(this,'UploadModal0', 'rangeinput')">
                                    <p class="title">الجميع</p>
                                    <input type="checkbox" class="checkbox" checked>
                                </div>
                                <div class="input  input2">
                                    <div class="custom rangeinput "
                                        onclick="ActiveOption(this,'UploadModal0', 'rangeinput')">
                                        <p class="title">مخصص</p>
                                        <input type="checkbox" class="checkbox">
                                    </div>
                                    <div class="pages">
                                        <div class="from">
                                            <p>من</p>
                                            <input hidden id="fromOptions" name="from[]" value="1">
                                            <select name="fromOptions" class="frompage" id="startRange"
                                                onchange="PageRange(event, 'from')">
                                            </select>
                                        </div>
                                        <div class="to">
                                            <p>ل</p>
                                            <input hidden id="toOptions" name="to[]" value="1">
                                            <select name="to[]" class="topage" id="endRange"
                                                onchange="PageRange(event, 'to')">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row size">

                                @php $collection = collect($priceOptions);
$uniquePageSizes = $collection->pluck('page_size')->unique();@endphp
                                <input hidden class="   pageInputSize" name="pageSize[]" value="A4">
                                <h3>مقاس الصفحه</h3>
                                <select name="page_size[]" class="Page" id="pageSize" onchange="Page(event, 'page')">
                                    @foreach ($uniquePageSizes as $size)
                                    <option @if($size=='A4' ) selected @endif value="A4">{{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="classbtn" type="button"
                                onclick="Modal(this,'UploadModal0', false)">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container ">
                <!-- <form action="" class="dropzone" id="my-awesome-dropzone">
                    <input type="file" name="file" id="uploadFiles">
                </form> -->
                <form method="post" action="{{ route('aSubmitDocument') }}" id="form1">
                    @csrf
                    <input type="hidden" name="file" id="WFile"
                        value="@if (isset($file)) @php echo $file; @endphp @endif">
                    <input type="hidden" name="phone" value="@if (isset($phone)) @php echo $phone; @endphp @endif">
                    <input type="hidden" name="code" value="{{ isset($code) ?$code: '' }}">
                    @if(!empty($shop))
                    <input type="hidden" name="shop_id" value="{{ $shop}}">
                    @endif
                    <h2 class="mainHeading">
                        حدد خيارات الطباعة
                        <!--<div class="wa d-flex">-->
                        <!--<button  type="button" onclick="UploadFilesModal()" type="button"> -->
                        <!--<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">-->
                        <!--<path d="M8 1C6.15042 1.02231 4.38287 1.76698 3.07493 3.07493C1.76698 4.38287 1.02231 6.15042 1 8C1.02231 9.84958 1.76698 11.6171 3.07493 12.9251C4.38287 14.233 6.15042 14.9777 8 15C9.84958 14.9777 11.6171 14.233 12.9251 12.9251C14.233 11.6171 14.9777 9.84958 15 8C14.9777 6.15042 14.233 4.38287 12.9251 3.07493C11.6171 1.76698 9.84958 1.02231 8 1ZM12 8.5H8.5V12H7.5V8.5H4V7.5H7.5V4H8.5V7.5H12V8.5Z" fill="#007FFF"/>-->
                        <!--</svg>   إضافة ملف</button>-->
                        <!--</div>-->
                    </h2>
                    <div class="box uploadFiles" style="display : block" >


                    <div class="content">
                        <div class="top">
                            <h3>إختيار المستندات</h3>
                            <div class="buttons">

                                <div class="selectAll" onclick="SelectAllOptions()">
                                إختر الكل
                                </div>
                                <div class="selectAll selectSome" onclick="SelectSomeOptions()">
                                حدد البعض
                                </div>
                            </div>
                        </div>
                        @php
                        $fileCount = 0;
                        @endphp
                        <div class="dropzone "  id="myDropzone">
                            @foreach($printJob as $job)

                            <div class="File FileOption{{$fileCount}}"
                            onclick="ImageOptions(this,'Option{{$fileCount}}')">
                                <img src="{{ getThumbnail($job->id)?getThumbnail($job->id):asset('public/assets/img/pdf.png')}}" style="width: 2.5rem;" alt="">
                                <!-- Whatsapp File {{$fileCount + 1 }} -->
                                <p> {{GetFileName($job->id)?GetFileName($job->id):'Whatsapp Media'}}</p>
                                 <span id="deleteJob" class="del" style="margin-left:0.4rem" ><img class="del" data-job-id="{{$job->id}}"
                                        src="{{ asset('public/assets/img/delete.png')}}"></span>
                            </div>
                            @php
                            $fileCount += 1;
                            @endphp
                            @endforeach
                        </div>
                    </div>
                    <div class="scrollbar-track" id="scrollbarTrack">
      <div class="scrollbar-arrow up" id="scrollbarArrowUp">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"></path></svg>
      </div>
      <div class="scrollbar-thumb" id="scrollbarThumb"></div>
      <div class="scrollbar-arrow down" id="scrollbarArrowDown">
      <svg style="transform : rotate(180deg)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"></path></svg>
      </div>
    </div>
                    </div>
                    <div class="AllOptions greyed">

                        @php
                        $count = 0;
                        @endphp
                        @foreach($printJob as $job)

                        <div class="Option Option{{$count}} @if(!$count) {{ 'active' }} @endif">
                            <div class="box color">
                                <div class="top">
                                    <h3>
                                        إختر لون
                                    </h3>
                                </div>
                                <div class="input mInput  blackwhite active"
                                    onclick=" totalPriceCalculation('color', 'blackwhite', 'Option{{$count}}');ActiveOption(this, 'Option{{$count}}', 'mInput')">
                                    <div class="price">
                                        <p class="title">أسود / أبيض
                                        <img src="{{ asset('public/assets/img/bw.jpg')}}" class="icon" alt="">
                                        </p>
                                        <p style="display : flex"><span style="margin-right : .4rem">رس</span>
                                            {{$bwPrice}} ورقة &nbsp; ب <span
                                                style="margin-left:0.1em">
                                                {{$bwPageAmount}}</span> </p>
                                    </div>
                                    <input type="checkbox" name="color[]" value="false" class="checkbox" checked>
                                </div>
                                <div class="input mInput"
                                    onclick=" totalPriceCalculation('color', 'color', 'Option{{$count}}');ActiveOption(this,'Option{{$count}}', 'mInput')">
                                    <div class="price">
                                        <p class="title">ملون
                                    <img src="{{ asset('public/assets/img/color.png')}}" class="icon" alt="">
                                        </p>
                                        <p style="display : flex"><span style="margin-right : .4rem">رس</span>
                                            {{$colorPrice}} ورقة &nbsp; ب <span
                                                style="margin-left:0.1em">
                                                {{$colorPageAmount}}</span></p>
                                    </div>
                                    <input type="checkbox" name="color[]" value="true" class="checkbox">
                                </div>
                                <input  type="hidden" name="colorType" id="colorType" value="blackwhite">
                                <div class="options" onclick="Modal(this,'Option{{$count}}', true)">
                                    <img src="{{ asset('public/assets/img/plus.png')}}" alt="">
                                    <p>خيارات متقدمة</p>
                                </div>
                            </div>
                            <div class="box copies">
                                <div class="top">
                                    <h3>
                                        عدد النسخ
                                    </h3>
                                </div>
                                <div class="input">
                                    <button type="button"
                                        onclick="Copies('noofcopies','Option{{$count}}', 'add')">+</button>
                                    <p class="noofcopies">1</p>
                                    <button type="button"
                                        onclick="Copies('noofcopies','Option{{$count}}', 'subt')">-</button>
                                    <input type="hidden" class="noofcopies" value="1" name="copies[]" id="copies">
                                </div>
                            </div>
                            <div class="box copies total" style="display:">
                                <div class="top">
                                    <h3>
                                    <span style="font-size:1rem">( للاختيار الحالي )</span>المجموع
                                    </h3>
                                </div>
                                <div class="input">
                                    <p style="display:flex;" class="totalPriceValue">
                                        1.15
                                    </p>
                                    <span style="margin-right : .4rem">رس</span>
                                </div>
                            </div>
                            <div class="modal" style="    position: fixed;
                                width: 100vw;
                                height: 100vh;
                                top: 0;
                                left: 0;">
                                <div class="container">
                                    <div class="close" onclick="Modal(this,'Option{{$count}}', false)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                                fill="#00448E" />
                                        </svg>
                                    </div>
                                    <!-- <h2 class="mainHeading">Advanced Optionss</h2> -->
                                    <p style="display:none;" id="singlePrice${i}">
                                        {{isset($pricing)?$pricing->one_side:"0.333"}} </p>
                                    <p style="display:none;" id="doublePrice${i}">
                                        {{isset($pricing)?$pricing->double_side:"0.333"}} </p>
                                    <p style="display:none;" id="bwCopy">{{isset($pricing)?$pricing->bw_copies:"3"}}
                                    </p>
                                    <p style="display:none;" id="colorCopy">
                                        {{isset($pricing)?$pricing->color_copies:"1"}} </p>
                                    <p style="display:none;" id="bwCopiesPrice">
                                        {{isset($pricing)?$pricing->bw_copies_price:"1"}} </p>
                                    <p style="display:none;" id="colorCopiesPrice">
                                        {{isset($pricing)?$pricing->color_copies_price:"1"}} </p>
                                    <div class="row">
                                        <h3>حدد جانب</h3>
                                        <div class="inputs">
                                            <div class="input modalinput  active"
                                                onclick="totalPriceCalculation('sides', 'oneside', 'Option{{$count}}'); ActiveOption(this,'Option{{$count}}', 'modalinput')">
                                                <p class="title"> جانب واحد
                                                    </p>
                                                <input type="checkbox" name="sides[]" value="one" class="checkbox"
                                                    checked>
                                            </div>

                                            <div class="input modalinput "
                                                onclick="totalPriceCalculation('sides', 'twoside', 'Option{{$count}}'); ActiveOption(this,'Option{{$count}}', 'modalinput')">
                                                <p class="title">وجهان </p>
                                                <input type="checkbox" name="sides[]" value="two" class="checkbox">
                                            </div>
                                            <input type="hidden" id="sidesType" name="sidesType" value="oneside">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h3> إتجاه الصفحة</h3>
                                        <div class="inputs">
                                        <div class="input orientationInput  active"
                                                onclick="ActiveOption(this,'Option{{$count}}', 'orientationInput')">
                                                <p class="title"> auto
                                                    </p>
                                                <input type="checkbox" name="orientation[]" value="auto" class="checkbox"
                                                    checked>
                                            </div>
                                            <div class="input orientationInput  "
                                                onclick="ActiveOption(this,'Option{{$count}}', 'orientationInput')">
                                                <p class="title"> طولي
                                                    </p>
                                                <input type="checkbox" name="orientation[]" value="portrait" class="checkbox"
                                                    >
                                            </div>

                                            <div class="input orientationInput "
                                                onclick=" ActiveOption(this,'Option{{$count}}', 'orientationInput')">
                                                <p class="title"> عرضي </p>
                                                <input type="checkbox" name="orientation[]" value="landscape" class="checkbox">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row range">
                                        <p hidden id="singlePrice">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>
                                        <p hidden id="doublePrice">{{isset($pricing)?$pricing->double_side:"0.333"}}
                                        </p>
                                        @php
                                        $range = range(1, $job->total_pages);
                                        $start = $range[0];
                                        $end = end($range);
                                        @endphp
                                        <p hidden data-total-pages='{{$job->total_pages}}' id="startRangePrice">{{$start}} </p>
                                        <p hidden id="endRangePrice">{{$end}} </p>

                                        <h3>نطاق الصفحات</h3>
                                        <div class="input rangeinput  active"
                                            onclick="ActiveOption(this,'Option{{$count}}', 'rangeinput')">
                                            <p class="title">الجميع</p>
                                            <input type="checkbox" class="checkbox" checked>
                                        </div>
                                        <div class="input  input2">
                                            <div class="custom rangeinput "
                                                onclick="ActiveOption(this,'Option{{$count}}', 'rangeinput')">
                                                <p class="title">مخصص</p>
                                                <!-- <p>من</p><p>ل</p> -->
                                                <input @if($job->total_pages <= '1' ) disabled @endif type="checkbox"
                                                    value="custom" class="checkbox">
                                            </div>
                                            <div class="pages">
                                            <div class="to">
                                                    @if($job->total_pages <= '1' ) <input hidden name="to[]" value="1">
                                                        @endif
                                                        <p style="margin-bottom:0">ل </p>
                                                        <select @if($job->total_pages <= '1' ) disabled @endif
                                                                name="to[]" class="topage" id="endRange"
                                                                onchange="PageRange(event, 'to','Option{{$count}}',{{$job->total_pages}})">
                                                                @for($i=1; $i<=$job->total_pages ; $i++)
                                                                    @if($i==$job->total_pages)
                                                                    <option value="{{$i}}" selected>{{$i}}</option>
                                                                    @else
                                                                    <option value="{{$i}}">{{$i}}</option>
                                                                    @endif
                                                                    @endfor
                                                        </select>
                                                </div>
                                                <div class="from">
                                                    <p style="margin-bottom:0">من </p>
                                                    @if($job->total_pages <= '1' ) <input hidden name="from[]"
                                                        value="1">
                                                        @endif
                                                        <select @if($job->total_pages <= '1' ) disabled @endif
                                                                class="frompage" name="from[]" id="startRange"
                                                                onchange="PageRange(event, 'from', 'Option{{$count}}',{{$job->total_pages}})">
                                                                @for($i=1; $i<=$job->total_pages ; $i++)
                                                                    <option value="{{$i}}"> {{$i}} </option>
                                                                    @endfor
                                                        </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row size">

                                        @php $collection = collect($priceOptions);
$uniquePageSizes = $collection->pluck('page_size')->unique();@endphp
                                        <h3>مقاس الصفحه</h3>
                                        <input type="hidden" class="mInput pageInputSize" name="pageSize[]" value="A4">
                                        <select name="page_size[]" class="page_size" id="pageSize"
                                            onchange="Page(event, 'page','Option{{$count}}')">
                                            @foreach ($uniquePageSizes as $size )
                                            <option @if($size=='A4' ) selected @endif value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" onclick="Modal(this,'Option{{$count}}', false)">تأكيد</button>
                                </div>
                            </div>
                            <div class="btn save" onclick="SaveModal(this,'Option{{$count}}', true)">
                            تأكيد
                            </div>
                            <div class="modal saveModal" style="    position: fixed;
                                width: 100vw;
                                height: 100vh;
                                top: 0;
                                left: 0;">
                                <div class="container" style="font-size: 2rem;
                                height: auto;
                                text-align: center;">
                                    <div class="close" onclick="SaveModal(this,'Option{{$count}}', false)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                                fill="#00448E" />
                                        </svg>
                                    </div>
                                    تأكيد الخيارات ؟
                                    <button type="button" onclick="SaveModal(this,'Option{{$count}}', false)">تأكيد</button>
                                </div>
                            </div>
                        </div>
                        @php
                        $count += 1;
                        @endphp
                        @endforeach
                        <div class="Option AllSelectedOptions ">
                            <div class="box color">
                                <div class="top">
                                    <h3>
                                        إختر لون
                                    </h3>
                                </div>
                                <div class="input mInput  blackwhite active"
                                    onclick=" totalPriceCalculation('color', 'blackwhite', 'AllSelectedOptions');ActiveOption(this, 'AllSelectedOptions', 'mInput')">
                                    <div class="price">
                                        <p class="title">أسود / أبيض
                                        <img src="{{ asset('public/assets/img/bw.jpg')}}" class="icon" alt="">

                                        </p>
                                        <p style="display : flex"><span style="margin-right : .4rem">رس</span>
                                            {{$bwPageAmount}} ورقة &nbsp; ب <span style="margin-left:0.1em">{{$bwPrice}}</span> </p>
                                    </div>
                                    <input type="checkbox" name="color[]" value="false" class="checkbox" checked>
                                </div>
                                <div class="input mInput"
                                    onclick=" totalPriceCalculation('color', 'color', 'AllSelectedOptions');ActiveOption(this,'AllSelectedOptions', 'mInput')">
                                    <div class="price">
                                        <p class="title">ملون
                                        <img src="{{ asset('public/assets/img/color.png')}}" class="icon" alt="">

                                        </p>
                                        <p style="display : flex"><span style="margin-right : .4rem">رس</span>
                                            {{$colorPageAmount}} ورقة &nbsp; ب <span style="margin-left:0.1em">{{$colorPrice}}</span></p>
                                    </div>
                                    <input type="checkbox" name="color[]" value="true" class="checkbox">
                                </div>
                                <input type="hidden" name="colorType" id="colorType" value="blackwhite">
                                <div class="options" onclick="Modal(this,'AllSelectedOptions', true)">
                                    <img src="{{ asset('public/assets/img/plus.png')}}" alt="">
                                    <p>خيارات متقدمة</p>
                                </div>
                            </div>
                            <div class="box copies">
                                <div class="top">
                                    <h3>
                                        عدد النسخ
                                    </h3>
                                </div>
                                <div class="input">
                                    <button type="button"
                                        onclick="Copies('noofcopies','AllSelectedOptions', 'add')">+</button>
                                    <p class="noofcopies">1</p>
                                    <button type="button"
                                        onclick="Copies('noofcopies','AllSelectedOptions', 'subt')">-</button>
                                    <input type="hidden" class="noofcopies" value="1" name="copies[]" id="copies">
                                </div>
                            </div>
                            <div class="box copies total" style="display:">
                                <div class="top">
                                    <h3>
                                    <span style="font-size:1rem">( للاختيار الحالي )</span>المجموع
                                    </h3>
                                </div>
                                <div class="input">
                                    <p style="display:flex;" class="totalPriceValue">1.15</p>
                                    <span style="margin-right : .4rem">رس</span>
                                </div>
                            </div>
                            <div class="modal" style="    position: fixed;
                                width: 100vw;
                                height: 100vh;
                                top: 0;
                                left: 0;">
                                <div class="container">
                                    <div class="close" onclick="Modal(this,'AllSelectedOptions', false)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                                fill="#00448E" />
                                        </svg>
                                    </div>
                                    <!-- <h2 class="mainHeading">Advanced Optionss</h2> -->
                                    <!-- <p style="display:none;" id="singlePrice${i}">0</p>
                                    <p style="display:none;" id="doublePrice${i}">1</p>
                                    <p style="display:none;" id="bwCopy">3</p>
                                    <p style="display:none;" id="colorCopy">1</p>
                                        <p style="display:none;" id="bwCopiesPrice">1</p>
                                    <p style="display:none;" id="colorCopiesPrice">2</p> -->
                                    <div class="row">
                                        <h3>حدد جانب</h3>
                                        <div class="inputs">
                                            <div class="input modalinput  active"
                                                onclick="totalPriceCalculation('sides', 'oneside', 'AllSelectedOptions'); ActiveOption(this,'AllSelectedOptions', 'modalinput')">
                                                <p class="title"> جانب واحد
                                                    </p>
                                                <input type="checkbox" name="sides[]" value="one" class="checkbox"
                                                    checked>
                                            </div>
                                            <div class="input modalinput "
                                                onclick="totalPriceCalculation('sides', 'twoside', 'AllSelectedOptions'); ActiveOption(this,'AllSelectedOptions', 'modalinput')">
                                                <p class="title">وجهان
                                                    </p>
                                                <input type="checkbox" name="sides[]" value="two" class="checkbox">
                                            </div>
                                            <input type="hidden" id="sidesType" name="sidesType" value="oneside">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <h3> إتجاه الصفحة</h3>
                                        <div class="inputs">
                                        <div class="input orientationInput  active"
                                                onclick="ActiveOption(this,'Option{{$count}}', 'orientationInput')">
                                                <p class="title"> auto
                                                    </p>
                                                <input type="checkbox" name="orientation[]" value="auto" class="checkbox"
                                                    checked>
                                            </div>

                                            <div class="input orientationInput  "
                                                onclick="ActiveOption(this,'Option{{$count}}', 'orientationInput')">
                                                <p class="title"> طولي
                                                    </p>
                                                <input type="checkbox" name="orientation[]" value="portrait" class="checkbox"
                                                    >
                                            </div>

                                            <div class="input orientationInput "
                                                onclick=" ActiveOption(this,'Option{{$count}}', 'orientationInput')">
                                                <p class="title">عرضي </p>
                                                <input type="checkbox" name="orientation[]" value="landscape" class="checkbox">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row range greyed">
                                        <p hidden id="singlePrice">0</p>
                                        <p hidden id="doublePrice">1</p>
                                        <!-- @php
                                        $range = range(1, $job->total_pages);
                                        $start = $range[0];
                                        $end = end($range);
                                        @endphp -->
                                        <p hidden id="startRangePrice">1 </p>
                                        <p hidden id="endRangePrice">1 </p>
                                        <h3> (Please Select a Single file)   نطاق الصفحات</h3>
                                        <div class="input rangeinput  active "
                                            onclick="ActiveOption(this,'AllSelectedOptions', 'rangeinput')">
                                            <p class="title" >الجميع</p>
                                            <input type="checkbox" class="checkbox" checked>
                                        </div>
                                        <div class="input  input2">
                                            <div class="custom rangeinput "
                                                onclick="ActiveOption(this,'AllSelectedOptions', 'rangeinput')">
                                                <p class="title">مخصص</p>
                                                <!-- <p>من</p><p>ل</p> -->
                                                <input type="checkbox" value="custom" class="checkbox" disabled>
                                            </div>
                                            <div class="pages">
                                                <div class="from">
                                                    <p style="margin-bottom:0">من</p>
                                                    <input hidden name="from[]" value="1" >

                                                    <select disabled>

                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>

                                                    </select disabled>
                                                </div>
                                                <div class="to">
                                                    <input hidden name="to[]" value="1">

                                                    <p style="margin-bottom:0">ل</p>
                                                    <select disabled>

                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row size">

                                        @php $collection = collect($priceOptions);
$uniquePageSizes = $collection->pluck('page_size')->unique();@endphp
                                        <h3>مقاس الصفحه</h3>
                                        <input type="hidden" class="mInput pageInputSize" name="pageSize[]" value="A4">
                                        <select name="page_size[]" class="page_size" id="pageSize"
                                            onchange="Page(event, 'page','Option{{$count}}')">
                                            @foreach ($uniquePageSizes as $size )
                                            <option @if($size=='A4' ) selected @endif value="{{ $size }}">{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button"
                                        onclick="Modal(this,'AllSelectedOptions', false)">تأكيد</button>
                                </div>
                            </div>
                            <div class="btn save" onclick="SaveModal(this,'AllSelectedOptions', true)">
                            تأكيد
                            </div>
                            <div class="modal saveModal" style="    position: fixed;
                                width: 100vw;
                                height: 100vh;
                                top: 0;
                                left: 0;">
                                <div class="container" style="font-size: 2rem;
                                height: auto;
                                text-align: center;">
                                    <div class="close" onclick="SaveModal(this,'AllSelectedOptions', false)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                                fill="#00448E" />
                                        </svg>
                                    </div>
                                    تأكيد الخيارات ؟
                                    <button type="button" onclick="SaveModal(this,'AllSelectedOptions', false)">تأكيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="box copies Alltotal">-->
                    <!--            <div class="top">-->
                    <!--                <h3>-->
                    <!--                المجموع-->
                    <!--                </h3>-->
                    <!--            </div>-->
                    <!--            <div class="input">-->
                    <!--                <p id="Alltotal" style="display:flex" class="AlltotalPriceValue">-->
                    <!--                1.00-->
                    <!--                </p>-->
                    <!--                <span  style="margin-right : .4rem"> </span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <div class="box greyed payment totaldocprice">
                        <div class="top">
                            <h3>
                                <span style="font-size:1rem">(شامل ضريبة القيمة المضافة)</span> المجموع
                            </h3>
                        </div>
                        <div class="input ">
                            <p style="display:flex;" class="totalDocPriceValue">
                                1.00
                            </p>
                            <span style="margin-right : .4rem">رس</span>
                        </div>
                    </div>
                    <div class="box greyed payment disabled">
                        <div class="top">
                            <h3>
                                خيارات الدفع
                            </h3>
                        </div>
                        <div class="input payInput" onclick="ActiveOption(this,'Option0', 'payInput')">
                            <img src="{{ asset('public/assets/img/applepay.jpg')}}" alt="" style="width : 2.4rem">
                            <p><span>( مدى فقط )                            </span>Apple Pay </p>
                            <input type="checkbox" name="type" value="applepay" class="checkbox">
                        </div>
                        <div class="input payInput" onclick="ActiveOption(this,'Option0',  'payInput')">
                            <img src="{{ asset('public/assets/img/mada.jpg')}}" alt="" style="width : 2.4rem">

                            <p> بطاقة مدى </p>
                            <input type="checkbox" name="type" value="card" class="checkbox">
                        </div>
                        <div class="input payInput" onclick="ActiveOption(this,'Option0','payInput')">
                            <img src="{{ asset('public/assets/img/cash.png')}}" alt="" style="width : 2.4rem">

                            <p>كاش او فيزا لدى الكاشير</p>
                            <input type="checkbox" name="type" value="cash" class="checkbox" id="cash_order">
                        </div>
                    </div>
                    <!--<input type="hidden" name="type" value="online"  >-->
                    <button class="pay greyed" disabled="true" id="pay" type="submit" onclick="confirmModal(event, true);disable(this);"
                        disabled='true'>
                        تأكيد الدفع
                    </button>
                </form>
                </form>
                @if(session()->has('modal_code'))
                {!! session('modal_code') !!}
                @endif
                <div class="modal confirmModal" id="confirmModal">
                    <div class="container" style="display: block;">
                        <div class="close" onclick="closeCash(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"
                                    fill="#00448E" />
                            </svg>
                        </div>
                        <h2 class="mainHeading">نقدي</h2>
                        <div class="row range">
                            <p class="title">الاسم الكامل</p>
                            <input type="text" name="name" class="name_input" placeholder="Enter your full name">
                        </div>
                        <button type="submit" onclick="confirmSubmit()">يتابع</button>
                    </div>
                </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src={{asset("public/assets/js/pricing.js")}}></script>



<!-- CUSTOM SCROLLBAR CODE -->
<script>
    // Get elements
    var content = document.querySelector('.content');
    var scrollbarTrack = document.getElementById('scrollbarTrack');
    var scrollbarThumb = document.getElementById('scrollbarThumb');
    console.log(content)
    // Set initial scrollbar thumb height based on content and container height
    function updateThumbHeight() {
        var thumbHeight = (content.clientHeight / content.scrollHeight) * scrollbarTrack.clientHeight;
        scrollbarThumb.style.height = thumbHeight + 'px';
    }

    updateThumbHeight(); // Call once on load

    // Event listeners for dragging functionality (both mouse and touch)
    var isDragging = false;
    var startY = 0;
    var startThumbTop = 0;

    scrollbarThumb.addEventListener('mousedown', startDrag);
    scrollbarThumb.addEventListener('touchstart', function (e) {
        startDrag(e.touches[0]);
    });

    document.addEventListener('mousemove', handleDrag);
    document.addEventListener('touchmove', function (e) {
        handleDrag(e.touches[0]);
    });

    document.addEventListener('mouseup', endDrag);
    document.addEventListener('touchend', endDrag);

    function startDrag(e) {
        isDragging = true;
        startY = e.clientY || e.touches[0].clientY;
        startThumbTop = scrollbarThumb.offsetTop;
    }

    function handleDrag(e) {
        if (!isDragging) return;

        var currentY = e.clientY || e.touches[0].clientY;
        var deltaY = currentY - startY;

        var newThumbTop = startThumbTop + deltaY;
        newThumbTop = Math.max(0, Math.min(newThumbTop, scrollbarTrack.clientHeight - scrollbarThumb.clientHeight));

        var scrollPercentage = newThumbTop / (scrollbarTrack.clientHeight - scrollbarThumb.clientHeight);
        content.scrollTop = scrollPercentage * (content.scrollHeight - content.clientHeight);

        scrollbarThumb.style.top = newThumbTop + 'px';
    }

    function endDrag() {
        isDragging = false;
    }

    // Update scrollbar thumb height and position when content is resized
    window.addEventListener('resize', function () {
        updateThumbHeight();
        content.dispatchEvent(new Event('scroll')); // Trigger scroll event to update thumb position
    });

    // Update scrollbar thumb position when content is scrolled
    content.addEventListener('scroll', function () {
        var scrollPercentage = content.scrollTop / (content.scrollHeight - content.clientHeight);
        var newThumbTop = scrollPercentage * (scrollbarTrack.clientHeight - scrollbarThumb.clientHeight);
        scrollbarThumb.style.top = newThumbTop + 'px';
    });

    // Scroll up when the up arrow is clicked
    scrollbarArrowUp.addEventListener('click', function () {
        content.scrollBy({
            top: -50,
            behavior: 'smooth'
        });
        console.log(content.scrollTop)
      });

      // Scroll down when the down arrow is clicked
      scrollbarArrowDown.addEventListener('click', function () {
        content.scrollBy({
          top: 50,
          behavior: 'smooth'
        });
        console.log(content.scrollTop)
    });

    // Scroll to a specific position when clicking on the track
    scrollbarTrack.addEventListener('click', function (e) {
      if(e.target.classList.contains("scrollbar-track")){

        var clickY = e.clientY - scrollbarTrack.getBoundingClientRect().top;
        var scrollPercentage = clickY / scrollbarTrack.clientHeight;
        content.scrollTo({
          top: scrollPercentage * (content.scrollHeight - content.clientHeight),
          behavior: 'smooth'
        });
      }
    });
</script>

    <script>

const paymentInput = document.querySelectorAll(".payment .payInput")
paymentInput.forEach(element => {
    element.addEventListener("click", ()=>{
        if(element.classList.contains("active") && document.querySelectorAll("#myDropzone .File.Saved").length == document.querySelectorAll("#myDropzone .File").length){

            document.querySelector("#pay").disabled = false;
        }
    })
});




const SaveModal = (e, MainParent, value)=>{
    const modal = document.querySelector(`.${MainParent} .saveModal`)
    if(value){
        modal.style.display = "flex"
    }else{

        modal.style.display = "none"
        const activeFiles = document.querySelectorAll("#myDropzone .File.active")

        activeFiles.forEach(element => {
            element.classList.add("Saved")
            if(element.querySelector(".checkmark")){

            }else{
            const template = document.createElement("template")
                template.innerHTML = `
                <img class="checkmark" style="width : 2.2rem; height:2.2rem;    top: -1.1rem;
                position: absolute;
                left: -1.1rem;" src="{{ asset('public/assets/img/checkmark.png')}}"/>`
                element.appendChild(template.content.firstElementChild)
            }
            element.classList.remove("active")

            });
           if(activeFiles.length == document.querySelectorAll("#myDropzone .File").length){
               document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()

               if(document.querySelector(".payment.disabled")){
                   document.querySelector(".payment.disabled").classList.remove("disabled")
                }
            }
            selectSome = false
            document.querySelector(".selectAll").classList.remove("active")
            document.querySelector(".selectSome").classList.remove("active")
    }
}



        var pricingArray = [];
        var priceOptions = @json($priceOptions);
        for(var i = 0; i < priceOptions.length; i++)
        {
            var option = priceOptions[i];
            pricingArray.push(new PriceOptions(option.page_size, option.color_type, option.sidedness, option.no_of_pages, option.shop_id, option.base_price));
        }
        console.log(pricingArray);


        let SelectedOptionsValues = []
        const AllOptionsVariation = document.querySelectorAll(".Option")
        AllOptionsVariation.forEach((element, index) => {
            let variation;
            let pageSize = "A4"
            const color = element.querySelector("#colorType").value
            const sides = element.querySelector("#sidesType").value

            variation = `${pageSize}_${color == "blackwhite" ? "mono" : "color"}_${sides == "oneside" ? "oneSide" : "doubleSided"}`
            if(element.classList.contains("AllSelectedOptions")){
                SelectedOptionsValues.push({option : `AllSelectedOptions `,variation})
            }else{
                SelectedOptionsValues.push({option : `Option${index}`,variation})
            }
        });
        console.log(SelectedOptionsValues)

        const changeVariation = (option, pageSize,color,sides)=>{

            const filter = SelectedOptionsValues.filter(x=>x.option.trim() == option)[0]
            filter.variation = `${pageSize ? pageSize : filter.variation.split("_")[0]}_${color ? color : filter.variation.split("_")[1]}_${sides ? sides : filter.variation.split("_")[2]}`

            const matchedvariation = pricingArray.filter((x)=>x.variation_id == filter.variation)
            console.log(SelectedOptionsValues)



        }




    </script>

    <script>
        const AllFiles = document.querySelectorAll(".File p")
        AllFiles.forEach(element => {
            if(element.innerText.length > 8){
                const newtext = element.innerText.substr(0,7)
                element.innerText = newtext + "..." + element.innerText.substr(-3)
            }
        });
        let selectAll = false
        let selectSome = false
        const SelectAllOptions = ()=>{
            selectSome = false
            document.querySelector(".selectSome").classList.remove("active")
            const greyed = document.querySelectorAll(".greyed")
            greyed.forEach(element => {
                if(!element.classList.contains('range'))
                    element.classList.remove("greyed")




            });
            selectAll = !selectAll
            const Options = document.querySelectorAll(".Option")
            const FileButtons = document.querySelectorAll(".dropzone .File")
            const thisButton = document.querySelector(".selectAll")
            if(selectAll){

                FileButtons.forEach(element => {
                    element.classList.add("active")
                });
                Options.forEach(element => {
                    element.classList.remove("active")
                });
                document.querySelector(".AllSelectedOptions").classList.add("active")
                thisButton.classList.add("active")
            }else{
                FileButtons.forEach(element => {
                    element.classList.remove("active")
                });
                FileButtons[0].classList.add("active")
                Options[0].classList.add("active")
                document.querySelector(".AllSelectedOptions").classList.remove("active")
                thisButton.classList.remove("active")

            }
        }

        let SelectedOptions = []
        const SelectSomeOptions = ()=>{
            selectAll = false
            document.querySelector(".selectAll:not(.selectSome)").classList.remove("active")
            selectSome = !selectSome
            const Options = document.querySelectorAll(".Option")
            const FileButtons = document.querySelectorAll(".dropzone .File")
            const thisButton = document.querySelector(".selectSome")
            if(selectSome){
                SelectedOptions = []

                FileButtons.forEach(element => {
                    element.classList.remove("active")
                });
                Options.forEach(element => {
                    element.classList.remove("active")
                });
                document.querySelector(".AllSelectedOptions").classList.add("active")
                thisButton.classList.add("active")
            }else{
                FileButtons.forEach(element => {
                    element.classList.remove("active")
                });
                FileButtons[0].classList.add("active")
                Options[0].classList.add("active")
                document.querySelector(".AllSelectedOptions").classList.remove("active")
                thisButton.classList.remove("active")

            }
        }
        const AllMInputs = document.querySelectorAll(".mInput")
        for (let index = 0; index < AllMInputs.length; index++) {
            const element = AllMInputs[index];
            if (element.classList.contains("active")) {
                element.querySelector("input").checked = true
            }
        }
        // const AllPrice=0;
        const noofcopies = document.querySelector(".noofcopies")
        const frompage = document.querySelector(".frompage")
        const topage = document.querySelector(".topage")
        const page_size_value = document.querySelector(".page_size")
        var AllPrice = 0;
        let color = "blackwhite";
        let sides = "oneside";
        const totalPriceCalculation = (varname, value, MainParent) => {
            if (varname == "color") {
                document.querySelector(`.${MainParent} input[name="colorType"]`).value = value


                    changeVariation(MainParent,null, value.trim() == "color" ? "color" : "mono", null)
                    // console.log("updated", SelectedOptionsValues)




            } else if (varname == "sides") {
                document.querySelector(`.${MainParent} input[name="sidesType"]`).value = value

                    changeVariation(MainParent,null, null, value.trim() == "oneside" ? "oneSide" : "doubleSided")
                    // console.log("updated", SelectedOptionsValues)


                // console.log(value)
            }
        }
        const disable = (element) => {
            element.setAttribute('disabled', true);
        }
        const calculatePrice = (MainParent) => {
            // console.log('original parent',MainParent)
            if (MainParent == 'payInput') {
                MainParent = 'option0'
            }
            // console.log('copies parent',MainParent)
            // console.log(MainParent)
            const numCopies = document.querySelector(`.${MainParent}  #copies `)


            const noofcopiesValue = parseInt(numCopies.value)

            const fromPage = parseInt(document.querySelector(`.${MainParent}  #startRangePrice`).innerHTML);
            const toPage = parseInt(document.querySelector(`.${MainParent} #endRangePrice`).innerHTML);
            const totalPages = toPage - fromPage + 1;

            // const numCopies = document.querySelector(`.${MainParent}  #copies `)
            const getPageSize = document.querySelector(`.${MainParent}  #pageSize `)
            // console.log('getPageSize',getPageSize.value)
            const page_size_price = getPageSize.value

            // get dynamic prices
            var bwCopy = parseInt(document.getElementById("bwCopy").innerHTML);
            var colorCopy = parseInt(document.getElementById("colorCopy").innerHTML);
            var bwPrice = parseInt(document.getElementById("bwCopiesPrice").innerHTML);
            var colorPrice = parseInt(document.getElementById("colorCopiesPrice").innerHTML);
            //    console.log("bwCopy",bwCopy)
            //    console.log("colorCopy",colorCopy)
            const MatchedAllOptionsValues = SelectedOptionsValues.filter(x=>x.option.trim() == MainParent)[0]
            const MatchPriceArray = pricingArray.filter(x=>x.variation_id == MatchedAllOptionsValues.variation)[0]
            // console.log(MatchPriceArray)
            var doc_pages = totalPages;
            var doc_copies = noofcopiesValue;

            var doc_TotalPages = doc_pages * doc_copies ;
            // formula apply
            var totalPrice = 0;
            var count = 0;
            if(MatchPriceArray){

                do{
                    doc_TotalPages -= MatchPriceArray.noOfPages;
                    count++
                }
                while(doc_TotalPages > 0);

                var totalPrice = count * MatchPriceArray.basePrice;
            }
            // if (doc_type == 2) {
            //     // 3>0
            //     while (doc_TotalPages > 0) {
            //         doc_TotalPages -= MatchPriceArray.noOfPages;
            //         count++;
            //     }
            //     console.log(totalPrice)
            //     totalPrice += parseFloat(page_size_price) + parseFloat(sidednessMultiplier);
            //     // console.log("totalPrice",totalPrice)
            // }
            // if (doc_type == 1) {
            //     while (doc_TotalPages > 0) {
            //         doc_TotalPages -= bwCopy;
            //         count++;
            //     }
            //     var totalPrice = count * bwPrice;
            //     totalPrice += parseFloat(page_size_price) + parseFloat(sidednessMultiplier);
            //     // console.log(sidednessMultiplier, page_size_price)
            //     // console.log("totalPrice",totalPrice)
            // }
            var temp = totalPrice
            // console.log(temp)

            return { temp: temp.toFixed(2) };
        };

        const calculatingByVariation = (total_pages,noOfPages,basePrice) =>
        {
            var totalPrice = 0;
            var count = 0;
            basePrice = parseFloat(basePrice)
            // console.log(basePrice)
            do{
                total_pages -= noOfPages;
                count++
            }
            while(total_pages > 0);
            var totalPrice = count * basePrice;
            return totalPrice
        }


        const GetAllDocumentPrice = (MainParent) => {

            var AllDocsTotalPrice = 0
            const matchedPriceArray = SelectedOptionsValues.filter(x=>x.option.trim() == "AllSelectedOptions")[0]
            // const matchVariation = pricingArray.filter(x=>x.variation_id == matchedPriceArray.variation)

            const groupedByVariation = SelectedOptionsValues.reduce((accumulator, currentValue) => {
                // If the variation key doesn't exist, initialize it
                if (!accumulator[currentValue.variation]) {
                    accumulator[currentValue.variation] = [];
                }

                // Push the option into the appropriate variation array
                accumulator[currentValue.variation].push(currentValue.option);

                return accumulator;
            }, {});

        console.log(groupedByVariation);
        const variations = groupedByVariation;

        var totalPriceForAll = 0;

        for (const variation in variations) {
            if (variations.hasOwnProperty(variation)) {
                const matchVariation = pricingArray.filter(x=>x.variation_id == variation);
               console.log('Variation:', variation);
                console.log('Options:', variations[variation]);
                var total_pages = 0;
                // Example: iterating over options array
                variations[variation].forEach(option => {

                    if(option == 'AllSelectedOptions ')
                    {
                        return;
                    }

                    const fromPage = parseInt(document.querySelector(`.${option}  #startRangePrice`).innerHTML);
                    const toPage = parseInt(document.querySelector(`.${option} #endRangePrice`).innerHTML);
                    const totalPages = toPage - fromPage + 1;

                    const numCopies = document.querySelector(`.${option}  #copies `)
                    const noofcopiesValue = parseInt(numCopies.value)

                    total_pages += totalPages* noofcopiesValue
                    // console.log(`Total pages ${total_pages}`)

                });
                if(variations[variation].length == 1 && variations[variation][0] == 'AllSelectedOptions ')
                {
                    continue;
                }
                else {
                    totalPriceForAll += calculatingByVariation(total_pages,matchVariation[0].noOfPages,matchVariation[0].basePrice)
                    // console.log(`Total Price ${totalPriceForAll}`)
                }


            }
            console.log(SelectedOptionsValues)
        }




            const Options = document.querySelectorAll(".Option:not(.AllSelectedOptions)")
            const AllSelectedOptions = document.querySelector(".AllSelectedOptions").classList.add(`Option${Options.length }`)

            // console.log(Options)
            // Options.forEach(element => {
            //     AllDocsTotalPrice += parseFloat(element.innerText)
            // });
            // console.log(AllDocsTotalPrice)
            // console.log(Options)

            let bwpages = 0
            let colorpages = 0
            Options.forEach((element, index) => {
                // console.log(index)
                let { basePrice,sidednessMultiplier } = calculatePrice(`Option${index}`);
                sidednessMultiplier = parseInt(sidednessMultiplier)
                // console.log(sidednessMultiplier)

                // console.log(basePrice)
                // console.log(totalPrice)
                const fromPage = parseInt(element.querySelector("#startRangePrice").innerHTML)
                const toPage = parseInt(element.querySelector("#endRangePrice").innerHTML)
                const noofcopies = parseInt(element.querySelector(".noofcopies").innerHTML)
                let totalPages = toPage - fromPage + 1;
                // console.log("number of pages", fromPage , toPage )
                // if (totalPages == 0) {
                //     totalPages = 1
                // }
                // if(basePrice == 1){
                //     totalPages = totalPages * noofcopies + sidednessMultiplier
                //     bwpages += totalPages

                // }else if (basePrice == 2){
                //     totalPages = totalPages * noofcopies * basePrice + sidednessMultiplier
                //     colorpages += totalPages
                // }
                totalPages = totalPages * noofcopies
                AllDocsTotalPrice += totalPages
                // console.log(totalPages)
            });
            AllDocsTotalPrice = totalPriceForAll*1.15;
            // const fullSets = Math.floor(bwpages / 3);
            // const remainderPages = bwpages % 3;
            // const totalCost = fullSets * 1.15 + (remainderPages > 0 ? 1.15 : 0);
            // AllDocsTotalPrice += totalCost
            // console.log(AllDocsTotalPrice)
            // console.log(basePrice)
            return AllDocsTotalPrice.toFixed(2)
        }
        // console.log(GetAllDocumentPrice())
        document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()
        let getPrice = 0;
        const CopyTotalPrice = () => {
            // parents
            const alltotalPriceValue = document.querySelectorAll(`.Option .totalPriceValue`);
            // console.log('alltotalPriceValue', alltotalPriceValue)
            var getPrice = 0;
            for (let index = 0; index < alltotalPriceValue.length; index++) {
                const element = alltotalPriceValue[index];
                var price = element.innerHTML;
                getPrice = getPrice + parseFloat(price);
            }
            const alltotalPriceValueSET = document.querySelectorAll(` #Alltotal`)
            for (let index = 0; index < alltotalPriceValueSET.length; index++) {
                const element = alltotalPriceValueSET[index];
                element.textContent = getPrice.toFixed(2);
            }
        }
        const updatePrice = (MainParent) => {
            const totalPriceValue = document.querySelector(`.${MainParent}  .totalPriceValue `)
            const { temp } = calculatePrice(MainParent);
            // console.log(temp)
            const price = temp
            var t = price;

            totalPriceValue.textContent = t;

            $("#price").val(price);
            const alltotalPriceValue = document.querySelectorAll(`.${MainParent} #Alltotal`)
            // console.log('alltotalPriceValue', alltotalPriceValue)
            for (let index = 0; index < alltotalPriceValue.length; index++) {
                const element = alltotalPriceValue[index];
                element.textContent = price;
            }
            CopyTotalPrice();

        };
        updatePrice("Option0");
        // }
        // updatePrice()
        let fromPageValue = 1
        let toPageValue = 2
        const PageRange = (e, value, option, totalDocPages) => {

            const startSelect = document.querySelector(`.${option}  #startRange`);
            const endSelect = document.querySelector(`.${option}  #endRange`);
            var startValue = parseInt(startSelect.value);
            var endValue = parseInt(endSelect.value);

            if (value == 'from' && startValue > endValue) {
            // If the start range is greater than the end range, adjust end range
            endSelect.value = startValue;

        } else if (value == 'to' && endValue < startValue) {
            // If the end range is less than the start range, adjust start range
            startSelect.value = endValue;
        }

        const startRange = document.querySelector(`.${option} #startRangePrice`);
        const endRange = document.querySelector(`.${option} #endRangePrice`);
        startRange.innerHTML = startValue;
        endRange.innerHTML = endValue;



            totalPages = parseInt(toPageValue) - parseInt(fromPageValue) + 1
            pages = totalPages
            updatePrice(option)
        }
        let pageValue = 1
        const Page = (e, value, MainParent) => {
            console.log('test page', e)
            var name = e.target.options[e.target.selectedIndex];
            const pageInputSize = document.querySelector(`.${MainParent}  .pageInputSize `)
            pageInputSize.setAttribute('value', name.innerHTML)
            if (value == "page") {
                pageValue = e.target.value
            }
            changeVariation(MainParent, document.querySelector(`.${MainParent}  .pageInputSize `).value.split("(")[0].trim(), null, null )
            updatePrice(MainParent)

            console.log(SelectedOptionsValues)

            if(MainParent == "AllSelectedOptions" && !selectSome){
            const allOptions = document.querySelectorAll(".Option:not(.AllSelectedOptions)")

            allOptions.forEach((element, index) => {
                const pageInputSizeValue = document.querySelector(".AllSelectedOptions .pageInputSize").value
                element.querySelector(".pageInputSize").value = pageInputSizeValue
                changeVariation(`Option${index}`, pageInputSizeValue.split("(")[0].trim(), null, null )
            updatePrice(`Option${index}`)

        });
    }else if(MainParent == "AllSelectedOptions" && selectSome){

        SelectedOptions.forEach((element, index) => {
            const pageInputSizeValue = document.querySelector(".AllSelectedOptions .pageInputSize").value
            document.querySelector(`.${element} .pageInputSize`).value = pageInputSizeValue
            changeVariation(`Option${index}`, pageInputSizeValue.split("(")[0].trim(), null, null )
            updatePrice(`Option${index}`)
            });
            }
            document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()
            updatePrice(MainParent)

        }
    </script>

    <script>

                document.addEventListener('click', function(event) {
                    // Check if the clicked element has the class 'del' (delete icon)
                    console.log('Click event triggered');
                    if (event.target.classList.contains('del')) {
                        // Prevent the default action of the click event
                        event.preventDefault();
                        console.log("deletebutton clicked");

                        // Extract the job ID from the data-job-id attribute
                        var jobId = event.target.dataset.jobId;

                        // Show confirmation dialog
                        if (confirm('هل أنت متأكد أنك تريد حذف هذا البند؟')) {
                            // Perform deletion
                            deleteMy(event, "option0", jobId);
                        } else {
                            // Cancel deletion
                            console.log('Deletion canceled.');
                        }
                    }
                });

        function deleteMy(e, option, printJob_id) {
            // Ajax call to remove Print Job Id from DB
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Assuming you're using jQuery for Ajax, you can do it like this:
            $.ajax({
                type: 'POST', // or 'GET' depending on your server setup
                url: '/removePrintJob', // Replace with the actual server endpoint
                data: {
                    printJobId: printJob_id,
                    _token: csrfToken
                }, // Send the printJob_id as data
                success: function (response) {
                    // on Success: Remove Options and Element
                    if (response.success) {
                        // Remove the options or element as needed
                        // For example, if you want to remove a <div> element with a specific ID:
                        // $('#yourElementId').remove();
                        // var options = document.querySelectorAll(`.Option.${option}`);
                        // for (opt of options) {
                        //     opt.remove();
                        // }
                        // document.querySelector(`.File.File${option}`).remove();
                        // document.querySelector(".Option.Option0").classList.add('active');
                        // document.querySelector(".File.FileOption0").classList.add('active');
                        location.reload();
                    }
                },
                error: function () {
                    // on Failure: Do nothing or handle the error
                    alert("error");
                }
            });
        }
        const nextUploadScreen = (value, options = null, modal) => {
            if (value == "screen1" && options) {
                document.getElementById("nextBtn_upload").classList.add('disabled');
                var bw_select = document.querySelector('.screen2 #bw');
                document.querySelector('.screen2').classList.replace("Op0", `${options}`);
                updatePrice(options)
                document.querySelector('.screen2').classList.replace("UploadModal0", `${modal}`);
                var color_select = document.querySelector('.screen2 #color');
                var options_btn = document.querySelector('.screen2 .options');
                var options_close_btn = document.querySelector('.screen2 .modal .close');
                var options_close_btn2 = document.querySelector('.screen2 .modal .classbtn');
                var increase_button = document.querySelector('.screen2 #inc');
                var decrease_button = document.querySelector('.screen2 #dec');
                var AllmInputs = document.querySelectorAll('.screen2 .mInput');
                var AllSelect = document.querySelector(".page_size");
                for (let index = 0; index < AllSelect.length; index++) {
                    const element = AllSelect[index];
                    element.setAttribute('onchange', "Page(this,page,'" + options + "')");
                }
                // console.log('AllmInputs',AllmInputs)
                for (let index = 0; index < AllmInputs.length; index++) {
                    const element = AllmInputs[index];
                    element.setAttribute('onclick', "ActiveOption(this, '" + options + "', 'mInput')");
                }
                // get color inputs to add function
                var AllmBWColorInputs = document.querySelectorAll('.screen2  .bwcolor');
                for (let index = 0; index < AllmBWColorInputs.length; index++) {
                    const element = AllmBWColorInputs[index];
                    element.setAttribute('onclick', "totalPriceCalculation('color', 'blackwhite'); ActiveOption(this, '" + options + "', 'mInput')");
                }
                var AllmColorInputs = document.querySelectorAll('.screen2  .colored');
                for (let index = 0; index < AllmColorInputs.length; index++) {
                    const element = AllmColorInputs[index];
                    element.setAttribute('onclick', "totalPriceCalculation('color', 'color'); ActiveOption(this, '" + options + "', 'mInput')");
                }
                var AllmSideInputs = document.querySelectorAll('.screen2  .oneSide');
                for (let index = 0; index < AllmSideInputs.length; index++) {
                    const element = AllmSideInputs[index];
                    element.setAttribute('onclick', "totalPriceCalculation('sides', 'one'); ActiveOption(this, '" + options + "', 'mInput')");
                }
                var AllmtwoSideInputs = document.querySelectorAll('.screen2  .twoSide');
                for (let index = 0; index < AllmtwoSideInputs.length; index++) {
                    const element = AllmtwoSideInputs[index];
                    element.setAttribute('onclick', "totalPriceCalculation('sides', 'twoside'); ActiveOption(this, '" + options + "', 'mInput')");
                }
                var Allmodalinput = document.querySelectorAll('.screen2 .modalinput');
                for (let index = 0; index < Allmodalinput.length; index++) {
                    const element = Allmodalinput[index];
                    element.setAttribute('onclick', "ActiveOption(this, '" + options + "', 'modalinput')");
                }
                var Allrangeinput = document.querySelectorAll('.screen2 .rangeinput');
                for (let index = 0; index < Allrangeinput.length; index++) {
                    const element = Allrangeinput[index];
                    element.setAttribute('onclick', "ActiveOption(this, '" + options + "', 'rangeinput')");
                }
                var AllPageSelect = document.querySelectorAll('.screen2 .page');
                for (let index = 0; index < AllPageSelect.length; index++) {
                    const element = AllPageSelect[index];
                    element.setAttribute('onchange', "Page(event,'page','" + options + "');");
                }
                options_btn.setAttribute('onclick', "Modal(this,'uploadFilesModal ." + options + "', true)");
                options_close_btn.setAttribute('onclick', "Modal(this,'uploadFilesModal ." + options + "', false)");
                options_close_btn2.setAttribute('onclick', "Modal(this,'uploadFilesModal ." + options + "', false)");
                increase_button.setAttribute('onclick', "Copies('noofcopies','" + options + "', 'add')");
                decrease_button.setAttribute('onclick', "Copies('noofcopies','" + options + "', 'subt')");
                document.querySelector(".screen1").classList.remove("active")
                document.querySelector(".screen2").classList.add("active")
                // screen2 range select box values populate
                var start = document.getElementById("optionStartRangePrice").innerHTML
                var startRange = document.getElementById("startRange");
                for (let i = 1; i <= start; i++) {
                    var option = document.createElement("option");
                    option.text = i;
                    option.value = i;
                    if (start <= 1) {
                        startRange.disabled = true;
                        option.setAttribute('selected', true);
                    }
                    startRange.add(option);
                };
                var endRange = document.getElementById("endRange");
                var end = document.getElementById("optionEndRangePrice").innerHTML
                // if(end<=1){
                //     document.getElementById('toOptions').value=1;
                // }else{
                //     document.getElementById('toOptions').name=1;
                // }
                for (let i = 1; i <= end; i++) {
                    var option = document.createElement("option");
                    option.text = i;
                    option.value = i;
                    // option.id = "";
                    if (end <= 1) {
                        endRange.disabled = true;
                        option.setAttribute('selected', true);
                        option.value = 1;
                    }
                    endRange.add(option);
                };
                // screen2 range select box values populate
            } else {
                document.querySelector(".screen1").classList.add("active")
                document.querySelector(".screen2").classList.remove("active")
                document.querySelector(".uploadFilesModal").style.display = "none"
                CopyTotalPrice();
            }
        }
        const UploadFilesModal = () => {
            document.querySelector(".uploadFilesModal").style.display = "flex"
        }
        Dropzone.autoDiscover = false; // Prevent Dropzone from auto-discovering forms
        var myDropzone = new Dropzone("#myDropzone", {
            //   url: "data:image/png;base64,", // Dummy URL to prevent errors (not used for actual uploads)
            params: {
                code: @json($code) ,
        phone: @json($phone) ,
        shop: @json($shop) ,
    // Add more fields as needed
  },
        url: "/getTotalPages",
            success: function(file, response) {
                console.log(response)
                let OptionCount = document.querySelector('.AllOptions').childElementCount;
                document.getElementById("optionStartRangePrice").innerHTML = response.total_pages;
                document.getElementById("optionEndRangePrice").innerHTML = response.total_pages;
                var printJob_id = response.printJob_id;
                var loaders = document.querySelectorAll("#loading");
                for (loader of loaders) {
                    loader.src = loader.dataset.alternate;
                }
                document.getElementById("nextBtn_upload").classList.remove('disabled');
                document.getElementById("close_upload").disabled = false;
                var fileDiv = document.createElement('div');
                var showOption = OptionCount + 1;
                fileDiv.className = 'File FileOption' + showOption;
                // fileDiv.innerHTML = 'whatsapp_file';
                fileDiv.innerHTML = `<div onclick="ImageOptions(this,'Option${OptionCount + 1}')">${file.name}</div><span style="margin-left:1.3em"onclick="deleteMy(this,'Option${OptionCount + 1}', ${printJob_id})"> x <span>`;
                fileDiv.style.display = "flex";
                //fileDiv.setAttribute("onclick", `ImageOptions(this,'Option${OptionCount + 1}')`)
                document.getElementById('myDropzone').appendChild(fileDiv);
            },
        autoProcessQueue: true, // Disable automatic uploading
            maxFiles: 10, // Set a maximum number of files
                clickable: false, // Disable clicking to open the file dialog
});
        document.getElementById('fileupload').addEventListener('change', function (e) {
            let OptionCount = document.querySelector('.AllOptions').childElementCount
            document.querySelector(".uploadFiles").style.display = "block"
            var files = e.target.files;
            const AllOptions = document.querySelector(".AllOptions")
            for (var i = 0; i < files.length; i++) {
                myDropzone.addFile(files[i])
                // AllOptions.appendChild(template.content.firstElementChild)
            }
            AllOptions.querySelector(".Option:nth-child(1)").classList.add("active")
            document.getElementById("nextBtn_upload").disabled = false;
            console.log(OptionCount + 1)
            document.getElementById("nextBtn_upload").setAttribute('onclick', `nextUploadScreen('screen1', 'Option${OptionCount + 1}', 'UploadModal${OptionCount + 1}')`)
            var screen2 = document.querySelector(".screen2");
            screen2.className = "";
            screen2.classList.add(`container`)
            screen2.classList.add(`screen2`)
            screen2.classList.add(`Option${OptionCount + 1}`)
            screen2.classList.add(`UploadModal${OptionCount + 1}`)
        });
        const SetOptionsValues = (e) => {
            let OptionCount = document.querySelector('.AllOptions').childElementCount
            console.log(e.parentElement.classList);
            const parent = e.parentElement.classList[3]
            const template = document.createElement("template")
            template.innerHTML = document.querySelector(".screen2").outerHTML
            document.querySelector(".AllOptions").appendChild(template.content.firstElementChild)
            document.querySelector(`.AllOptions .${e.parentElement.classList[2]}`).classList.replace("container", 'Option')
            document.querySelector(`.AllOptions .${e.parentElement.classList[2]} .save`).remove();
            var options_btn = document.querySelector(`.AllOptions .${e.parentElement.classList[2]} .options`);
            var options_close_btn = document.querySelector(`.AllOptions .${e.parentElement.classList[2]} .modal .close`);
            options_btn.setAttribute('onclick', `Modal(this,'AllOptions .Option${OptionCount + 1}', true)`);
            options_close_btn.setAttribute('onclick', `Modal(this,'AllOptions .Option${OptionCount + 1}', false)`);
            document.querySelector(".screen2").classList.remove(`Option${OptionCount + 1}`)
            document.querySelector(".screen2").classList.remove(`UploadModal${OptionCount + 1}`)
            document.querySelector(`.AllOptions .Option${OptionCount + 1}`).classList.remove(`screen2`)
            const mInput = document.querySelectorAll(`.${parent} .mInput`)
            for (let index = 0; index < mInput.length; index++) {
                const element = mInput[index];
                if (element.classList.contains('active')) {
                    // console.log(element.querySelector("input"))
                    element.querySelector("input").checked = true
                }
                if (element.classList.contains('pageInputSize')) {
                    const pageA = document.querySelector(`.${parent} .pageInputSize`)
                    // get select box
                    const pageSelectBox = document.querySelector(`.${parent} .Page`)
                    // selectBoxOptionValue how can i get it ?
                    for (var i = 0; i < pageSelectBox.options.length; i++) {
                        if (pageSelectBox.options[i].innerHTML === pageA.value) {
                            pageSelectBox.options[i].selected = true;
                            break; // Stop the loop once the desired option is found
                        }
                    }
                }
            }
            CopyTotalPrice();
        }
        myDropzone.on('success', function (file, response) {
        });
        let indexFiles = 0
        myDropzone.on('addedfile', function (file) {
            document.getElementById("nextBtn_upload").disabled = true;
            document.getElementById("close_upload").disabled = true;
            indexFiles++
            // Display the file in the #uploadedFiles div as an image
            var fileDiv = document.createElement('div');
            var UploadDisplay = document.createElement('template');
            // console.log(file)
            UploadDisplay.innerHTML = `
<div class="UploadedDisplay">
                  <img src="{{ asset('public/assets/img/file.png')}}" alt="">
                  <div class="details">
                      <h2>${file.name}</h2>
                      <p>${file.size / 1000}kb</p>
                  </div>
                  <img height="30px" width="30px" id="loading" data-alternate="{{ asset('public/assets/img/delete.png')}}" src="{{ asset('public/assets/img/loading.webp')}}" style="margin-right: auto;" alt="">
              </div>
`
            var fileDiv = document.createElement('div');
            fileDiv.className = 'File FileOption0';
            fileDiv.innerHTML = 'whatsapp_file';
            fileDiv.setAttribute("onclick", `ImageOptions(this,'Option0')`)
            document.getElementById('myDropzone').appendChild(fileDiv);
            //   var img = document.createElement('img');
            //   img.src = URL.createObjectURL(file);
            // fileDiv.className = 'File';
            // fileDiv.innerHTML = file.name;
            // //   console.log(UploadDisplay)
            // fileDiv.setAttribute("onclick", `ImageOptions(this,'Option${indexFiles + 1}')`)
            // //   fileDiv.appendChild(img);
            // document.getElementById('myDropzone').appendChild(fileDiv);
            document.querySelector('.uplaodedFilesDisplay').appendChild(UploadDisplay.content.firstElementChild);
            // console.log(file)
            // document.getElementById("nextBtn_upload").disabled= true;
        });
    </script>
    <script>

        const ImageOptions = (e, cl) => {
            // console.log(cl)
            const greyed = document.querySelectorAll(".greyed")
            greyed.forEach(element => {
                if(!element.classList.contains("range"))
                element.classList.remove("greyed")

            });
            if(selectSome){
                e.classList.toggle("active")
                if(e.classList.contains("active")){
                    SelectedOptions.push(cl)
                }else{
                    SelectedOptions = SelectedOptions.filter(x=> x != cl)
                }
                // console.log(SelectedOptions)

            }else{

                document.querySelectorAll(`.Option`).forEach(element => {
                    element.classList.remove("active")
                });
                document.querySelectorAll(`.File`).forEach(element => {
                    element.classList.remove("active")
                });
                document.querySelector(`.AllOptions .${cl}`).classList.add("active")
                e.classList.add("active")
            }
        }
        const ActiveOption = (e, MainParent, input) => {


            let inputs = document.querySelectorAll(`.${MainParent} .${input}`);
            if (inputs.length === 0) {
                console.log('no INputs')
                inputs = document.querySelectorAll(`.${input}`);
            }
            if (input == 'payInput' && document.querySelectorAll("#myDropzone .File.Saved").length == document.querySelectorAll("#myDropzone .File").length) {
                console.log("pay button activated")
                document.querySelector('#pay').disabled = false;
            }
            // const dummy_inputs = document.querySelectorAll(`.screen2 .${input}`);
            for (let index = 0; index < inputs.length; index++) {
                const element = inputs[index];
                element.classList.remove("active")
                var check_input = element.querySelector("input");
                if (check_input) {
                    check_input.checked = false;
                }
                if (element.classList.contains("pageInputSize")) {
                    const pageValueGet = element.classList.contains("pageInputSize").value;

                    // element.querySelector("input").checked = false
                }
            }
            e.classList.add("active")
            if (e.classList.contains("active")) {
                e.querySelector("input").checked = true
            }

            updatePrice(MainParent)


            if(MainParent == "AllSelectedOptions" && !selectSome){
                const AllOptions = document.querySelectorAll(".Option:not(.AllSelectedOptions)")
                var selectedOption = 0
                AllOptions.forEach((optionEl, optionIndex) => {
                    optionEl.querySelectorAll(".mInput").forEach(mInput => {
                        mInput.classList.remove("active")
                    });
                    optionEl.querySelectorAll(".mInput input").forEach(checkbox => {
                        checkbox.checked = false
                    })
                    optionEl.querySelectorAll(".modalinput").forEach(mInput => {
                        mInput.classList.remove("active")
                    });
                    optionEl.querySelectorAll(".modalinput input").forEach(checkbox => {
                        checkbox.checked = false
                    })
                    const AllInputs = document.querySelectorAll(".AllSelectedOptions .mInput")
                    const modalinput = document.querySelectorAll(".AllSelectedOptions .modalinput")
                    AllInputs.forEach((element, index) => {
                        if(element.classList.contains("active")){
                            optionEl.querySelectorAll(".mInput")[index].classList.add("active")
                            optionEl.querySelectorAll(".mInput input")[index].checked = true
                            if(input == "mInput"){
                                totalPriceCalculation('color', `${index == 0 ? "blackwhite" : "color" } `, `Option${optionIndex}`)


                                // console.log(optionIndex)
                            }
                        }

                    });
                    modalinput.forEach((element, index) => {
                        if(element.classList.contains("active")){
                            optionEl.querySelectorAll(".modalinput")[index].classList.add("active")
                            optionEl.querySelectorAll(".modalinput input")[index].checked = true
                            totalPriceCalculation('sides', `${index == 0 ? "oneside" : "twoside" } `, `Option${optionIndex}`)

                        }

                    });
                        updatePrice(`Option${optionIndex}`)
                });
            }
            else if(MainParent == "AllSelectedOptions" && selectSome){

                var selectedOption = 0
                SelectedOptions.forEach((cl, optionIndex) => {
                    const optionEl = document.querySelector(`.${cl}`)

                    optionEl.querySelectorAll(".mInput").forEach(mInput => {
                        mInput.classList.remove("active")
                    });
                    optionEl.querySelectorAll(".mInput input").forEach(checkbox => {
                        checkbox.checked = false
                    })
                    optionEl.querySelectorAll(".modalinput").forEach(mInput => {
                        mInput.classList.remove("active")
                    });
                    optionEl.querySelectorAll(".modalinput input").forEach(checkbox => {
                        checkbox.checked = false
                    })
                    const AllInputs = document.querySelectorAll(".AllSelectedOptions .mInput")
                    const modalinput = document.querySelectorAll(".AllSelectedOptions .modalinput")
                    AllInputs.forEach((element, index) => {
                        if(element.classList.contains("active")){
                            optionEl.querySelectorAll(".mInput")[index].classList.add("active")
                            optionEl.querySelectorAll(".mInput input")[index].checked = true
                            if(input == "mInput"){
                                totalPriceCalculation('color', `${index == 0 ? "blackwhite" : "color" } `, `${cl}`)
                                // console.log(optionIndex)
                            }
                        }

                    });
                    modalinput.forEach((element, index) => {
                        if(element.classList.contains("active")){
                            optionEl.querySelectorAll(".modalinput")[index].classList.add("active")
                            optionEl.querySelectorAll(".modalinput input")[index].checked = true
                            totalPriceCalculation('sides', `${index == 0 ? "oneside" : "twoside" } `, `${cl}`)
                        }

                    });

                        updatePrice(`${cl}`)
                });
            }
            document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()
        }
        const Copies = (e, MainParent, value) => {
            // console.log('option name', MainParent)
            // e = noofcoopies


            const p = document.querySelector(`.${MainParent} .${e}`)
            const copy = document.querySelector(`.${MainParent} #copies`)
            if (value == "add") {
                p.innerHTML = parseInt(p.innerHTML) + 1;

                copy.value = p.innerHTML;
                // console.log(copy.value)
            } else {
                if (p.innerHTML == 0) return
                p.innerHTML = parseInt(p.innerHTML) - 1
                copy.value = parseInt(p.innerHTML);
            }
            updatePrice(MainParent)
            document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()
            if(MainParent == "AllSelectedOptions" && !selectSome){


                const AllOptions = document.querySelectorAll(".Option:not(.AllSelectedOptions)")
                const  AllSelectorCopiesVal = parseInt(document.querySelector(".AllSelectedOptions .noofcopies").innerText)
                // console.log(document.querySelector(".AllSelectedOptions .noofcopies").innerText)
                AllOptions.forEach((OptionEl, index) => {

                const p = OptionEl.querySelector(`.${e}`)
                const copy = OptionEl.querySelector(` #copies`)

                p.innerHTML = AllSelectorCopiesVal;
                copy.value = AllSelectorCopiesVal;
                // console.log(copy.value)

                updatePrice(`Option${index}`)
                document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()
                });

        }else if(MainParent == "AllSelectedOptions" && selectSome){
            const  AllSelectorCopiesVal = parseInt(document.querySelector(".AllSelectedOptions .noofcopies").innerText)
            // console.log(document.querySelector(".AllSelectedOptions .noofcopies").innerText)
            SelectedOptions.forEach((cl, index) => {
                    const OptionEl = document.querySelector(`.${cl}`)

                const p = OptionEl.querySelector(`.${e}`)
                const copy = OptionEl.querySelector(` #copies`)

                p.innerHTML = AllSelectorCopiesVal;
                copy.value = AllSelectorCopiesVal;
                // console.log(copy.value)

                updatePrice(cl)
               document.querySelector(".totalDocPriceValue").innerText = GetAllDocumentPrice()
                });
        }




        }
        var changedModal = false;
        const closeCash = (e) => {
            let modal = e.parentElement;
            let modalParent = modal.parentElement;
            modal.style.display = 'none';
            modalParent.style.display = 'none';
        }
        const Modal = (e, Mainparent, value) => {
            // console.log(Mainparent)
            //console.log(e.parentElement)
            if(Mainparent == 'AllSelectedOptions' &&  value)
            {

                if( SelectedOptions.length == 1)
                {
                    Mainparent = SelectedOptions[0];
                    console.log("Main Parent Changed to "+Mainparent);
                    if(document.querySelector(".AllSelectedOptions").classList.contains("active"))
                {
                    document.querySelector(".AllSelectedOptions").classList.remove("active");
                }

                if(!document.querySelector("."+Mainparent).classList.contains("active"))
                {
                    document.querySelector("."+Mainparent).classList.add("active");
                }


                changedModal = true;

                }
            }

            if(!value && changedModal)
            {
                if(!document.querySelector(".AllSelectedOptions").classList.contains("active"))
                {
                    document.querySelector(".AllSelectedOptions").classList.add("active");
                }

                if(document.querySelector("."+Mainparent).classList.contains("active"))
                {
                    document.querySelector("."+Mainparent).classList.remove("active");
                }
            }
            let modal = document.querySelector(`.${Mainparent} .modal`)
            if (value) {
                modal.style.display = "flex"
            } else {
                modal.style.display = "none"
            }
            // console.log(modal)
        }
        const getSelectedPaymentMethod = () => {
            var pay_methods = document.querySelectorAll('input[name="type"]');
            for (pay_method of pay_methods) {
                if (pay_method.checked) {
                    return pay_method.value;
                }
            }
        }
        const confirmModal = (e, value) => {
            const modal = document.getElementById("confirmModal")
            const pay_method = getSelectedPaymentMethod()
            // alert(pay_method)
            const form = document.querySelector('#form1')
            if (value) {
                if (pay_method == 'cash') {
                    //modal.style.display = "flex"
                    //document.getElementById("pay").setAttribute('type', 'button');
                    modal.style.display = "none"
                    document.getElementById("pay").setAttribute('type', 'submit');
                    // const form = document.querySelector('#form1')
                    form.submit()
                } else {
                    modal.style.display = "none"
                    document.getElementById("pay").setAttribute('type', 'submit');
                    // const form = document.querySelector('#form1')
                    form.submit()
                }
            } else {
                modal.style.display = "none"
            }
        }
        const HideModal = () => {
            const modal = document.getElementById("advance_modal");
            modal.style.display = "none";
        }
    </script>
    <script>
        const startRangeSelect = document.getElementById('startRange');
        const endRangeSelect = document.getElementById('endRange');
        startRangeSelect.addEventListener('change', () => {
            const selectedValue = parseInt(startRangeSelect.value);
            // Enable all options in endRangeSelect
            for (const option of endRangeSelect.options) {
                option.disabled = false;
            }
            // Disable options greater than or equal to the selected value in endRangeSelect
            for (const option of endRangeSelect.options) {
                if (parseInt(option.value) <= selectedValue) {
                    option.disabled = true;
                }
            }
        });
        $(".close").on('click', function (event) {
            $(".uploadFilesModal").hide();
        });
        $(".classbtn").on('click', function (event) {
            $(".UploadModal0").hide();
        });
        const confirmSubmit = () => {
            const form = document.querySelector('#form1')
            form.submit()
        }
    </script>
    <script>
        @if (session() -> has('modal_code'))
            $(document).ready(function () {
                $('#cashOrder').show();
            });
        @endif
        const successModal = () => {
            $.ajax({
                type: 'POST',
                url: '{{ route('remove_modal_session') }}', // Define a Laravel route for this purpose
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function (data) {
                }
            });
            document.querySelector(".successModal").style.display = "none"
        }


    </script>
</body>

</html>
@endsection
