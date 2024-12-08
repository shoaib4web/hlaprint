@extends('english.main')
@section('content')
@section('page', 'share')
@section('title', 'Share')
<body>
    <section class="w-100 m-auto">
        <div class="container m-auto">
            @include('english.layouts.top')
        <div class="middle d-flex justify-content-between align-items-start">
            <div class="left">

                <p class="mainText">Please </p>
                <h2 class="font-weight-semibold"> Share Your File</h2>
            <div class="wa d-flex">
                <img src="{{ asset('public/assets/english') }}/img/wa.png" alt="">
                <div class="info bg-white d-flex justify-content-center align-items-center">+966 59 101 3248</div>
            </div>
            </div>
            <div class="middleLine d-flex flex-column justify-content-center align-items-center">
                <div class="line bg-white"></div>
                <p>|</p>
                <div class="line bg-white"></div>
            </div>
            <div class="right d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('public/assets/english') }}/img/qr.png" alt="">
                <div class="info bg-white d-flex justify-content-center align-items-center">Scan QR Code</div>
            </div>
        </div>
        <div class="bottom d-flex justify-content-end align-items-center">
            <a href="{{route('index')}}" class="backbtn d-flex justify-content-center align-items-center"><</a>

            <a href="{{ route('englishCode') }}" class="text-white d-flex justify-content-center align-items-center">
                Lets Get Started
                <img src="{{ asset('public/assets/english') }}/img/arrow.png" alt="" style="transform: rotate(180deg);">
            </a>
        </div>
    </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
@endsection
