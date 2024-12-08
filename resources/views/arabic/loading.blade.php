@extends('arabic.main')
@section('content')
@section('page', 'Loading')
@section('title', 'Loading')

<style>
    .spinner-container {
  display: flex;
  justify-content: center;
  flex-direction:column;
  align-items: center;
  height: 100vh; /* Full viewport height */
}

.loading-spinner {
  border: 5px solid #f3f3f3; /* Light grey background */
  border-top: 5px solid #3498db; /* Blue color */
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>
<body>
    <div class="spinner-container">
        <div class="loading-spinner"></div>
        <div class="info-text"></div>
    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js">
    

</script>
<script>
    function checkPaymentStatus(orderId) {
  $.ajax({
    url: '/check-payment-status/' + orderId,
    type: 'GET',
    success: function(response) {
      if(response.status === 'done') {
        // Payment is completed
        // Update the UI accordingly
        window.location.href = '/arabic/success'; // Redirect to success page
      } else if(response.status === 'sent_to_client' || response.status === 'new') {
        // Payment is still pending
        // Continue to show processing view and poll again after a delay
        var text = document.querySelector('.info-text');
        text.innerHTML = 'Files Being Processed';
        setTimeout(function() {
          checkPaymentStatus(orderId);
        }, 5000); // Poll every 5 seconds, adjust time as needed
      } else if( response.status === 'error') {
        // Payment failed or status unknown
        // Handle accordingly, maybe show an error message
        window.location.href = '/errorPrint/{{$trans_id}}';
      }
    },
    error: function(xhr, status, error) {
      // Handle error
      console.error('Error checking payment status:', error);
    }
  });
}

// Call this function with the correct orderId when the processing view is active
checkPaymentStatus({{$trans_id}});
</script>
</html>