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
                <a href="{{route('englishSuccess')}}" class="btn">English</a>
            </div>
            <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">
        </div>
        <div class="right">
            <div class="container">
            <span id="trans-id" data-trans-id="{{ $trans_id }}"></span>
                <div class="box">

                    <img src="{{ asset('public/assets/img/successfull.png') }}" alt="">
                    <h2>Almost Done.. Please Wait</h2>
@if(isset($message))
<p>{{$message}}</p>
@else 

@endif
                </div>
                <button>نعم </button>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var transId = "{{ $trans_id }}";//document.getElementById("trans-id").transId;

var pusher = new Pusher('e233ee947c37e701b089', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');
console.log("Event");
channel.bind('prints-done', function(data) {
   // Do what you want with the data
   // For now, let's just print it on the console
   console.log(data.transactionId);
   console.log(transId);
   
   if(data.transactionId && data.transactionId == transId)
   {
    // Extract transactionId from the data object
    var transactionId = data.transactionId;

    // Construct the redirect URL with the transactionId appended
    var redirectUrl = "https://hlaprint.com/successful/";

    // Redirect to the constructed URL
    window.location.href = redirectUrl;
   }
   

   
});
    </script>
</body>
</html>