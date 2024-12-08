
@extends('english.main')
@section('content')
@section('page', 'code')
@section('title', 'Code')
<body>
    <section class="w-100 m-auto code">
        <div class="container  m-auto">
            @include('english.layouts.top')
            <div class="middle d-flex justify-content-between align-items-start">
                <div class="left">

                    <p class="mainText">Please !</p>
                    <h2 class=""> Enter The Code</h2>
                    @if(isset($shopTitle))
                    <div>{{$shopTitle}}</div>
                    @endif
                    <div class="wa d-flex">
                        <img src="{{ asset('public/assets/english') }}/img/wa.png" alt="">
                        <input type = "text" name = "employee_id"  pattern="[0-9]{4}" placeholder="e.g. 1234" required class="info bg-white d-flex justify-content-center align-items-center" id="input" @if(isset($code)) value="{{ $code }}" @endif>
                    </div>
                   @if(isset($error))
                        <div class="mt-3 error">
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
                        <button class="btn col-4 bg-white" onclick="Numpad('1')">1</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('2')">2</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('3')">3</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('4')">4</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('5')">5</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('6')">6</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('7')">7</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('8')">8</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('9')">9</button>
                        <button class="btn col-4 text-white" style="background-color: #FF0000;" onclick="Del()">Del
                            X</button>
                        <button class="btn col-4 bg-white" onclick="Numpad('0')">0</button>
                        <button class="btn col-4 " style="background: #FAFF00;" onclick="Clear()">Clear</button>
                    </div>
                </div>
            </div>
            <div class="bottom d-flex justify-content-between align-items-center">
                <a href="{{ route('englishShare') }}" class="backbtn d-flex justify-content-center align-items-center">
                    <
                    </a>
                        {{-- <a href="{{ route('englishDocument') }}" class="text-white d-flex justify-content-center align-items-center">
                            Continue
                            <img src="{{ asset('public/assets/english') }}/img/arrow.png" alt="" style="transform: rotate(180deg);">
                        </a> --}}
                        <form method="post" action="{{ route('englishSubmitCode') }}" class="d-flex justify-content-center align-items-center">@csrf
                            <input id = "form-input" type="hidden" name="code"  @if(isset($code)) value="{{ $code }}" @endif>
                            <input id = "form-input" type="hidden" name="shop_id"  @if(isset($shop)) value="{{ $shop->id }}" @endif>

                         <button type="submit" class="text-white d-flex justify-content-center align-items-center">
                             Continue

                            </button>
                         </form>
            </div>
        </div>

    </section>
    <script>
        const input = document.getElementById("input")
        input.addEventListener('change', function(){
            document.getElementById("form-input").value = input.value;
            console.log(document.getElementById("form-input").value)
        })
        console.log(input)
        var event = new Event('change');
        const Numpad = (value) => {
            if (input.value.toString().length >= 4) {
                input.dispatchEvent(event)
                return
            } else {
                input.value = input.value.toString() + value
                input.dispatchEvent(event)
                console.log()
            }
        }
        const Del = () => {
            let newValue = input.value.toString().substring(0, input.value.toString().length - 1);
            input.value = newValue
            input.dispatchEvent(event)
        }
        const Clear = () => {
            input.value = ""
            input.dispatchEvent(event)
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>
@endsection
