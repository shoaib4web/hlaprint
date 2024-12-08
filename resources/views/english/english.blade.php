@extends('english.main_document')



@section('content')



@section('page', 'document')



@section('title', 'Document')



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



    <style>



        .UploadFiles{



    height: auto !important;



    min-height: 18.4rem;



}



.dropzone{



    margin-top: 0;



    display: none;



    border: 0;



    min-height: auto;    flex-wrap: wrap;



    padding: 0;



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



    </style>



    



    </div>



    <section class="document">



        

    <div class="left">

<img src="{{ asset('public/assets/img/lines1.png') }}" class="line1" alt="">

<img src="{{ asset('public/assets/img/lines2.png ') }}" class="line2" alt="">

<div class="header">

    <img src="{{ asset('public/assets/img/logo.png') }}" alt="">

    

    @if($shop)

    <a href="{{route('getArabicOptions',[$code,$shop])}}" class="btn">العربية</a>

   

    @else

    <a href="{{route('getArabicOptions',[$code])}}" class="btn">العربية</a>

    

    @endif

</div>

<img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">

</div>



        <div class="right">



            <div class="uploadFilesModal" >



                <div class="container screen1 active">



                    <div class="uploadBox">



                        <img src="{{ asset('public/assets/img/upload.png')}}" alt="">



                        <p>Upload your file <span> here</span></p>



                        <button>Upload



                            <input id="fileupload"  type="file" name="file[]" accept=".xls, .xlsx, .docx, .doc, .pdf, .jpeg, .jpg, .png" required>



                        </button>



                    </div>



                    <div class="uplaodedFilesDisplay">



                        



                    </div>



                    <button class="next" id="nextBtn_upload" onclick="nextUploadScreen('screen1')">Next</button>



                </div>



                <div class="container screen2">



                    <div class="box color">



                        <div class="top">



                            <h3>



                                Select Color



                            </h3>



                        </div>



                            <div class="input mInput  blackwhite" onclick="totalPriceCalculation('color', 'blackwhite'); ActiveOption(this, 'mInput', 'blackwhite')">



                                <div class="price">



    



                                    <p class="title">Black/White</p>



                                    <p id="bwPrice">{{isset($pricing)?$pricing->black_and_white_amount:"2"}}</p>



                                </div>



                                <input type="checkbox" class="checkbox" value="false">



                            </div>



                            <div class="input mInput" onclick="totalPriceCalculation('color', 'color'); ActiveOption(this, 'mInput', 'color') "> 



                                <div class="price">



    



                                    <p class="title">Color</p>



                                    <p>{{isset($pricing)?$pricing->color_page_amount:"2"}} </p>



                                </div>



                                <input type="checkbox"  class="checkbox" value="true">



                            </div>



                            <div class="options" onclick="Modal(event,'Option0', true)">



                                <img src="{{ asset('public/assets/img/plus.png')}}" alt="">



                                <p>Advanced Options</p>



                            </div>



                    </div>



                    <div class="box copies">



                            <div class="top">



                                <h3 >



                                    No. Of Copies



                                </h3>



                            </div>



                                <div class="input">



    



                                    <button onclick="Copies('noofcopies','Option0', 'add')">+</button>



                                    <p class="noofcopies" style="margin-bottom:0">1</p>



                                    <button type="button" onclick="Copies('noofcopies','Option0', 'subt')">-</button>



                                </div>



                        </div>



                        <div class="box copies total">



                            <div class="top">



                                <h3>



                                    Total



                                </h3>



                            </div>



                                <div class="input">



    



                                    <p class="totalPriceValue">1.00 SAR</p>



                                </div>



                        </div>



                        <button class="next" onclick="nextUploadScreen('screen2')">



                            Save



                        </button>



                </div>



            </div>



            



            <div class="container " >



                <!-- <form action="" class="dropzone" id="my-awesome-dropzone">







                    <input type="file" name="file" id="uploadFiles">



                </form> -->

                <form method="post" action="{{ route('eSubmitDocument') }}" id="form1">

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



                    Select Printing Options



                    <div class="wa d-flex">



                    <button onclick="UploadFilesModal()" type="button"> 



                       



                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">



                        <path d="M8 1C6.15042 1.02231 4.38287 1.76698 3.07493 3.07493C1.76698 4.38287 1.02231 6.15042 1 8C1.02231 9.84958 1.76698 11.6171 3.07493 12.9251C4.38287 14.233 6.15042 14.9777 8 15C9.84958 14.9777 11.6171 14.233 12.9251 12.9251C14.233 11.6171 14.9777 9.84958 15 8C14.9777 6.15042 14.233 4.38287 12.9251 3.07493C11.6171 1.76698 9.84958 1.02231 8 1ZM12 8.5H8.5V12H7.5V8.5H4V7.5H7.5V4H8.5V7.5H12V8.5Z" fill="#00448E"/>



                      </svg>  Add File</button>



                    </div>



                </h2>



                <div class="box uploadFiles">    



                    <div class="top">



                        <h3>Upload Document</h3>



                    </div>



                    <div class="dropzone" id="myDropzone">



                    </div> 



                </div>



           

            <div class="AllOptions">







            <div class="Option Option0 active">



                    



                    <div class="box color">



                        <div class="top">



                            <h3>



                                Select Color



                            </h3>



                        </div>



                            <div class="input mInput  blackwhite" onclick="totalPriceCalculation('color', 'blackwhite'); ActiveOption(this, 'mInput', 'blackwhite')">



                                <div class="price">







                                    <p class="title">Black/White</p>



                                    <p>{{isset($pricing)?$pricing->black_and_white_amount:"0.333"}}</p><span>.00 SAR</span>



                                </div>



                                <input type="checkbox" name="color"  value="false" class="checkbox" >



                            </div>



                            <div class="input mInput" onclick="totalPriceCalculation('color', 'color'); ActiveOption(this, 'mInput', 'color') "> 



                                <div class="price">







                                    <p class="title">Color</p>



                                    <p>{{isset($pricing)?$pricing->color_page_amount:"0.333"}}</p><span>.00 SAR</span>



                                </div>



                                <input type="checkbox" name="color" value="true" class="checkbox" >



                            </div>



                            <div class="options"  onclick="Modal(event,'Option0', true)">



                                <img src="{{ asset('public/assets/img/plus.png')}}" alt="">



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







                                <button onclick="Copies('noofcopies','Option0', 'add')">+</button>



                                <p class="noofcopies">1</p>

                                



                                <button type="button" onclick="Copies('noofcopies','Option0', 'subt')">-</button>



                            </div>

                            <input type="hidden"  value="1" name="copies" id="copies">



                    </div>



                    <div class="box copies total">



                        <div class="top">



                            <h3>



                                Total



                            </h3>



                        </div>



                            <div class="input">







                                <!-- <p>1.00 SAR</p> -->

                                <p><span class="totalPriceValue">1</span> SAR</p>

                                <input hidden type="text" id="price" value="" name="price" >

                           



                            </div>



                    </div>

                <div class="modal" style="    position: absolute;



                width: 100vw;



                height: 100vh;



                top: 0;



                left: 0;">



                    <div class="container">



                        <div class="close" onclick="Modal(event,'Option0', false)">



                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">



                                <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E"/>



                            </svg>



                        </div>



                        <h2 class="mainHeading">Advanced Options</h2>



                        <p  id="singlePrice${i}">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>







                        <p hidden id="doublePrice${i}">{{isset($pricing)?$pricing->double_side:"0.333"}} </p>



                        <div class="row">



                            <h3>Select Side</h3>



                            <div class="inputs" >







                                <div class="input modalinput" onclick="ActiveOption(this,'Option0', 'modalinput')">



                                    <p class="title">One Sided {{isset($pricing)?$pricing->one_side:"0.333"}} .00 SAR </p>



                                    <input type="checkbox" name="sides" value="one" class="checkbox">



                            </div>



                            <div class="input modalinput" onclick="ActiveOption(this,'Option0', 'modalinput')">



                                <p class="title">Two Sided {{isset($pricing)?$pricing->double_side:"0.333"}} .00 SAR</p>



                                <input type="checkbox" name="sides[]" value="two" class="checkbox">



                            </div>



                        </div>



                        </div>



                        <div class="row range">

                        @php 

                            $range = range(1, $printJob->total_pages);

                            $start = $range[0];

                            $end = end($range);

                        @endphp



                        

                        <p   id="startRangePrice">{{$start}} </p>

                        <p   id="endRangePrice">{{$end}} </p>



                        <p hidden id="singlePrice">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>







                        <p hidden id="doublePrice">{{isset($pricing)?$pricing->double_side:"0.333"}} </p>



                        <p hidden  id="startRangePrice"> </p>

                        <p hidden  id="endRangePrice"> </p>



                            <h3>Page Range</h3>







                            <div class="input rangeinput" onclick="ActiveOption(this,'Option0', 'rangeinput')">



                                    <p class="title">All</p>



                                    <input type="checkbox" name="range" value="all" class="checkbox">



                            </div>



                            <div class="input  input2" >



                                <div class="custom rangeinput" onclick="ActiveOption(this,'Option0', 'rangeinput')">







                                    <p class="title">Custom</p>



                                    <input  @if($printJob->total_pages <= '1') disabled @endif type="checkbox" name="range" value="custom" class="checkbox">



                                </div>



                                <div class="pages">

                            <div class="from">

                              

                                <p style="margin-bottom:0">From</p>

                                <select @if($printJob->total_pages <= '1') disabled @endif class="frompage" name="from" id="startRange" onchange="PageRange(event, 'from')" >

                                @if($printJob->total_pages > '1') 

                                    @for($i=1; $i<=$printJob->total_pages ; $i++)

                                      <option value="{{$i}}">{{$i}}</option>

                                    @endfor

                                    

                                @endif

                                </select>

                            </div>

                            <div class="to">



                                <p style="margin-bottom:0">To</p>

                                <select @if($printJob->total_pages <= '1') disabled @endif name="to" class="topage" id="endRange" onchange="PageRange(event, 'to')">

                                @if($printJob->total_pages > '1') 

                                    @for($i=1; $i<=$printJob->total_pages ; $i++)

                                      <option value="{{$i}}">{{$i}}</option>

                                    @endfor

                                    

                                @endif

                                </select>

                            </div>

                        </div>



                            </div>



                        </div>



                        <div class="row size">

                        @php $data = unserialize($pricing->size_amount); @endphp



                            <h3>Page Size</h3>

                            <select name="page_size" class="page_size" id="" onchange="Page(event, 'page')">

                            @foreach ($data as $size => $amount)


                            <option  @if($size == 'A4') selected @endif value="{{ $size}}">{{ $size }} @if($size != 'A4') <span style= " font-weight: 600;!important">( + {{ $amount }} SAR)</span> @endif</option>

                            @endforeach

                            </select>



                        </div>



                        <button type="button">DONE</button>



                        </div>



                </div>



            </div>







            </div>







                <div class="box payment">



                    <div class="top">



                        <h3>



                            Select Payment Options



                        </h3>



                    </div>



                        <div class="input payInput" onclick="ActiveOption(this, 'payInput')">



                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">



                                <path d="M17.0499 20.28C16.0699 21.23 14.9999 21.08 13.9699 20.63C12.8799 20.17 11.8799 20.15 10.7299 20.63C9.28992 21.25 8.52992 21.07 7.66992 20.28C2.78992 15.25 3.50992 7.59003 9.04992 7.31003C10.3999 7.38003 11.3399 8.05003 12.1299 8.11003C13.3099 7.87003 14.4399 7.18003 15.6999 7.27003C17.2099 7.39003 18.3499 7.99003 19.0999 9.07003C15.9799 10.94 16.7199 15.05 19.5799 16.2C19.0099 17.7 18.2699 19.19 17.0399 20.29L17.0499 20.28ZM12.0299 7.25003C11.8799 5.02003 13.6899 3.18003 15.7699 3.00003C16.0599 5.58003 13.4299 7.50003 12.0299 7.25003Z" class="svgs"/>



                              </svg>



                            <p>Apple Pay</p>



                            <input type="checkbox" name="type"   class="checkbox">



                        </div>



                        <div class="input payInput" onclick="ActiveOption(this, 'payInput')">



                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">



                                <path d="M1 9C1 5.229 1 3.343 2.172 2.172C3.343 1 5.229 1 9 1H13C16.771 1 18.657 1 19.828 2.172C21 3.343 21 5.229 21 9C21 12.771 21 14.657 19.828 15.828C18.657 17 16.771 17 13 17H9C5.229 17 3.343 17 2.172 15.828C1 14.657 1 12.771 1 9Z" class="svgs" stroke-width="1.5"/>



                                <path d="M9 13H5M13 13H11.5M1 7H21" class="svgs" stroke-width="1.5" stroke-linecap="round"/>



                              </svg>



                            <p>Credit Card</p>



                            <input type="checkbox" name="type" value="online" class="checkbox">



                        </div>



                        <div class="input payInput" onclick="ActiveOption(this, 'payInput')">



                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">



                                <path d="M12.0001 7.7143V5.14287M12.0001 7.7143C10.5773 7.7143 9.42871 7.7143 9.42871 9.42858C9.42871 12 14.5716 12 14.5716 14.5714C14.5716 16.2857 13.423 16.2857 12.0001 16.2857M12.0001 7.7143C13.423 7.7143 14.5716 8.36573 14.5716 9.42858M9.42871 14.5714C9.42871 15.8572 10.5773 16.2857 12.0001 16.2857M12.0001 16.2857V18.8572" class="svgs" stroke-linecap="round" stroke-linejoin="round"/>



                                <path d="M12 23.1429C18.1541 23.1429 23.1429 18.154 23.1429 12C23.1429 5.84597 18.1541 0.857147 12 0.857147C5.846 0.857147 0.857178 5.84597 0.857178 12C0.857178 18.154 5.846 23.1429 12 23.1429Z" class="svgs" stroke-linecap="round" stroke-linejoin="round"/>



                              </svg>



                            <p>Cash</p>



                            <input type="checkbox" name="type" value="cash" class="checkbox">



                        </div>



                        



                </div>



                <button class="pay">



                    CONFIRM PAY



                </button>



            </form>



        </form>











        <div class="modal confirmModal" id="confirmModal" >



            <div class="container" style="display: block;">



            <div class="close" onclick="Modal(event, false)">



                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"



                        fill="none">



                        <path



                            d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"



                            fill="#00448E" />



                    </svg>



            </div>



                <h2 class="mainHeading">Cash</h2>











                <div class="row range">



                    <p class="title">Full Name</p>



                    <input type="text" name="name" class="name_input" placeholder="Enter your full name">



                </div>



                <button type="submit">DONE</button>



            </div>



        </div>



        <div class="modal confirmModal" id="" >



            <div class="container">



            <div class="close" onclick="Modal(event, false)">



                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"



                        fill="none">



                        <path



                            d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"



                            fill="#00448E" />



                    </svg>



            </div>



                <img src="{{ asset('public/assets/img/lineIcon.png')}}" alt="" style="width: 9.2rem; height: 9.2rem;">



                    <p class="infoP">please visit the counter to pay and collect your print</p>



                <button type="submit">Ok</button>



            </div>



        </div>



    </section>







    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>



    <script>

        





let color = "blackwhite";

let sides = "oneside";

const  totalPriceValue = document.querySelector(".totalPriceValue")

const  noofcopies = document.querySelector(".noofcopies")

const  frompage = document.querySelector(".frompage")

const  topage = document.querySelector(".topage")

const  page_size_value = document.querySelector(".page_size")





const totalPriceCalculation = (varname, value)=>{

  

if(varname == "color"){

    color = value

}else if(varname == "sides"){

    sides = value

}

console.log(value)

}





const calculatePrice = () => {

    const numCopies = parseInt(noofcopies.innerHTML);

    // const fromPage = parseInt(frompage.value);

    const fromPage = document.getElementById("startRangePrice").innerHTML;

   

    const toPage  = document.getElementById("endRangePrice").innerHTML;



    const totalPages = toPage - fromPage + 1;

    const page_size_price  = parseInt(page_size_value.value);



    let basePrice = 0;



    if (color === "blackwhite") {

        var bwPrice = document.getElementById("bwPrice").innerHTML;

        basePrice = bwPrice; 

    } else if (color === "color") {

        var colorPrice =document.getElementById("colorPrice").innerHTML;

        basePrice = colorPrice; 

        console.log(basePrice)

    }

    

    let sidednessMultiplier = 0;

    if (sides === "oneside") {

         sidednessMultiplier = document.getElementById("singlePrice").innerHTML;

        // sidednessMultiplier = 1;

        console.log("one")

    }else if (sides == "twoside"){

         sidednessMultiplier =document.getElementById("doublePrice").innerHTML;

        // sidednessMultiplier = 2; 

        console.log("two")

    }

    

    console.log(numCopies,totalPages,basePrice,sidednessMultiplier,page_size_price)

    const totalPrice = numCopies * totalPages * (parseInt(basePrice) + parseInt(sidednessMultiplier) + page_size_price);

    // console.log(numCopies, totalPages, (parseInt(basePrice) + parseInt(sidednessMultiplier) + parseInt(page_size_price)))

    return totalPrice;

    // console.log(totalPrice)



};

// calculatePrice()

const updatePrice = () => {

    const price = calculatePrice();

    

    var t = price+'.00';

    totalPriceValue.textContent = t;

    console.log(price)

    

    $("#price").val(price);



};

updatePrice()



        

let fromPageValue = 1

let toPageValue = 2



const PageRange = (e, value)=>{

    if(value == "from"){

        fromPageValue = e.target.value

        document.getElementById("startRangePrice").innerHTML = fromPageValue

    }else{



        toPageValue = e.target.value

        console.log('toPageValue',toPageValue)

        document.getElementById("endRangePrice").innerHTML = toPageValue

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



      



    </script>



    <script>



        const nextUploadScreen = (value)=>{



            if(value == "screen1"){







                document.querySelector(".screen1").classList.remove("active")



                document.querySelector(".screen2").classList.add("active")



            }else{



                document.querySelector(".screen1").classList.add("active")



                document.querySelector(".screen2").classList.remove("active")



                document.querySelector(".uploadFilesModal").style.display = "none"







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

        document.getElementById("startRangePrice").innerHTML = response.total_pages;

        document.getElementById("endRangePrice").innerHTML = response.total_pages;

    },

  autoProcessQueue: true, // Disable automatic uploading



  maxFiles: 10, // Set a maximum number of files



  clickable: false, // Disable clicking to open the file dialog



});



var fileDiv = document.createElement('div');

fileDiv.className = 'File';

fileDiv.innerHTML = 'whatsapp_file';

fileDiv.setAttribute("onclick", `ImageOptions(this,'Option0')`)

document.getElementById('myDropzone').appendChild(fileDiv);



document.getElementById('fileupload').addEventListener('change', function (e) {



    document.querySelector(".uploadFiles").style.display = "block"

    

    // alert(WFile)

 

  var files = e.target.files;

  for (var i = 0; i < files.length; i++) {



    myDropzone.addFile(files[i]);



    const AllOptions = document.querySelector(".AllOptions")



    const template = document.createElement("template")



    template.innerHTML = `



    <div class="Option Option${i+1}">



                    



                    <div class="box color">



                        <div class="top">



                            <h3>



                                Select Color



                            </h3>



                        </div>



                            <div class="input mInput  blackwhite" onclick="totalPriceCalculation('color', 'blackwhite'); ActiveOption(this, 'mInput', 'blackwhite')">



                                <div class="price">







                                    <p class="title">Black/White</p>



                                    <p id="bwPrice${i+1}">{{isset($pricing)?$pricing->black_and_white_amount:"0.333"}} </span>SAR



                                </div>



                                <input type="checkbox" name=color${i+1} class="checkbox">



                            </div>



                            <div class="input mInput"  onclick="ActiveOption(this,'Option${i+1}', 'mInput')"> 



                                <div class="price">







                                    <p class="title">Color</p>



                                    <span id ="colorPrice">{{isset($pricing)?$pricing->color_page_amount:"2"}} </span>SAR



                                </div>



                                <input type="checkbox" name=color${i+1} class="checkbox">



                            </div>



                            <div class="options"  onclick="Modal(event,'Option${i+1}', true)">



                                <img src="{{ asset('public/assets/img/plus.png')}}" alt="">



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







                                <button onclick="Copies('noofcopies','Option${i+1}', 'add')">+</button>



                                <p class="noofcopies">1</p>



                                <button type="button" onclick="Copies('noofcopies','Option${i+1}', 'subt')">-</button>



                            </div>



                    </div>



                    <div class="box copies total">



                        <div class="top">



                            <h3>



                                Total



                            </h3>



                        </div>



                            <div class="input">







                                <p><span class="totalPriceValue">1</span></p>

 

                            </div>



                    </div>











































                <div class="modal" style="    position: absolute;



    width: 100vw;



    height: 100vh;



    top: 0;



    left: 0;">



        <div class="container">



            <div class="close" onclick="Modal(event,'Option${i+1}', false)">



                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">



                    <path d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z" fill="#00448E"/>



                  </svg>



            </div>



            <h2 class="mainHeading">Advanced Options</h2>



            <div class="row">



                <h3>Select Side</h3>



                <div class="inputs" >

                

                     <p hidden id="singlePrice${i}">{{isset($pricing)?$pricing->one_side:"0.333"}} </p>







                    <p hidden id="doublePrice${i}">{{isset($pricing)?$pricing->double_side:"0.333"}} </p>



                    <p   id="startRangePrice${i}"> </p>

                    <p   id="endRangePrice${i}"> </p>







                    <div class="input modalinput" onclick="ActiveOption(this,'Option${i+1}', 'modalinput')">



                        <p class="title">One Sided <span class = "design">( + {{isset($pricing)?$pricing->one_side:"0.333"}} SAR) </span></p>





                        <input type="checkbox" name="sides${i+1}"  value="one" class="checkbox">



                </div>



                <div class="input modalinput" onclick="ActiveOption(this,'Option${i+1}', 'modalinput')">



                <p class="title" style="margin-bottom:0">Two Sided <span class = "design">( + {{isset($pricing)?$pricing->double_side:"0.333"}} SAR)</span></p>





                    <input type="checkbox" name="sides[${i+1}]" value="two"  class="checkbox">



                </div>



            </div>



            </div>



            <div class="row range">



                <h3>Page Range</h3>







                <div class="input rangeinput" onclick="ActiveOption(this,'Option${i+1}', 'rangeinput')">



                        <p class="title">All</p>



                        <input type="checkbox" class="checkbox">



                </div>



                <div class="input  input2" >



                    <div class="custom rangeinput" onclick="ActiveOption(this,'Option${i+1}', 'rangeinput')">







                        <p class="title">Custom</p>



                        <input type="checkbox" class="checkbox">



                    </div>



                    <div class="pages">



                        <div class="from">







                            <p>From</p>



                            <select class="frompage" name="from${i}" id="startRange" onchange="PageRange(event, 'from')" >





                                <option value="">1</option>



                                <option value="">2</option>



                                <option value="">3</option>



                            </select>



                        </div>



                        <div class="to">







                            <p>To</p>



                            <select name="to${i}" class="topage" id="endRange" onchange="PageRange(event, 'to')">



                                <option value="">1</option>



                                <option value="">2</option>



                                <option value="">3</option>



                            </select>



                        </div>



                    </div>



                </div>



            </div>



            <div class="row size">



                <h3>Page Size</h3>



                @php $data = unserialize($pricing->size_amount); @endphp







                <select name="page_size${i}" class="page_size" id="" onchange="Page(event, 'page')">







                @foreach ($data as $size => $amount)





                    <option  @if($size == 'A4') selected @endif value="{{ $size }}">{{ $size }} @if($size != 'A4') <span style= " font-weight: 600;!important">( + {{ $amount }} SAR)</span> @endif</option>

                    





                @endforeach







                    







                </select>



            </div>



            <button type="button">DONE</button>



            </div>



    </div>



</div>







    `



    AllOptions.appendChild(template.content.firstElementChild)



AllOptions.querySelector(".Option:nth-child(1)").classList.add("active")







}



});







myDropzone.on('success',function(file,response) {



    document.getElementById("nextBtn_upload").disabled= false;



    console.log('Option'+indexFiles+ ' startRangePrice');



    document.querySelector('.Option'+indexFiles+ ' #startRangePrice').innerHTML = "<option>10</option>";



    document.querySelector('.Option'+indexFiles+ " #endRangePrice").innerHTML = "<option>10</option>";



});







let indexFiles = 0



 myDropzone.on('addedfile', function (file) {



    document.getElementById("nextBtn_upload").disabled= true;



   



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



                    <img src="{{ asset('public/assets/img/delete.png')}}" style="margin-left: auto;" alt="">



                </div>



  `



//   var img = document.createElement('img');



//   img.src = URL.createObjectURL(file);



  fileDiv.className = 'File';



  fileDiv.innerHTML = file.name;



  console.log(UploadDisplay)



  fileDiv.setAttribute("onclick", `ImageOptions(this,'Option${indexFiles}')`)



  



//   fileDiv.appendChild(img);



  document.getElementById('myDropzone').appendChild(fileDiv);



  document.querySelector('.uplaodedFilesDisplay').appendChild(UploadDisplay.content.firstElementChild);



});







</script>



<script>



const ImageOptions = (e,cl)=>{



    console.log(cl)



    document.querySelectorAll(`.Option`).forEach(element => {



        element.classList.remove("active")



    });



    document.querySelectorAll(`.File`).forEach(element => {



        element.classList.remove("active")



    });



    document.querySelector(`.${cl}`).classList.add("active")



    e.classList.add("active")



}



        const ActiveOption = (e,MainParent, input)=>{



            // const inputs = document.querySelectorAll(`.${MainParent} .${input}`)

            const inputs = document.querySelectorAll(`.${input}`)

            

            for (let index = 0; index < inputs.length; index++) {



                const element = inputs[index];

                

                

                element.classList.remove("active")



                for (let index = 0; index < element.childNodes.length; index++) {



                    const element2 = element.childNodes[index];



                    



                    if(element2.classList != undefined  && element2.classList.contains("checkbox")){



                        element2.checked = false



                    }



                }



                



            }



            e.classList.add("active")



            for (let index = 0; index < e.childNodes.length; index++) {



                const element = e.childNodes[index];



                // console.log( e.childNodes[1].classList != undefined  && e.childNodes[1].classList.contains("title"))



            if(element.classList != undefined  && element.classList.contains("title")){



                // element.classList.add("active")



            }



            if(element.classList != undefined  && element.classList.contains("checkbox")){



                element.checked = true



            }







            



           }

           console.log('element',e)

           updatePrice()



           }



           const Copies = (e,MainParent, value)=>{



            const p = document.querySelector(`.${MainParent} .${e}`)

            const copy = document.querySelector(`.${MainParent} #copies`)



            if(value == "add"){



                p.innerHTML = parseInt(p.innerHTML) + 1

                copy.value = parseInt(p.innerHTML);



            }else {



                if(p.innerHTML == 0) return



                p.innerHTML =  parseInt(p.innerHTML) - 1

                copy.value = parseInt(p.innerHTML);







            }



           }











           const Modal = (e,Mainparent,value)=>{



            const modal = document.querySelector(`.${Mainparent} .modal`)



            if(value){



                console.log(modal)



                modal.style.display = "flex"



            }else{



                modal.style.display = "none"



            }







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









</script>









</body>



</html>



@endsection