@extends('arabic.main')
@section('content')
@section('page', 'success')
@section('title', 'Success')
<body>
    <section class="document">
        <div class="left">
            <img src="{{ asset('public/assets/img/lines1.png') }}" class="line1" alt="">
            <img src="{{ asset('public/assets/img/lines2.png') }}" class="line2" alt="">
            <div class="header">
                <img src="{{ asset('public/assets/img/logo.png') }}" alt="">
                <a href="{{route('englishError')}}" class="btn">English</a>
            </div>
            <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">
        </div>
        <div class="right">
            <div class="container">
                <div class="box">

                    <img src="{{ asset('public/assets/img/error.png') }}" alt="">
                    <h2>فشل الصفقة</h2>
                    @if(isset($message))
                    <p>{{$message}}</p>
                    @else
                    <p>آسف لقد واجهنا خطأص بك</p>
                    @endif
                </div>
                <button>نعم </button>
            </div>
        </div>
    </section>

</body>
</html>