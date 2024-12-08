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
                    <p>الرجاء الانتظار</p>
                    <h2 id="status-message">Processing Your Files</h2>
                </div>
                <!-- Replace static image with loader -->
                <div class="loader" id="loader">
                    <!-- Add your loader graphic here, e.g., a spinner or progress bar -->
                    <img src="{{ asset('public/assets/english/img/loading.gif') }}" alt="Loading..." class="process">
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher
        Pusher.logToConsole = true;
        var transId = document.getElementById("trans-id").dataset.transId;
        var pusher = new Pusher('e233ee947c37e701b089', { cluster: 'ap2' });
        var channel = pusher.subscribe('my-channel');

        // Function to update status message
        function updateStatusMessage(message) {
            document.getElementById("status-message").innerText = message;
        }

        // Step 1: "Processing Your Files" is already shown on page load
        // Step 2: Change to "Sending Files" after 2 seconds
        setTimeout(function() {
            updateStatusMessage("Sending Files");
        }, 2000);

        // Listen for the 'files-downloaded' event
        channel.bind('files-downloaded', function(data) {
            if (data.transactionId) {
                // Step 3: Change to "Redirecting to Payment"
                updateStatusMessage("Redirecting to Payment");

                // Step 4: Redirect after 0.3 seconds
                setTimeout(function() {
                    var redirectUrl = "http://hlaprint.com/Payment/" + data.transactionId;
                    window.location.href = redirectUrl;
                }, 300);
            }
        });
    </script>
</body>
</html>
