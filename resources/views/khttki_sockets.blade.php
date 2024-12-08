<html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        
</head>
<body>
    <script>
        // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

var pusher = new Pusher('e233ee947c37e701b089', {
  cluster: 'ap2'
});

var channel = pusher.subscribe('my-channel');
console.log("Event");
channel.bind('files-downloaded', function(data) {
   // Do what you want with the data
   // For now, let's just print it on the console
   console.log(data);
   // Extract transactionId from the data object
   var transactionId = data.transactionId;

   // Construct the redirect URL with the transactionId appended
   var redirectUrl = "http://hlaprint.com/Payment/" + transactionId;

   // Redirect to the constructed URL
   window.location.href = redirectUrl;

   
});
    </script>
</body>

</html>