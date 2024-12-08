@extends('arabic.main')
@section('content')
@section('page', 'success')
@section('title', 'Error')
<body>
    <section class="w-100 m-auto success">
        @include('arabic.layouts.top')
            <div class="main d-flex justify-content-center align-items-center flex-row-reverse text-end">
                <div>

                    <div class="left">
                        <p>قسط</p>
                        <h2>فشل</h2>
                    </div>
                    <p class="p">هل هناك خطب ما</p>
                </div>
                <img src="{{ asset('public/assets/arabic') }}/img/error.png" alt="" class="process">
            </div>
        <a href="{{route('index')}}" class="back text-decoration-none d-flex justify-content-center align-items-center">></a>
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