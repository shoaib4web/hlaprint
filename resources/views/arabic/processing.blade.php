@extends('arabic.main')
@section('content')
@section('page', 'processing')
@section('title', 'Processing')
<body>
    <section class="w-100 m-auto processing">
        <div class="container  m-auto"> 
            @include('arabic.layouts.top')
            <div class="main d-flex flex-row-reverse justify-content-between align-items-center">
                <div class="left text-end">
                    <p>انتظر من فضلك</p>
                    <h2>معالجة الدفع</h2>
                </div>
                <img src="{{ asset('public/assets/arabic') }}/img/process.png" alt="" class="process" style="transform: scaleX(-100%);"> 
            </div>
        <a href="./pay.html" class="back text-decoration-none d-flex justify-content-center align-items-center">></a>
   
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