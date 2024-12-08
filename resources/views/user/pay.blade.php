@extends('user.home')
@section('content')
<title>Document</title>
<link rel="stylesheet" href="{{url('/public/storage')}}/css/pay.css">
</head>

<body>
    <section class="w-100 m-auto pay">
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
    <div class="left d-flex justify-content-start align-items-start flex-column">

        <div class="bank">
            <div class="top">
                <p class="mainText">Use</p>
                <h2 class=""> Bank Account</h2>
                <button><img src="{{url('/public/storage')}}/img/bank.png" alt=""></button>
                </div>
        </div>
        <div class="bank applepay">
            <div class="top">
                <p class="mainText">Or</p>
                <h2 class=""> Apple Pay</h2>
                <button><img src="{{url('/public/storage')}}/img/applepay.png" alt=""></button>
                </div>
        </div>
        <p>To pay at the POS Machine</p>
    </div>
    <div class="right">
        <img src="{{url('/public/storage')}}/img/pos.png" class="pos" alt="">
        <h2>Total: <span>10 SAR</span></h2>
    </div>
</div>
<a href="{{url('/public/storage')}}/document.html" class="d-flex justify-content-center align-items-center back ms-auto"><</a>
        <p class="date m-auto text-center">26-Apr-2023</p>
       </div>

    </section>


@endsection