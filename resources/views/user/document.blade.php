@extends('user.home')
@section('content')
<title>Document</title>
<link rel="stylesheet" href="{{url('/public/storage')}}/css/document.css">
</head>

<body>
    <section class="w-100 m-auto document">
        <div class="container  m-auto"> 

        <div class="header d-flex justify-content-center align-items-center">
            <p class="">Welcome to <br>
            Our Printing Shop</p>
            <img src="{{url('/public/storage')}}/img/logo.png" alt="" class="logo ms-5">
            <div class="language d-flex justify-content-end align-items-right text-right ">
               <button><img src="{{url('/public/storage')}}/img/us.png" alt=""><p> English ></p></button>
               <button><img src="{{url('/public/storage')}}/img/arab.png" alt=""><p> العربية ></p></button>
            </div>
        </div>

 
        <div class="main d-flex justify-content-center align-items-start">
            <div class="documents">
                <div class="top">
                    <p class="mainText">Please </p>
                <h2 class="">Review Your File</h2>
                <p class="doc d-flex justify-content-start align-items-start"><img src="{{url('/public/storage')}}/img/pdficon.png" alt=""> Document Name</p>
                </div>
                <div class="documentFiles overflow-scroll">
                    <!-- <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt="">
                    <img src="{{url('/public/storage')}}/img/doc.png" alt=""> -->
                    <embed src="{{$file}}" height="243" width="547" type="application/pdf" style="margin-bottom: 20px;">
                    </div>
            </div>
        <div>
            <form method="post" action="submitDocument">@csrf
                <input type="hidden" name="code" value="@php echo $user->code; @endphp">
                <input type="hidden" name="file" value="@php echo $file; @endphp">
                <input type="hidden" name="phone" value="@php echo $user->phone; @endphp">
            <div class="settings">
                <div class="d-flex options">
                    <p>Select Color:</p>
                    <div class="d-flex input">
                        <input name="color" id="blackwhite" value="blacknWhite" type="radio" value="blacknWhite" onchange="(uncheck('color'))">
                        <p>Black/White</p>
                    </div>
                    <div class="d-flex input">
                        <input name="color" value="color" type="radio" id="color"  onchange="(uncheck('blackwhite'))">
                        <p>Color</p>
                    </div>
                </div>
                <div class="d-flex options">
                    <p>Sides:</p>
                    <div class="d-flex input">
                        <input name="sides" value="oneside" type="radio" id="oneside"  onchange="(uncheck('twoside'))">
                        <p>One Sided</p>
                    </div>
                    <div class="d-flex input">
                        <input name="sides" value="twosides" type="radio" id="twoside"  onchange="(uncheck('oneside'))">
                        <p>Two Sided</p>
                    </div>
                </div>
                 <div class="d-flex options">
                    <p>Page Range:</p>
                    <div class="d-flex input">
                        <input type="radio" id="all"  onchange="(uncheck('custom', 'all'))">
                        <p>All</p>
                    </div>
                    <div class="d-flex input">
                        <input type="radio" id="custom"  onchange="(uncheck('all', 'custom'))">
                        <p>Custom</p>
                    </div>
                </div>
                <div class="Pages " id="CopiesNumber">
                    <p>Or</p>
                    <div class="d-flex justify-content-center align-items-center">
                        <p style="margin-right: 2rem; margin-left: 0; font-weight: 400;" >From</p>
                        <input type="number" class="number">
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <p style="margin-right: 2rem; margin-left: 7rem; font-weight: 400;">To</p>
                        <input type="number" class="number">

                    </div>
                    
                </div> 
                <div class="d-flex Pages">
                    <div class="d-flex justify-content-center align-items-center">
                    <p style="margin-right: 3.1rem; margin-left: 0">No of copies:</p>
                    <input name="copies" type="number" class="number">

                    </div>                    
                </div>
            </div>
            <div class="amount d-flex justify-content-center align-items-center">
                <h2>Total:<span>10 SAR</span></h2>   
                <div class="d-flex">
                    <a href="{{route('code')}}" class="d-flex justify-content-center align-items-center back"> <</button>
                        <input class="d-flex justify-content-center align-items-center pay" type="submit" value="Confirm Pay">
                    </div> 
            </div>
        </div>
    </form>
        </div>
        <p class="date m-auto text-center">May 08, 2023</p>
       </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


    <script>
        const uncheck = (id, e)=>{
                document.getElementById(id).checked = false
                if(e == "custom"){
                document.getElementById("CopiesNumber").style.opacity = "1"
                const inputs = document.querySelectorAll("#CopiesNumber input")
                for (let index = 0; index < inputs.length; index++) {
                    const element = inputs[index];
                    element.disabled = false
                    
                }
                console.log("custom")
            }else if(e == "all"){
                
                console.log("all")
                document.getElementById("CopiesNumber").style.opacity = "0.5"
                const inputs = document.querySelectorAll("#CopiesNumber input")
                for (let index = 0; index < inputs.length; index++) {
                    const element = inputs[index];
                    element.disabled = true
                    
                }
                }
        }
    </script>
@endsection