@extends('english.main')
@section('content')
@section('page', 'pay')
@section('title', 'Pay')
<body>
    <section class="w-100 m-auto pay">
        <div class="container  m-auto">
    @include('english.layouts.top')
    <div class="main d-flex justify-content-center align-items-start">
        <div class="left d-flex justify-content-start align-items-start flex-column">

        <p class="heading">Please</p>
        <h2>Select Your Payment <br>
            Method</h2>
            <div class="d-flex">

                <a href="{{ route('englishProcessing') }}" class="payMethod d-flex justify-content-center align-items-center">
                <img src="{{ asset('public/assets/english') }}/img/applepay.png" alt="">
                </a>
                <a href="{{ route('englishProcessing') }}" class="payMethod d-flex justify-content-center align-items-center">
                <img src="{{ asset('public/assets/english') }}/img/bank.png" alt="">
                </a>
                <a href="{{ route('englishProcessing') }}" class="payMethod d-flex justify-content-center align-items-center">
                <img src="{{ asset('public/assets/english') }}/img/gpay.png" alt="">
                </a>
            </div>
            <p>To pay at the POS Machine</p>
        </div>
        <div class="right">
            <img src="{{ asset('public/assets/english') }}/img/pos.png" class="pos" alt="">
            <h2>Total: <span>10 SAR</span></h2>
        </div>
    </div>
    <a  href="{{ route('englishDocument') }}" class="d-flex justify-content-center align-items-center back ms-auto"><</a>

       </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


    <script>
        const uncheck = (id)=>{
                document.getElementById(id).checked = false
        }
    </script>
</body>

</html>
