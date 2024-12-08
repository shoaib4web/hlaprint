@extends('user.home')
@section('content')
<title>PrintAway | Share</title>
<link rel="stylesheet" href="{{url('/public/storage')}}/css/share.css">
</head>
<body>
    @include('user.navigation-header')
        <div class="middle d-flex justify-content-between align-items-start">
            <div class="left">

                <p class="mainText">Please </p>
                <h2 class="font-weight-semibold"> Share Your File</h2>
            <div class="wa d-flex">
                <img src="{{url('/public/storage')}}/img/wa.png" alt="">
                <div class="info bg-white d-flex justify-content-center align-items-center">+966 59 101 3248</div>
            </div>
            </div>
            <div class="middleLine d-flex flex-column justify-content-center align-items-center">
                <div class="line bg-white"></div>
                <p>Or</p>
                <div class="line bg-white"></div>
            </div>
            <div class="right d-flex flex-column justify-content-center align-items-center">
                <img src="{{url('/public/storage')}}/img/qr.png" alt="">
                <div class="info bg-white d-flex justify-content-center align-items-center">Scan QR Code</div>
            </div>
        </div>
        <div class="bottom d-flex justify-content-end align-items-center">
            <p>May 08,2023</p>
             <a href="{{route('code')}}">
                <button class="text-white">
                    Let's Get Started!
                    <img src="{{url('/public/storage')}}/img/arrow2.png" alt="">
                </button> 
             </a>
        </div>
    </div>
    </section>
@endsection