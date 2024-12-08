
@extends('arabic.main')
@section('content')
@section('page', 'code')
@section('title', 'Code')
<body>
    <section class="w-100 m-auto code">
        <div class="container  m-auto"> 
        @include('arabic.layouts.top')
        <div class="middle flex-row-reverse d-flex justify-content-between align-items-start">
            <div class="left">

                <p class="mainText text-end">لو سمحت </p>
                <h2 class="text-end"> ادخل الرمز</h2>
            <div class="wa d-flex flex-row-reverse">
                <img src="{{ asset('public/assets/arabic') }}/img/wa.png" alt="">
                <input name="code" id="input" type="number" class="info text-end bg-white d-flex justify-content-center align-items-center" placeholder="شفرة"></input>
            </div>
            @if(isset($error))
                        <div class="error badge bg-danger text-wrap fs-1">
                            {{$error}}
                        </div>
                   @else
                        <div class="mt-3 d-none error">
                            Invalid Code, Please Try Again
                        </div>
                    @endif
            </div>
           <div class="right">
            <div class="row dial">
                <button class="btn col-4 bg-white"  onclick="Numpad('1')">1</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('2')">2</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('3')">3</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('4')">4</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('5')">5</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('6')">6</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('7')">7</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('8')">8</button>
                <button class="btn col-4 bg-white"  onclick="Numpad('9')">9</button>
                <button class="btn col-4 text-white" style="background-color: #FF0000;" onclick="Del()">Del X</button>
                <button class="btn col-4 bg-white" onclick="Numpad('0')">0</button>
                <button class="btn col-4 " style="background: #FAFF00;" onclick="Clear()">Clear</button>
            </div>
           </div>
        </div>
        <div class="bottom d-flex flex-row-reverse justify-content-between align-items-center">
            <a href="{{ route('arabicShare') }}" class="backbtn d-flex justify-content-center align-items-center">></a>
            {{-- <a href="{{ route('arabicDocument') }}" class="text-white d-flex justify-content-center align-items-center">
                <img src="{{ asset('public/assets/arabic') }}/img/arrow.png" alt="">
                يكمل
            </a> --}}
            <form method="post" action="{{ route('arabicCode') }}" class="d-flex justify-content-center align-items-center">@csrf
               <input id = "form-input" type="hidden" name="code">
               <input id = "form-input" type="hidden" name="shop_id"  @if(isset($shop)) value="{{ $shop->id }}" @endif>
                         
            <button type="submit" class="text-white d-flex justify-content-center align-items-center">
                < يكمل</button>
            </form>
        </div>
       </div>
   
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <script>
        const input = document.getElementById("input")
        input.addEventListener('change', function(){
            document.getElementById("form-input").value = input.value;
        })
        var event = new Event('change');
        const Numpad = (value)=>{
            if(input.value.toString().length >= 4){
                input.dispatchEvent(event)
                return
            }else{
                input.value = input.value.toString() + value
                input.dispatchEvent(event)
                console.log()
            }
        }
        const Del = ()=>{
          let newValue =  input.value.toString().substring(0,input.value.toString().length-1);
          input.value = newValue
          input.dispatchEvent(event)
        }
        const Clear = ()=>{
            input.value = ""
            input.dispatchEvent(event)
        }
    </script>
</body>
@endsection
