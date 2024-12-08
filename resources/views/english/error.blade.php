@extends('english.main')
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
                <a href="{{route('arabicError')}}" class="btn">العربية</a>
            </div>
            <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">
        </div>
        <div class="right">
            <div class="container">
                <div class="box">

                    <img src="{{ asset('public/assets/img/error.png') }}" alt="">
                    <h2>Transaction Failure</h2>
                    <p>Your payment has been declined</p>
                </div>
                <button>Ok</button>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>
</html>