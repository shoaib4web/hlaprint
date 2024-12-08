@extends('user.home')
@section('content')
<title>Document</title>
<link rel="stylesheet" href="{{url('/public/storage')}}/css/processing.css">
</head>

<body>
    <section class="w-100 m-auto processing">
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

            <div class="main d-flex justify-content-center align-items-center">
                <div class="left">
                    <p>Please Wait</p>
                    <h2>Processing payment</h2>
                </div>
                <img src="{{url('/public/storage')}}/img/process.png" alt="" class="process">
            </div>
        <button class="back "><</button>
        <p class="date m-auto text-center">May 08, 2023</p>
       </div>

    </section>

@endsection