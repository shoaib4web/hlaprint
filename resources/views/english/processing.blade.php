@extends('english.main')
@section('content')
@section('page', 'processing')
@section('title', 'Processing')
<body>
    <span id="trans-id" data-trans-id="{{ $trans_id }}"></span>
    <section class="w-100 m-auto processing">
        <div class="container  m-auto"> 
            @include('english.layouts.top')

            <div class="main d-flex justify-content-center align-items-center">
                <div class="left">
                    <p>الرجاء الانتظار </p>
                    <h2>@if(isset($message))
                        {{$message}}
                        @else
                        Processing Your Payment
                        @endif
                    </h2>
                </div>
                <img src="{{ asset('public/assets/english') }}/img/process.png" alt="" class="process">
            </div>
        <!-- <a href="{{ route('englishPay') }}" class="back text-decoration-none d-flex justify-content-center align-items-center "><</a> -->
       
       </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var transId = document.getElementById("trans-id").transId;

var pusher = new Pusher('e233ee947c37e701b089', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');
console.log("Event");
channel.bind('files-downloaded', function(data) {
   // Do what you want with the data
   // For now, let's just print it on the console
   console.log(data);
   if(data.transactionId)
   {
    // Extract transactionId from the data object
    var transactionId = data.transactionId;

    // Construct the redirect URL with the transactionId appended
    var redirectUrl = "http://hlaprint.com/Payment/" + transactionId;

    // Redirect to the constructed URL
    window.location.href = redirectUrl;
   }
   

   
});
    </script>

</body>

</html>