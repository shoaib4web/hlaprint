@extends('user.home')
@section('content')
<title>PrintAway | Code</title>
<link rel="stylesheet" href="{{url('/public/storage')}}/css/code.css">
</head>
<body>
    <section class="w-100 m-auto code">
        <div class="container  m-auto"> 
        <div class="header d-flex justify-content-center align-items-center">
            <p class="">Welcome to <br>
            Our Printing Shop</p>
            <img src="{{url('/public/storage')}}/img/logo.png" alt="" class="logo ms-5">
            <div class="contact d-flex justify-content-end align-items-right text-right ">
                <p class="text-end"> Customer Support <br>
                    +966 59 101 3248</p>
                <img src="{{url('/public/storage')}}/img/contact.png" alt="">
            </div>
        </div>
        <div class="middle d-flex justify-content-between align-items-start">
            <div class="left">

                <p class="mainText">Please </p>
                <h2 class=""> Enter The Code</h2>
            <div class="wa d-flex">
                <img src="{{url('/public/storage')}}/img/wa.png" alt="">
                <form method="post" action="{{route('submitCode')}}" class="d-flex justify-content-center align-items-center">@csrf
                <input class="info bg-white d-flex justify-content-center align-items-center" placeholder="Code" name="code">
                <button type="submit" class="btn btn-outline-primary" id="submitButton">Verify</button>
            </form>
            </div>
            </div>
           <div class="right">
            <div class="row dial">
                <button class="btn col-4 bg-white">1</button>
                <button class="btn col-4 bg-white">2</button>
                <button class="btn col-4 bg-white">3</button>
                <button class="btn col-4 bg-white">4</button>
                <button class="btn col-4 bg-white">5</button>
                <button class="btn col-4 bg-white">6</button>
                <button class="btn col-4 bg-white">7</button>
                <button class="btn col-4 bg-white">8</button>
                <button class="btn col-4 bg-white">9</button>
                <button class="btn col-4 text-white" style="background-color: #FF0000;">Del X</button>
                <button class="btn col-4 bg-white">0</button>
                <button class="btn col-4 " style="background: #FAFF00;">Clear</button>
            </div>
           </div>
        </div>
        <div class="bottom d-flex justify-content-end align-items-center">
            <p>May 08,2023</p>
            <a href="{{route('share')}}" class="text-white d-flex justify-content-center align-items-center">
                <img src="{{url('/public/storage')}}/img/arrow.png" alt="">
                Go Back
            </a>
       
        </div>
        
       </div>
    </section>
@endsection