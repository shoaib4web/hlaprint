@extends('english.main')
@section('content')
@section('page', 'success')
@section('title', 'Success')
<body>
    <section class="w-100 m-auto success">
        <div class="container  m-auto"> 
            @include('english.layouts.top')
            <div class="main d-flex justify-content-center align-items-center">
                <div>
                    <div class="left">
                        <p>Payment</p>
                        <h2>Successfull</h2>
                    </div>
                    <p class="p">Please Collect From Store Employ and Collect Your Receipt </p>
                </div>
                <img src="{{ asset('public/assets/english') }}/img/success.png" alt="" class="process">
            </div>
        <a href="{{route('index')}}" class="back text-decoration-none d-flex justify-content-center align-items-center "><</a>
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