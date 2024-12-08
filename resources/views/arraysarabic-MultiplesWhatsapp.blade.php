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
<body>
   @if(isset($cashResponse))
   {{$cashResponse}}
   @endif
   <style>
      .UploadFiles{
      height: auto !important;
      min-height: 18.4rem;
      }
      button.disabled {
      background-color: #ccc !important;
      cursor: not-allowed !important;
      pointer-events: none;
      }
      .dropzone{
      margin-top: 0;
      display: none;
      border: 0;
      min-height: auto;    flex-wrap: wrap;
      padding: 0;
      justify-content: flex-end;
      }
      .dz-image-preview,.dz-preview, .dz-file-preview, .dz-processing, .dz-complete{
      display: none !important;
      }
      #myDropzone > div.dz-default.dz-message {
      display:none;
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
      max-height: 100%;
      max-width: 100%;
      width: auto;
      min-width: 50%;
      }
      .uploaded-image:hover{
      box-shadow: 5px 5px 5px #8080800f;
      }
      .AllOptions .Option{
      display: none;
      } 
      .AllOptions .Option.active{
      display: block;
      }
      #myDropzone{
      display: flex;
      }
      .document .right .box.uploadFiles .top{
      margin-bottom: 1.4rem;
      }
      .uploadFilesModal .container .modal .container{
      display : block !important
      }
   </style>
   </div>
   <section class="document">
      <div class="left">
         <img src="{{ asset('public/assets/img/lines1.png') }}" class="line1" alt="">
         <img src="{{ asset('public/assets/img/lines2.png ') }}" class="line2" alt="">
         <div class="header">
            <img src="{{ asset('public/assets/img/logo.png') }}" alt="">
            @if($shop)
            <a href="{{route('getOptions',[$code,$shop])}}" class="btn">English</a>
            @else
            <a href="{{route('getOptions',[$code])}}" class="btn">English</a>
            @endif
         </div>
         <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">
      </div>
      <div class="right">
      <div class="uploadFilesModal" >
         <div class="container screen1 active">
            <div  >
               <button class="close" id="close_upload">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                     <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E"/>
                  </svg>
               </button>
            </div>
            <div class="uploadBox">
               <img src="{{ asset('public/assets/img/upload.png')}}" alt="">
               <p>قم بتحميل الملف الخاص بك هنا</p>
               <button type="button">رفع
               <input id="fileupload" mutliple  type="file" name="file[]" accept=".xls, .xlsx, .docx, .doc, .pdf, .jpeg, .jpg, .png" required>
               </button>
            </div>
            <div class="uplaodedFilesDisplay">
            </div>
            <button  type="button" class="next disabled" id="nextBtn_upload" onclick="nextUploadScreen('screen1', `Option1`, `UploadModal1`)">التالي</button>
         </div>
         <script></script>
         <div class="container screen2 Op0 UploadModal0">
            <div class="box color">
               <div class="top">
                  <h3>
                     إختر لون
                  </h3>
               </div>
               <div id="bw" class="input mInput   bwcolor blackwhite active"  onclick="ActiveOption(this, 'UploadModal0', 'mInput')">
                  <div class="price">
                     <p class="title">أسود / أبيض</p>
                     <p id="bwPrice" style="display:flex"><span style="margin-right : .4rem;font-size: 1.3rem;font-weight: 600;color: #007FFF40;">رس</span>{{isset($pricing)?$pricing->bw_copies_price:"0"}} ورقة &nbsp; ب  <span style="margin-left:0.1em"> {{isset($pricing)?$pricing->bw_copies:"0"}}</span>
                  </div>
                  <input type="checkbox" name="color[]"  value = "false" class="checkbox" checked>
               </div>
               <div id="color" class="input mInput  colored" onclick="ActiveOption(this,'UploadModal0', 'mInput')">
                  <div class="price">
                     <p class="title">ملون</p>
                     <p id="colorPrice" style="display:flex"><span style="margin-right : .4rem">رس</span  > {{isset($pricing)?$pricing->color_copies_price:"0"}} ورقة&nbsp;  ب    <span style="margin-left:0.1em"> {{isset($pricing)?$pricing->color_copies:"0"}}</span></p>
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
                  <h3 >
                     عدد النسخ
                  </h3>
               </div>
               <div class="input">
                  <button  type="button" id="inc"  onclick="Copies('noofcopies','UploadModal0', 'add')">+</button>
                  <p class="noofcopies" style="margin-bottom:0">1</p>
                  <input type="hidden" class="" value="1" name="copies[]" id="copies">
                  <button type="button" id="dec" onclick="Copies('noofcopies','UploadModal0', 'subt')">-</button>
               </div>
            </div>
            
            <div class="box copies total">
               <div class="top">
                  <h3>
                     المجموع
                  </h3>
               </div>
               <div class="input">
                  <p class="totalPriceValue">1</p>
                  <span style="margin-right : .4rem">رس</span  > 
               </div>
            </div>
            <button  type="button" class="next save" onclick="nextUploadScreen('screen2'); SetOptionsValues(this)">
            يحفظ
            </button>
            <div class="modal" style="position: fixed; width: 100vw; height: 100vh; top: 0px; left: 0px; ">
               <div class="container">
                  <div class="close" onclick="Modal(this,'UploadModal0', false)">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E"></path>
                     </svg>
                  </div>
                  <h2 class="mainHeading">خيارات متقدمة</h2>
                  <p style="display:none;" id="singlePrice${i}">{{isset($pricing)?$pricing->one_side:"0.33"}}</p>
                  <p style="display:none;"  id="doublePrice${i}">{{isset($pricing)?$pricing->double_side:"0.33"}} </p>
                  <div class="row">
                     <h3>حدد جانب</h3>
                     <div class="inputs">
                        <div class="input modalinput   oneSide active " onclick="totalPriceCalculation('sides', 'oneside');ActiveOption(this,'UploadModal0', 'modalinput')">
                           <p class="title">من جانب واحد {{isset($pricing)?$pricing->one_side:"0.333"}} .00 ريال </p>
                           <input type="checkbox" name="sides[]" value="one" class="checkbox" checked>
                        </div>
                        <div class="input modalinput   twoSide" onclick="totalPriceCalculation('sides', 'twoside');ActiveOption(this,'UploadModal0', 'modalinput')">
                           <p class="title">وجهان {{isset($pricing)?$pricing->double_side:"0.333"}}  ريال</p>
                           <input type="checkbox" name="sides[]" value="two" class="checkbox">
                        </div>
                     </div>
                  </div>
                  <div class="row range">
                     <p style="display:none;"  id="singlePrice">{{isset($pricing)?$pricing->one_side:"0.33"}}</p>
                     <p style="display:none;"  id="doublePrice">{{isset($pricing)?$pricing->double_side:"0.33"}}</p>
                     <p style="display:none;" id="optionStartRangePrice"> </p>
                     <p style="display:none;" n id="optionEndRangePrice"> </p>
                     <h3>نطاق الصفحات</h3>
                     <div class="input rangeinput  active" onclick="ActiveOption(this,'UploadModal0', 'rangeinput')">
                        <p class="title">الجميع</p>
                        <input type="checkbox" class="checkbox" checked>
                     </div>
                     <div class="input  input2">
                        <div class="custom rangeinput " onclick="ActiveOption(this,'UploadModal0', 'rangeinput')">
                           <p class="title">مخصص</p>
                           <input type="checkbox" class="checkbox">
                        </div>
                        <div class="pages">
                           <div class="from">
                              <p>من</p>
                              <input hidden id="fromOptions" name="from[]"  value="1" >
                              <select name="fromOptions" class="frompage"  id="startRange" onchange="PageRange(event, 'from')">
                              </select>
                           </div>
                           <div class="to">
                              <p>ل</p>
                              <input hidden id="toOptions" name="to[]" value="1" >
                              <select name="to[]"  class="topage" id="endRange" onchange="PageRange(event, 'to')">
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row size">
                     @php $data = unserialize($pricing->size_amount); @endphp
                     <input hidden class="   pageInputSize" name="pageSize[]"   value="A4">
                     <h3>مقاس الصفحه</h3>
                     <select  name= "page_size[]" class="Page" id="pageSize" onchange="Page(event, 'page')">
                     @foreach ($data as $size => $amount)
                     <option  @if($size == 'A4') selected @endif value="{{ $amount }}">{{ $size }}  @if($size != 'A4')( + {{ $amount }}  SAR) @endif</option>
                     @endforeach
                     </select>
                  </div>
                  <button class="classbtn" type="button" onclick="Modal(this,'UploadModal0', false)">منتهي</button>
               </div>
            </div>
         </div>
      </div>
      <div class="container " >
      <!-- <form action="" class="dropzone" id="my-awesome-dropzone">
         <input type="file" name="file" id="uploadFiles">
         
         
         
         
         
         
         
         </form> -->
      <form method="post" action="{{ route('aSubmitDocument') }}" id="form1">
         @csrf
         <input type="hidden" name="file" id="WFile"
            value="@if (isset($file)) @php echo $file; @endphp @endif">
         <input type="hidden" name="phone"
            value="@if (isset($phone)) @php echo $phone; @endphp @endif">
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
         <div class="box uploadFiles">
            <div class="top">
               <h3>تحميل الوثيقة</h3>
            </div>
            @php
            $fileCount = 0;
            @endphp
            <div class="dropzone" id="myDropzone">
               @foreach($printJob as $job)
               <div class="File FileOption{{$fileCount}}" onclick="ImageOptions(this,'Option{{$fileCount}}')">
                  Whatsapp File {{$fileCount + 1 }} <span class="del" style="margin-left:0.4rem"><img src="{{ asset('public/assets/img/delete.png')}}" ></span>
               </div>
               @php
               $fileCount += 1;
               @endphp
               @endforeach
            </div>
         </div>
         <div class="AllOptions">
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
                  <div class="input mInput  blackwhite active" onclick=" totalPriceCalculation('color', 'blackwhite');ActiveOption(this, 'Option{{$count}}', 'mInput')">
                     <div class="price">
                        <p class="title">أسود / أبيض</p>
                        <p style="display : flex"><span style="margin-right : .4rem">رس</span> {{isset($pricing)?$pricing->bw_copies_price:"0"}}  ورقة &nbsp; ب    <span style="margin-left:0.1em"> {{isset($pricing)?$pricing->bw_copies:"0"}}</span> </p>
                     </div>
                     <input type="checkbox" name="color[]" value="false" class="checkbox" checked>
                  </div>
                  <div class="input mInput"  onclick=" totalPriceCalculation('color', 'color');ActiveOption(this,'Option{{$count}}', 'mInput')">
                     <div class="price">
                        <p class="title">ملون</p>
                        <p style="display : flex"><span style="margin-right : .4rem">رس</span  > {{isset($pricing)?$pricing->color_copies_price:"0"}} ورقة &nbsp;  ب <span style="margin-left:0.1em"> {{isset($pricing)?$pricing->color_copies:"0"}}</span></p>
                     </div>
                     <input type="checkbox" name="color[]" value="true" class="checkbox">
                  </div>
                  <div class="options"  onclick="Modal(this,'Option{{$count}}', true)">
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
                     <button  type="button" onclick="Copies('noofcopies','Option{{$count}}', 'add')">+</button>
                     <p class="noofcopies">1</p>
                     <button  type="button" onclick="Copies('noofcopies','Option{{$count}}', 'subt')">-</button>
                     <input  type="hidden" class="noofcopies" value="1" name="copies[]" id="copies">
                  </div>
               </div>
               <div class="box copies total" style="display:">
                  <div class="top">
                     <h3>
                        المجموع 
                     </h3>
                  </div>
                  <div class="input">
                     <p style="display:flex;" class="totalPriceValue"> 
                        1.00
                     </p>
                     <span style="margin-right : .4rem">رس</span  > 
                  </div>
               </div>
               <div class="modal" style="    position: fixed;
                  width: 100vw;
                  height: 100vh;
                  top: 0;
                  left: 0;">
                  <div class="container">
                     <div class="close" onclick="Modal(this,'Option{{$count}}', false)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                           <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E"/>
                        </svg>
                     </div>
                     <!-- <h2 class="mainHeading">Advanced Optionss</h2> -->
                     <p style="display:none;"  id="singlePrice${i}">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>
                     <p style="display:none;"  id="doublePrice${i}">{{isset($pricing)?$pricing->double_side:"0.333"}} </p>
                     <p style="display:none;"  id="bwCopy">{{isset($pricing)?$pricing->bw_copies:"3"}} </p>
                     <p style="display:none;"  id="colorCopy">{{isset($pricing)?$pricing->color_copies:"1"}} </p>
                     <p style="display:none;"  id="bwCopiesPrice">{{isset($pricing)?$pricing->bw_copies_price:"1"}} </p>
                     <p style="display:none;"  id="colorCopiesPrice">{{isset($pricing)?$pricing->color_copies_price:"1"}} </p>
                     <div class="row">
                        <h3>حدد جانب</h3>
                        <div class="inputs" >
                           <div class="input modalinput  active" onclick="totalPriceCalculation('sides', 'oneside'); ActiveOption(this,'Option{{$count}}', 'modalinput')">
                              <p class="title"> جانب واحد {{isset($pricing)?$pricing->one_side:"0.333"}}  ريال </p>
                              <input type="checkbox" name="sides[]" value="one" class="checkbox" checked>
                           </div>
                           <div class="input modalinput " onclick="totalPriceCalculation('sides', 'twoside'); ActiveOption(this,'Option{{$count}}', 'modalinput')">
                              <p class="title">وجهان {{isset($pricing)?$pricing->double_side:"0.333"}}  ريال</p>
                              <input type="checkbox"  name="sides[]" value="two" class="checkbox">
                           </div>
                        </div>
                     </div>
                     <div class="row range">
                        <p hidden id="singlePrice">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>
                        <p hidden id="doublePrice">{{isset($pricing)?$pricing->double_side:"0.333"}} </p>
                        @php
                        $range = range(1, $job->total_pages);
                        $start = $range[0];
                        $end = end($range);
                        @endphp
                        <p  hidden  id="startRangePrice">{{$start}} </p>
                        <p hidden  id="endRangePrice">{{$end}} </p>
                        <h3>نطاق الصفحات</h3>
                        <div class="input rangeinput  active" onclick="ActiveOption(this,'Option{{$count}}', 'rangeinput')">
                           <p class="title">الجميع</p>
                           <input type="checkbox"  class="checkbox" checked>
                        </div>
                        <div class="input  input2" >
                           <div class="custom rangeinput " onclick="ActiveOption(this,'Option{{$count}}', 'rangeinput')">
                              <p class="title">مخصص</p>
                              <!-- <p>من</p><p>ل</p> -->
                              <input  @if($job->total_pages <= '1') disabled @endif type="checkbox" value="custom" class="checkbox">
                           </div>
                           <div class="pages">
                              <div class="from">
                                 <p style="margin-bottom:0">ل</p>
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
                              <div class="to">
                                    @if($job->total_pages <= '1' ) <input hidden name="to[]" value="1">
                                       @endif
                                       <p style="margin-bottom:0">من  </p>
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
                           </div>
                        </div>
                     </div>
                     <div class="row size">
                        @php $data = unserialize($pricing->size_amount); @endphp
                        <h3>مقاس الصفحه</h3>
                        <input hidden  class="mInput pageInputSize" name="pageSize[]" value="A4">
                        <select name= "page_size[]" class="page_size" id="pageSize" onchange="Page(event, 'page','Option{{$count}}')">
                        @foreach ($data as $size => $amount)
                        <option   @if($size == 'A4') selected @endif value="{{ $amount }}">{{ $size }} @if($size != 'A4') ( + {{ $amount }}  SAR)@endif</option>
                        @endforeach
                        </select>
                     </div>
                     <button  type="button" onclick="Modal(this,'Option{{$count}}', false)">منتهي</button>
                  </div>
               </div>
            </div>
            @php
            $count += 1;
            @endphp
            @endforeach
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
         <div class="box payment">
            <div class="top">
               <h3>
                  خيارات الدفع
               </h3>
            </div>
            <div class="input payInput" onclick="ActiveOption(this,'Option0', 'payInput')">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M17.0499 20.28C16.0699 21.23 14.9999 21.08 13.9699 20.63C12.8799 20.17 11.8799 20.15 10.7299 20.63C9.28992 21.25 8.52992 21.07 7.66992 20.28C2.78992 15.25 3.50992 7.59003 9.04992 7.31003C10.3999 7.38003 11.3399 8.05003 12.1299 8.11003C13.3099 7.87003 14.4399 7.18003 15.6999 7.27003C17.2099 7.39003 18.3499 7.99003 19.0999 9.07003C15.9799 10.94 16.7199 15.05 19.5799 16.2C19.0099 17.7 18.2699 19.19 17.0399 20.29L17.0499 20.28ZM12.0299 7.25003C11.8799 5.02003 13.6899 3.18003 15.7699 3.00003C16.0599 5.58003 13.4299 7.50003 12.0299 7.25003Z" class="svgs"/>
               </svg>
               <p>Apple Pay</p>
               <input type="checkbox" name="type" value="applepay" class="checkbox">
            </div>
            <div class="input payInput" onclick="ActiveOption(this,'Option0',  'payInput')">
               <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">
                  <path d="M1 9C1 5.229 1 3.343 2.172 2.172C3.343 1 5.229 1 9 1H13C16.771 1 18.657 1 19.828 2.172C21 3.343 21 5.229 21 9C21 12.771 21 14.657 19.828 15.828C18.657 17 16.771 17 13 17H9C5.229 17 3.343 17 2.172 15.828C1 14.657 1 12.771 1 9Z" class="svgs" stroke-width="1.5"/>
                  <path d="M9 13H5M13 13H11.5M1 7H21" class="svgs" stroke-width="1.5" stroke-linecap="round"/>
               </svg>
               <p>بطاقة إئتمان</p>
               <input type="checkbox" name="type" value="card" class="checkbox">
            </div>
            <div class="input payInput" onclick="ActiveOption(this,'Option0','payInput')">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M12.0001 7.7143V5.14287M12.0001 7.7143C10.5773 7.7143 9.42871 7.7143 9.42871 9.42858C9.42871 12 14.5716 12 14.5716 14.5714C14.5716 16.2857 13.423 16.2857 12.0001 16.2857M12.0001 7.7143C13.423 7.7143 14.5716 8.36573 14.5716 9.42858M9.42871 14.5714C9.42871 15.8572 10.5773 16.2857 12.0001 16.2857M12.0001 16.2857V18.8572" class="svgs" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M12 23.1429C18.1541 23.1429 23.1429 18.154 23.1429 12C23.1429 5.84597 18.1541 0.857147 12 0.857147C5.846 0.857147 0.857178 5.84597 0.857178 12C0.857178 18.154 5.846 23.1429 12 23.1429Z" class="svgs" stroke-linecap="round" stroke-linejoin="round"/>
               </svg>
               <p>نقدي</p>
               <input type="checkbox" name="type" value="cash" class="checkbox" id="cash_order">
            </div>
         </div>
         <!--<input type="hidden" name="type" value="online"  >-->
         <button class="pay" id="pay" type="submit" onclick="confirmModal(event, true);disable(this);"  disabled='true' >
         تأكيد الدفع
         </button>
      </form>
      </form>
      @if(session()->has('modal_code'))
      {!! session('modal_code') !!}
      @endif
      <div class="modal confirmModal" id="confirmModal" >
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
               <input type="text" name="name"  class="name_input" placeholder="Enter your full name">
            </div>
            <button type="submit" onclick="confirmSubmit()">يتابع</button>
         </div>
      </div>
   </section>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
   <script>
      const AllMInputs = document.querySelectorAll(".mInput")
      
      
      
      
      
      
      
      for (let index = 0; index < AllMInputs.length; index++) {
      
      
      
      
      
      
      
          const element = AllMInputs[index];
      
      
      
      
      
      
      
          if(element.classList.contains("active")){
      
      
      
      
      
      
      
              element.querySelector("input").checked = true
      
      
      
      
      
      
      
          }
      
      
      
      
      
      
      
      }
      
      
      
              // const AllPrice=0;
      
      
      
              let color = "blackwhite";
      
      
      
      
      
      
      
              let sides = "oneside";
      
      
      
      
      
      
      
               const  noofcopies = document.querySelector(".noofcopies")
      
      
      
      
      
      
      
              const  frompage = document.querySelector(".frompage")
      
      
      
      
      
      
      
              const  topage = document.querySelector(".topage")
      
      
      
      
      
      
      
              const  page_size_value = document.querySelector(".page_size")
      
             
      
             var AllPrice=0;
      
                  
      
      
      
              const totalPriceCalculation = (varname, value )=>{
      
      
      
              console.log('value',value)
      
      
      
      
      
      
      
              if(varname == "color"){
      
      
      
      
      
      
      
                  color = value
      
      
      
      
      
      
      
              }else if(varname == "sides"){
      
      
      
      
      
      
      
                  sides = value
      
      
      
      
      
      
      
              }
      
      
      
      
      
      
      
              }
      
      
      
      
      
          const disable = (element) => {
      
              element.setAttribute('disabled', true);
      
          }
      
      
      
      
      
              const calculatePrice = (MainParent) => {
      
                  console.log('original parent',MainParent)
      
                  if(MainParent =='payInput'){
      
                      MainParent = 'option0'
      
                  }
      
                  
      
                  console.log('copies parent',MainParent)
      
                 
      
                  const numCopies = document.querySelector(`.${MainParent}  #copies `)
      
                  console.log('numCopies',numCopies)
      
                   const numCopiesText = parseInt(numCopies.innerHTML);
      
                
      
                   const noofcopiesValue =  parseInt(numCopies.value)
      
                
      
      
      
                  const fromPage = document.getElementById("startRangePrice").innerHTML;
      
      
      
      
      
                  const toPage  = document.getElementById("endRangePrice").innerHTML;
      
      
      
                  const totalPages = toPage - fromPage + 1;
                  console.log(totalPages)
      
      
      
                  // const numCopies = document.querySelector(`.${MainParent}  #copies `)
      
                  const getPageSize = document.querySelector(`.${MainParent}  #pageSize `)
      
                  console.log('getPageSize',getPageSize.value)
      
                  const page_size_price = getPageSize.value
      
                  // const page_size_price  = parseInt(page_size_value.value);
      
                 
      
      
      
      
      
                  let basePrice = 0;
      
      
      
      
      
      
      
                  if (color === "blackwhite") {
      
      
      
      
      
      
      
                      //var bwPrice = document.getElementById("bwPrice").innerHTML;
      
      
      
      
      
      
      
                      basePrice = 1; 
      
      
      
      
      
      
      
                  } else if (color === "color") {
      
      
      
      
      
      
      
                      //var colorPrice =document.getElementById("colorPrice").innerHTML;
      
      
      
      
      
      
      
                      basePrice = 2; 
      
      
      
      
      
      
      
                      // console.log(basePrice)
      
      
      
      
      
      
      
                  }
      
      
      
      
      
      
      
                  let sidednessMultiplier = 0;
      
      
      
      
      
      
      
                  if (sides === "oneside") {
      
      
      
      
      
      
      
                      sidednessMultiplier = document.getElementById("singlePrice").innerHTML;
      
      
      
      
      
      
      
                      // sidednessMultiplier = 1;
      
      
      
      
      
      
      
                      // console.log("one")
      
      
      
      
      
      
      
                  }else if (sides == "twoside"){
      
      
      
      
      
      
      
                      sidednessMultiplier =document.getElementById("doublePrice").innerHTML;
      
      
      
      
      
      
      
                  }
      
      
      
      
      
      
      
                  console.log(noofcopiesValue,totalPages,basePrice,sidednessMultiplier,page_size_price)
      
                  // get dynamic prices 
      
                 var bwCopy=  document.getElementById("bwCopy").innerHTML;
      
                 var colorCopy=  document.getElementById("colorCopy").innerHTML;
      
                 
      
                 var bwPrice=  document.getElementById("bwCopiesPrice").innerHTML;
      
                 var colorPrice=  document.getElementById("colorCopiesPrice").innerHTML;
      
                 
      
                 console.log("bwCopy",bwCopy)
      
                 console.log("colorCopy",colorCopy)
      
      
      
      
      
                 var doc_pages = totalPages;
      
                 var doc_copies = noofcopiesValue;
      
                 var doc_type  = basePrice;
      
              
      
              
      
                  var doc_TotalPages = doc_pages * doc_copies;
      
                  // formula apply 
      
                  var totalPrice =0;
      
                  var count = 0; 
      
                  if(doc_type == 2)
      
                  {
      
                      // 3>0 
      
                      while(doc_TotalPages > 0 )
      
                      {
      
                          doc_TotalPages -= colorCopy;
      
                          
      
                          count++;
      
                      
      
                      }
      
      
      
                      var totalPrice = count* colorPrice;
      
                      totalPrice += parseFloat(page_size_price) + parseFloat(sidednessMultiplier);
      
                      // console.log("totalPrice",totalPrice)
      
                    
      
                  }
      
                  
      
                  if(doc_type == 1)
      
                  {
      
                      while(doc_TotalPages > 0 )
      
                      {
      
                          doc_TotalPages -= bwCopy;
      
                          count++;
      
                      
      
                      }
      
                      var totalPrice = count* bwPrice;
      
                       totalPrice += parseFloat(page_size_price) + parseFloat(sidednessMultiplier);
      
                      // console.log("totalPrice",totalPrice)
      
                  }
      
      
      
                 
      
                  var temp = totalPrice*1.15
      
                  
      
                  if(temp<1)
      
                  {
      
                      temp = 1.15;
      
                  }
      
      
      
                  return temp.toFixed(2);
      
              };
      
      
      
              let getPrice=0 ;
      
      
      
              const CopyTotalPrice = ()=>{
      
                  // parents 
      
                  document.querySelectorAll(`.Option .totalPriceValue`);
      
                 
      
                 const  alltotalPriceValue = document.querySelectorAll(`.Option .totalPriceValue`);
      
                 console.log('alltotalPriceValue',alltotalPriceValue)
      
                 var getPrice = 0;
      
                 for (let index = 0; index < alltotalPriceValue.length; index++) {
      
                     const element = alltotalPriceValue[index];
      
                     var price = element.innerHTML;
      
                     getPrice = getPrice+parseFloat(price);
      
                     
      
                 }
      
                 console.log('getPrice',getPrice);
      
                 const  alltotalPriceValueSET = document.querySelectorAll(` #Alltotal`)
      
                  for (let index = 0; index < alltotalPriceValueSET.length; index++) {
      
                      const element = alltotalPriceValueSET[index];
      
                      element.textContent = getPrice.toFixed(2) ;
      
                  }
      
                 
      
      
      
      
      
      
      
             }
      
      
      
              
      
      
      
              
      
             
      
      
      
              const updatePrice = (MainParent) => {
      
      
      
              
      
              
      
              const  totalPriceValue = document.querySelector(`.${MainParent}  .totalPriceValue `)
      
                
      
                  const price = calculatePrice(MainParent);
      
                  var t = price;
      
                  totalPriceValue.textContent = t;
      
                
      
              
      
                  $("#price").val(price);
      
                  const  alltotalPriceValue = document.querySelectorAll(`.${MainParent} #Alltotal`)
      
                  console.log('alltotalPriceValue',alltotalPriceValue)
      
              
      
                  for (let index = 0; index < alltotalPriceValue.length; index++) {
      
                      const element = alltotalPriceValue[index];
      
                      element.textContent = price ;
      
                  }
      
      
      
                   CopyTotalPrice();
      
      
      
              };
      
              
      
               updatePrice("Option0");
      
      
      
            
      
      
      
              // }
      
      
      
      
      
              // updatePrice()
      
      
      
      
      
      
      
              let fromPageValue = 1
      
      
      
      
      
      
      
              let toPageValue = 2
      
      
      
              
      
      
      
      
      
      
      
              const PageRange = (e, value)=>{
      
      
      
      
      
      
      
                  if(value == "from"){
      
      
      
      
      
      
      
                      fromPageValue = e.target.value
      
      
      
      
      
      
      
                      document.getElementById("startRangePrice").innerHTML = fromPageValue
      
      
      
      
      
      
      
                  }else{
      
      
      
      
      
      
      
                      toPageValue = e.target.value
      
      
      
      
      
      
      
                      // console.log('toPageValue',toPageValue)
      
      
      
      
      
      
      
                      document.getElementById("endRangePrice").innerHTML = toPageValue
      
      
      
      
      
      
      
                  }
      
      
      
      
      
      
      
                  totalPages = parseInt(toPageValue) - parseInt(fromPageValue) + 1
      
      
      
      
      
      
      
                  pages = totalPages
      
      
      
      
      
      
      
                  updatePrice()
      
      
      
      
      
      
      
              }
      
      
      
      
      
      
      
              let pageValue = 1
      
      
      
      
      
      
      
              const Page = (e, value,MainParent)=>{
      
      
      
                  console.log('test page',e)
      
                  var name =  e.target.options[e.target.selectedIndex];
      
                  
      
                  const  pageInputSize = document.querySelector(`.${MainParent}  .pageInputSize `)
      
                 
      
                  pageInputSize.setAttribute('value',name.innerHTML)
      
                  
      
                  if(value == "page"){
      
                      pageValue = e.target.value
      
                     
      
                  }
      
                  updatePrice(MainParent)
      
      
      
              }
      
             
      
      
      
      
      
      
      
   </script>
   <script>
      function deleteMy(e, option, printJob_id) {
      
      
      
      
      
      // Ajax call to remove Print Job Id from DB
      
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      
      
      
      // Assuming you're using jQuery for Ajax, you can do it like this:
      
      $.ajax({
      
      type: 'POST', // or 'GET' depending on your server setup
      
      url: '/removePrintJob', // Replace with the actual server endpoint
      
      data: { printJobId: printJob_id,
      
      _token : csrfToken }, // Send the printJob_id as data
      
      success: function (response) {
      
          // on Success: Remove Options and Element
      
          if (response.success) {
      
              // Remove the options or element as needed
      
              // For example, if you want to remove a <div> element with a specific ID:
      
              // $('#yourElementId').remove();
      
             var options = document.querySelectorAll(`.Option.${option}`);
      
             for(opt of options)
      
             {
      
              opt.remove();
      
             }
      
             document.querySelector(`.File.File${option}`).remove();
      
             document.querySelector(".Option.Option0").classList.add('active');
      
              document.querySelector(".File.FileOption0").classList.add('active');
      
          }
      
      },
      
      error: function () {
      
          // on Failure: Do nothing or handle the error
      
      }
      
      });
      
      }
      
      
      
      
      
      
      
      const nextUploadScreen = (value,options = null, modal)=>{
      
      
      
      
      
      
      
          if(value == "screen1" && options){
      
      
      
      
      
      
      
              document.getElementById("nextBtn_upload").classList.add('disabled');
      
      
      
      
      
      
      
              var bw_select = document.querySelector('.screen2 #bw');
      
      
      
      
      
      
      
                  document.querySelector('.screen2').classList.replace("Op0", `${options}`);
      
      
      
                 
      
                  updatePrice(options)
      
      
      
      
      
                  document.querySelector('.screen2').classList.replace("UploadModal0", `${modal}`);
      
      
      
      
      
      
      
                  var color_select = document.querySelector('.screen2 #color');
      
      
      
                  
      
      
      
                  var options_btn = document.querySelector('.screen2 .options');
      
      
      
      
      
      
      
                  var options_close_btn = document.querySelector('.screen2 .modal .close');
      
      
      
      
      
      
      
                  var options_close_btn2 = document.querySelector('.screen2 .modal .classbtn');
      
      
      
      
      
      
      
                  var increase_button =  document.querySelector('.screen2 #inc');
      
      
      
      
      
      
      
                  var decrease_button = document.querySelector('.screen2 #dec');
      
      
      
      
      
      
      
                  var AllmInputs = document.querySelectorAll('.screen2 .mInput');
      
                  var AllSelect = document.querySelector(".page_size");
      
                  for (let index = 0; index < AllSelect.length; index++) {
      
      
      
      
      
      
      
                      const element = AllSelect[index];
      
                      
      
                      element.setAttribute('onchange', "Page(this,page,'"+options+"')");
      
                  }
      
      
      
                  // console.log('AllmInputs',AllmInputs)
      
                 
      
      
      
                  for (let index = 0; index < AllmInputs.length; index++) {
      
      
      
      
      
      
      
                      const element = AllmInputs[index];
      
      
      
      
      
                      element.setAttribute('onclick', "ActiveOption(this, '"+options+"', 'mInput')");
      
      
      
      
      
      
      
                      }
      
      
      
                  // get color inputs to add function
      
      
      
                  var AllmBWColorInputs = document.querySelectorAll('.screen2  .bwcolor');
      
      
      
      
      
      
      
                  for (let index = 0; index < AllmBWColorInputs.length; index++) {
      
      
      
      
      
      
      
                      const element = AllmBWColorInputs[index];
      
      
      
      
      
      
      
                      element.setAttribute('onclick', "totalPriceCalculation('color', 'blackwhite'); ActiveOption(this, '"+options+"', 'mInput')");
      
      
      
      
      
      
      
                  }
      
      
      
                  var AllmColorInputs = document.querySelectorAll('.screen2  .colored');
      
      
      
      
      
      
      
                  for (let index = 0; index < AllmColorInputs.length; index++) {
      
      
      
      
      
      
      
                      const element = AllmColorInputs[index];
      
      
      
      
      
      
      
                      element.setAttribute('onclick', "totalPriceCalculation('color', 'color'); ActiveOption(this, '"+options+"', 'mInput')");
      
      
      
      
      
      
      
                  }
      
      
      
                  var AllmSideInputs = document.querySelectorAll('.screen2  .oneSide');
      
      
      
      
      
      
      
                      for (let index = 0; index < AllmSideInputs.length; index++) {
      
      
      
      
      
      
      
                          const element = AllmSideInputs[index];
      
      
      
      
      
      
      
                          element.setAttribute('onclick', "totalPriceCalculation('sides', 'one'); ActiveOption(this, '"+options+"', 'mInput')");
      
      
      
      
      
      
      
                  }
      
      
      
                  var AllmtwoSideInputs = document.querySelectorAll('.screen2  .twoSide');
      
      
      
      
      
      
      
                      for (let index = 0; index < AllmtwoSideInputs.length; index++) {
      
      
      
      
      
      
      
                          const element = AllmtwoSideInputs[index];
      
      
      
      
      
      
      
                          element.setAttribute('onclick', "totalPriceCalculation('sides', 'twoside'); ActiveOption(this, '"+options+"', 'mInput')");
      
      
      
      
      
      
      
                  }
      
      
      
      
      
      
      
                
      
      
      
      
      
      
      
                  var Allmodalinput = document.querySelectorAll('.screen2 .modalinput');
      
      
      
      
      
      
      
                  for (let index = 0; index < Allmodalinput.length; index++) {
      
      
      
      
      
      
      
                      const element = Allmodalinput[index];
      
      
      
      
      
      
      
                      element.setAttribute('onclick', "ActiveOption(this, '"+options+"', 'modalinput')");
      
      
      
      
      
      
      
                  }
      
      
      
      
      
      
      
                  var Allrangeinput = document.querySelectorAll('.screen2 .rangeinput');
      
      
      
      
      
      
      
                  for (let index = 0; index < Allrangeinput.length; index++) {
      
      
      
      
      
      
      
                      const element = Allrangeinput[index];
      
      
      
      
      
      
      
                      element.setAttribute('onclick', "ActiveOption(this, '"+options+"', 'rangeinput')");
      
      
      
      
      
      
      
                  }
      
      
      
                  var AllPageSelect = document.querySelectorAll('.screen2 .page');
      
      
      
      
      
                  for (let index = 0; index < AllPageSelect.length; index++) {
      
      
      
      
      
      
      
                  const element = AllPageSelect[index];
      
      
      
      
      
      
      
                  element.setAttribute('onchange', "Page(event,'page','"+options+"');");
      
      
      
      
      
      
      
                  }
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
                  options_btn.setAttribute('onclick',"Modal(this,'uploadFilesModal ."+options+"', true)");
      
      
      
      
      
      
      
                  options_close_btn.setAttribute('onclick',"Modal(this,'uploadFilesModal ."+options+"', false)");
      
      
      
      
      
      
      
                  options_close_btn2.setAttribute('onclick',"Modal(this,'uploadFilesModal ."+options+"', false)");
      
      
      
      
      
      
      
                  increase_button.setAttribute('onclick',"Copies('noofcopies','"+options+"', 'add')");
      
      
      
      
      
      
      
                  decrease_button.setAttribute('onclick',"Copies('noofcopies','"+options+"', 'subt')");
      
      
      
      
      
      
      
              document.querySelector(".screen1").classList.remove("active")
      
      
      
      
      
      
      
              document.querySelector(".screen2").classList.add("active")
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
              // screen2 range select box values populate 
      
      
      
      
      
      
      
              var start = document.getElementById("optionStartRangePrice").innerHTML
      
      
      
              var startRange = document.getElementById("startRange");
      
      
      
      
      
      
      
             for(let i=1;i<=start;i++){
      
                  
      
                  var option = document.createElement("option");
      
                  
      
                  option.text = i;
      
                  option.value = i;
      
                 
      
                  if(start<=1){
      
                      startRange.disabled = true;
      
                       option.setAttribute('selected', true);
      
      
      
                  }
      
                  startRange.add(option);
      
              };
      
      
      
            
      
      
      
      
      
      
      
              var endRange = document.getElementById("endRange");
      
      
      
      
      
      
      
              var end   = document.getElementById("optionEndRangePrice").innerHTML
      
      
      
              // if(end<=1){
      
              //     document.getElementById('toOptions').value=1;
      
              // }else{
      
              //     document.getElementById('toOptions').name=1;
      
              // }
      
      
      
              for(let i=1;i<=end;i++){
      
      
      
      
      
      
      
                  var option = document.createElement("option");
      
      
      
      
      
      
      
                  option.text = i;
      
      
      
      
      
      
      
                  option.value = i;
      
      
      
      
      
      
      
                  // option.id = "";
      
      
      
      
      
      
      
                 
      
                  if(end<=1){
      
                      endRange.disabled = true;
      
                      option.setAttribute('selected', true);
      
                      option.value=1;
      
                  }
      
                  endRange.add(option);
      
      
      
      
      
      
      
                
      
      
      
      
      
      
      
              };
      
      
      
      
      
      
      
             
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
              // screen2 range select box values populate 
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
          }else{
      
      
      
      
      
      
      
              document.querySelector(".screen1").classList.add("active")
      
      
      
      
      
      
      
              document.querySelector(".screen2").classList.remove("active")
      
      
      
      
      
      
      
              document.querySelector(".uploadFilesModal").style.display = "none"
      
      
      
      
      
      
      
              
      
              CopyTotalPrice();
      
      
      
      
      
          }
      
      
      
      
      
      
      
      }
      
      
      
      
      
      
      
      const UploadFilesModal = ()=>{
      
      
      
      
      
      
      
          document.querySelector(".uploadFilesModal").style.display = "flex"
      
      
      
      
      
      
      
      }
      
      
      
      
      
      
      
      Dropzone.autoDiscover = false; // Prevent Dropzone from auto-discovering forms
      
      
      
      
      
      
      
      var myDropzone = new Dropzone("#myDropzone", {
      
      
      
      
      
      
      
      //   url: "data:image/png;base64,", // Dummy URL to prevent errors (not used for actual uploads)
      
      
      
      
      
      
      
      params: {
      
      
      
      
      
      
      
      code: {{ $code}},
      
      
      
      
      
      
      
      phone: {{$phone}},
      
      
      
      
      
      
      
      shop: {{$shop}},
      
      
      
      
      
      
      
      // Add more fields as needed
      
      
      
      
      
      
      
      },
      
      
      
      
      
      
      
      url: "/getTotalPages",
      
      
      
      
      
      
      
      success: function(file, response) {
      
      
      
      let OptionCount = document.querySelector('.AllOptions').childElementCount;
      
      
      
      
      
      
      
      document.getElementById("optionStartRangePrice").innerHTML = response.total_pages;
      
      
      
      
      
      
      
      document.getElementById("optionEndRangePrice").innerHTML = response.total_pages;
      
      
      
      
      
      
      
      var printJob_id = response.printJob_id;
      
      
      
      
      
      
      
      var loaders = document.querySelectorAll("#loading");
      
      
      
      
      
      
      
      for(loader of loaders)
      
      
      
      
      
      
      
      {
      
      
      
      
      
      
      
      loader.src = loader.dataset.alternate;
      
      
      
      
      
      
      
      }
      
      
      
      
      
      
      
      document.getElementById("nextBtn_upload").classList.remove('disabled');
      
      document.getElementById("close_upload").disabled = false;
      
      
      
      
      
      
      
      var fileDiv = document.createElement('div');
      
      
      
      var showOption = OptionCount+1;
      
      
      
      fileDiv.className = 'File FileOption'+showOption;
      
      
      
      
      
      
      
      // fileDiv.innerHTML = 'whatsapp_file';
      
      
      
      fileDiv.innerHTML = `<div onclick="ImageOptions(this,'Option${OptionCount + 1}')">${file.name}</div><span style="margin-left:1.3em"onclick="deleteMy(this,'Option${OptionCount+1}', ${printJob_id})"> x <span>`;
      
      
      
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
      
      
      
      
      
      
      
      document.getElementById("nextBtn_upload").disabled= false;
      
      
      
      
      
      
      
      console.log(OptionCount + 1)
      
      
      
      
      
      
      
      document.getElementById("nextBtn_upload").setAttribute('onclick',`nextUploadScreen('screen1', 'Option${OptionCount + 1}', 'UploadModal${OptionCount + 1}')`)
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      var screen2 = document.querySelector(".screen2");
      
      
      
      
      
      
      
      screen2.className = "";
      
      
      
      
      
      
      
      screen2.classList.add( `container`)
      
      
      
      
      
      
      
      screen2.classList.add( `screen2`)
      
      
      
      
      
      
      
      screen2.classList.add( `Option${OptionCount + 1}`)
      
      
      
      
      
      
      
      screen2.classList.add(`UploadModal${OptionCount + 1}`)
      
      
      
      
      
      
      
      });
      
      
      
      
      
      
      
      const SetOptionsValues = (e)=>{
      
      
      
      
      
      
      
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
      
      
      
      
      
      
      
      
      
      
      
      options_btn.setAttribute('onclick',`Modal(this,'AllOptions .Option${OptionCount + 1}', true)`);
      
      
      
      
      
      
      
      options_close_btn.setAttribute('onclick',`Modal(this,'AllOptions .Option${OptionCount + 1}', false)`);
      
      
      
      
      
      
      
      document.querySelector(".screen2").classList.remove( `Option${OptionCount + 1}`)
      
      
      
      
      
      
      
      document.querySelector(".screen2").classList.remove( `UploadModal${OptionCount + 1}`)
      
      
      
      
      
      
      
      document.querySelector(`.AllOptions .Option${OptionCount + 1}`).classList.remove( `screen2`)
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      const mInput = document.querySelectorAll(`.${parent} .mInput`)
      
      
      
      
      
      
      
      for (let index = 0; index < mInput.length; index++) {
      
      
      
      
      
      
      
      const element = mInput[index];
      
      
      
      if(element.classList.contains('active')){
      
      
      
      // console.log(element.querySelector("input"))
      
      element.querySelector("input").checked = true
      
      
      
      }
      
      if(element.classList.contains('pageInputSize')){
      
      
      
      
      
      
      
      const pageA =  document.querySelector(`.${parent} .pageInputSize`)
      
      // get select box 
      
      const pageSelectBox =  document.querySelector(`.${parent} .Page`)
      
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
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      myDropzone.on('success',function(file,response) {
      
      
      
      
      
      
      
      });
      
      
      
      
      
      
      
      let indexFiles = 0
      
      
      
      
      
      
      
      
      
      
      
      myDropzone.on('addedfile', function (file) {
      
      
      
      document.getElementById("nextBtn_upload").disabled= true;
      
      
      
      document.getElementById("close_upload").disabled= true;
      
      
      
      
      
      
      
      
      
      
      
      indexFiles++
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      // Display the file in the #uploadedFiles div as an image
      
      
      
      
      
      
      
      var fileDiv = document.createElement('div');
      
      
      
      
      
      
      
      var UploadDisplay = document.createElement('template');
      
      
      
      
      
      
      
      UploadDisplay.innerHTML =`
      
      
      
      
      
      
      
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
      const ImageOptions = (e,cl)=>{
      
      
      
      
      
      
      
          // console.log(cl)
      
      
      
      
      
      
      
          document.querySelectorAll(`.Option`).forEach(element => {
      
      
      
      
      
      
      
              element.classList.remove("active")
      
      
      
      
      
      
      
          });
      
      
      
      
      
      
      
          document.querySelectorAll(`.File`).forEach(element => {
      
      
      
      
      
      
      
              element.classList.remove("active")
      
      
      
      
      
      
      
          });
      
      
      
      
      
      
      
          document.querySelector(`.AllOptions .${cl}`).classList.add("active")
      
      
      
      
      
      
      
          e.classList.add("active")
      
      
      
      
      
      
      
      }
      
      
      
      
      
      
      
              
      
      
      
      
      
      
      
                  const ActiveOption = (e,MainParent, input)=>{
      
      
      
      
      
      
      
                  let inputs = document.querySelectorAll(`.${MainParent} .${input}`);
      
                   if(inputs.length === 0)
      
                  {
      
                      console.log('no INputs')
      
                      inputs = document.querySelectorAll(`.${input}`);    
      
                  }
      
                  
      
                  if(input == 'payInput')
      
                  {
      
                      document.querySelector('#pay').removeAttribute('disabled')
      
                  }
      
                  
      
      
      
      
      
                  // const dummy_inputs = document.querySelectorAll(`.screen2 .${input}`);
      
                  for (let index = 0; index < inputs.length; index++) {
      
      
      
      
      
      
      
                      const element = inputs[index];
      
      
      
      
      
      
      
                       element.classList.remove("active")
      
                      
      
                      var check_input = element.querySelector("input");
      
                      if(check_input)
      
                      {
      
                         check_input.checked = false;
      
                      }
      
      
      
      
      
                      if(element.classList.contains("pageInputSize")){
      
                          const pageValueGet = element.classList.contains("pageInputSize").value;
      
                          console.log('pageValueGet',pageValueGet)
      
                          // element.querySelector("input").checked = false
      
                      }
      
                    
      
      
      
      
      
                  }
      
                  
      
                 
      
      
      
      
      
                 
      
      
      
                  e.classList.add("active")
      
      
      
      
      
      
      
                  if(e.classList.contains("active")){
      
      
      
      
      
      
      
                      e.querySelector("input").checked = true
      
      
      
      
      
      
      
                  }
      
                  
      
                   
      
      
      
                  console.log('MainParent',MainParent)
      
      
      
                  updatePrice(MainParent)
      
      
      
      
      
      
      
                 }
      
      
      
      
      
                 const Copies = (e,MainParent, value)=>{
      
      
      
                  console.log('option name',MainParent)
      
                 
      
                  
      
      
      
                  // e = noofcoopies
      
      
      
                  const p = document.querySelector(`.${MainParent} .${e}`)
      
      
      
                  console.log('element',p)
      
      
      
      
      
                  const copy = document.querySelector(`.${MainParent} #copies`)
      
      
      
                  console.log('copy',copy)
      
      
      
                  
      
                  if(value == "add"){
      
                    
      
                      p.innerHTML = parseInt(p.innerHTML) + 1;
      
                      console.log(p.innerHTML)
      
                      copy.value = p.innerHTML ;
      
                      // console.log(copy.value)
      
      
      
      
      
                  }else {
      
                      if(p.innerHTML == 0) return
      
      
      
                      p.innerHTML =  parseInt(p.innerHTML) - 1
      
                      copy.value = parseInt(p.innerHTML);
      
      
      
                  }
      
                  
      
                  
      
                  updatePrice(MainParent)
      
      
      
                 }
      
      
      
      
      
              const closeCash = (e) =>
      
              {
      
                  let modal = e.parentElement;
      
                  let modalParent = modal.parentElement;
      
                  
      
                  modal.style.display = 'none';
      
                  modalParent.style.display = 'none';
      
              }
      
      
      
      
      
      
      
                 const Modal = (e,Mainparent,value)=>{
      
      
      
      
      
      
      
                  // console.log(Mainparent)
      
      
      
      
      
      
      
                  //console.log(e.parentElement)
      
                  
      
                  
      
      
      
      
      
      
      
                 let modal = document.querySelector(`.${Mainparent} .modal`)
      
      
      
      
      
      
      
              if(value){
      
      
      
      
      
      
      
                  modal.style.display = "flex"
      
      
      
      
      
      
      
              }else{
      
      
      
      
      
      
      
                  
      
      
      
      
      
      
      
                  modal.style.display = "none"
      
      
      
      
      
      
      
              }
      
      
      
      
      
      
      
                  // console.log(modal)
      
      
      
      
      
      
      
                  
      
      
      
      
      
      
      
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
      
                  const pay_method = getSelectedPaymentMethod()
      
                  // alert(pay_method)
      
                  const form = document.querySelector('#form1')
      
                  if(value){
      
      
      
                      if (pay_method == 'cash') {
      
                          modal.style.display = "flex"
      
                          document.getElementById("pay").setAttribute('type','button');
      
                      } else {
      
                          modal.style.display = "none"
      
                          document.getElementById("pay").setAttribute('type','submit');
      
                          // const form = document.querySelector('#form1')
      
                          form.submit()
      
                      }
      
                  }else{
      
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
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      $(".close").on('click', function(event){
      
           $(".uploadFilesModal").hide();
      
      });
      
      
      
      $(".classbtn").on('click', function(event){
      
           $(".UploadModal0").hide();
      
      });
      
      
      
      const confirmSubmit = ()=>{
      
          const form = document.querySelector('#form1')
      
          form.submit()
      
      }
      
      
      
      
      
   </script>
   <script>
      @if(session()->has('modal_code'))
      
          $(document).ready(function() {
      
              $('#cashOrder').show();
      
             
      
          });
      
      @endif
      
      
      
      const successModal = ()=>{
      
      
      
      $.ajax({
      
          type: 'POST',
      
          url: '{{ route('remove_modal_session') }}', // Define a Laravel route for this purpose
      
          data: {
      
              '_token': '{{ csrf_token() }}',
      
          },
      
          success: function(data) {
      
          }
      
      });
      
      
      
      document.querySelector(".successModal").style.display = "none"
      
      
      
      
      
      
      
      }
      
   </script>
</body>
</html>
@endsection