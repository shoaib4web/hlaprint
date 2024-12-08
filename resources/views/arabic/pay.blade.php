@extends('arabic.main')
@section('content')
@section('page', 'pay')
@section('title', 'Pay')
<body>
    <section class="w-100 m-auto pay">
        <div class="container  m-auto"> 
            @include('arabic.layouts.top')
<div class="main middle flex-row-reverse d-flex justify-content-between align-items-start">
    <div class="left">

        <p class="mainText text-end">لو سمحت </p>
        <h2 class="font-weight-semibold text-end">حدد الدفع الخاص بك
           <br> طريقة </h2>
    <div class="wa d-flex flex-row-reverse">
        <a href="{{ route('arabicProcessing') }}" class="payMethod info text-decoration-none bg-white d-flex justify-content-center align-items-center" ><img src="{{ asset('public/assets/arabic') }}/img/applepay.png" alt=""></a>
        <a href="{{ route('arabicProcessing') }}" class="payMethod info text-decoration-none bg-white d-flex justify-content-center align-items-center"><img src="{{ asset('public/assets/arabic') }}/img/bank.png" alt=""></a>
        <a href="{{ route('arabicProcessing') }}" class="payMethod info text-decoration-none bg-white d-flex justify-content-center align-items-center"><img src="{{ asset('public/assets/arabic') }}/img/gpay.png" alt=""></a>
        
    </div>
    <p class="text-end">للدفع في جهاز نقاط البيع</p>
    </div>
    <div class="right">
        <img src="{{ asset('public/assets/arabic') }}/img/pos.png" style="    transform: scaleX(-100%);" class="pos" alt="">
        <h2>المجموع: <span>10 ريال</span></h2>
    </div>
</div>
<a href="{{ route('arabicDocument') }}" class="d-flex justify-content-center align-items-center back ms-auto">></a>

       </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


    <script>
        const uncheck = (id)=>{
                document.getElementById(id).checked = false
        }
    </script>
</body>
@endsection