@extends('arabic.main_document')
@section('content')
@section('page', 'document')
@section('title', 'Hla Print')



<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container-box {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 660px;
        }
        .otp-title {
            font-size: 2.8em;
            margin-bottom: 0px;
        }
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .otp-description {
            margin-bottom: 20px;
            font-size:1.4em;
        }
        .otp-input {
            width: 60px;
            height: 60px;
            font-size: 2em;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .key {
            background-color: #007FFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.8em;
            padding: 15px;
            cursor: pointer;
        }
        .key:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }
        .submit-btn {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 2em;
            cursor: pointer;
        }

        .document .left {
            height: max-content;
        }

        .document .right {
            justify-content: center;
            align-items: center;
        }

        .key.delete {
            background-color: red;
        }

        .key.delete:active {
            background-color: #f73030;
        }

        .key:active {
            background-color: #1789fb;
            box-shadow: none;
            top: 3px;
        }
    </style>
    <style>
        /* Connection Status */
        .connection-status {
            position: absolute;
            bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 8px 15px;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #2ecc71; /* Default green color */
            transition: background-color 0.3s ease;
        }

        .status-indicator.disconnected {
            background-color: #e74c3c; /* Red color for disconnected state */
        }

        .status-text {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        /* Adjust the right section to accommodate the status indicator */
        .document .right {
            position: relative;
        }
    </style>

    <style>
        .alert-dismissible {
    position: relative;
    padding-right: 35px;
}

.close-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: inherit;
    padding: 0 5px;
}

.close-btn:hover {
    opacity: 0.7;
}

.alert {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
</style>
    <section class="document">
        <div class="left">
            <img src="{{ asset('public/assets/img/lines1.png') }}" class="line1" alt="">
            <img src="{{ asset('public/assets/img/lines2.png ') }}" class="line2" alt="">
            <div class="header">
                <!-- <img src="{{ asset('public/assets/img/logo.png') }}" alt=""> -->

                <a href="{{route('getOptions',123)}}" class="btn">English</a>

            </div>
            <img src="{{ asset('public/assets/img/printing.png') }}" alt="" class="printing">
        </div>
        <div class="right">

            <div class="container-box" data-print-url="{{ route('printFromCode', ['code' => ':code', 'id' => 13]) }}">
                @isset($message)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{$message}}
                        <button type="button" class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endisset
                <div class="otp-title">Enter Your Invoice Number</div>
                <div class="otp-description">Enter Invoice Number to Get your Prints</div>

                <!-- OTP Inputs -->
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" id="otp-1" disabled>
                    <input type="text" class="otp-input" maxlength="1" id="otp-2" disabled>
                    <input type="text" class="otp-input" maxlength="1" id="otp-3" disabled>
                    <input type="text" class="otp-input" maxlength="1" id="otp-4" disabled>
                </div>

                <!-- Onscreen Keypad -->
                <div class="keypad">
                    <button class="key" onclick="enterDigit(1)">1</button>
                    <button class="key" onclick="enterDigit(2)">2</button>
                    <button class="key" onclick="enterDigit(3)">3</button>
                    <button class="key" onclick="enterDigit(4)">4</button>
                    <button class="key" onclick="enterDigit(5)">5</button>
                    <button class="key" onclick="enterDigit(6)">6</button>
                    <button class="key" onclick="enterDigit(7)">7</button>
                    <button class="key" onclick="enterDigit(8)">8</button>
                    <button class="key" onclick="enterDigit(9)">9</button>
                    <button class="key delete" onclick="deleteDigit()">←</button>
                    <button class="key" onclick="enterDigit(0)">0</button>
                    <button class="key" disabled>*</button>
                </div>

                <!-- Submit Button -->
                <button class="submit-btn" onclick="submitOtp()">Next</button>


            </div>
            <div class="connection-status">
                <div class="status-indicator"></div>
                <span class="status-text">Connection Status: Good</span>
            </div>
        </div>
    </section>
    <script>
        let currentInputIndex = 0;
        const inputs = document.querySelectorAll('.otp-input');
        inputs[currentInputIndex].disabled = false;
        inputs[currentInputIndex].focus();

        function enterDigit(digit) {
            if (currentInputIndex < inputs.length) {
                inputs[currentInputIndex].value = digit;
                inputs[currentInputIndex].disabled = true;
                currentInputIndex++;
                if (currentInputIndex < inputs.length) {
                    inputs[currentInputIndex].disabled = false;
                    inputs[currentInputIndex].focus();
                }
            }
        }

        function deleteDigit() {
            if (currentInputIndex > 0) {
                currentInputIndex--;
                inputs[currentInputIndex].value = '';
                inputs[currentInputIndex].disabled = false;
                inputs[currentInputIndex].focus();
            }
        }

        function submitOtp() {
            const otp = Array.from(inputs).map(input => input.value).join('');
            if (otp.length === 4) {
                const container = document.querySelector('.container-box');
                let url = container.dataset.printUrl;
                url = url.replace(':code', otp);
                window.location.href = url;
            } else {
                alert('Please enter the full OTP.');
            }
        }
    </script>

<script>
    // Connection Status
    function updateConnectionStatus(isConnected) {
        const indicator = document.querySelector('.status-indicator');
        const statusText = document.querySelector('.status-text');

        if (isConnected) {
            indicator.classList.remove('disconnected');
            statusText.textContent = 'Connection Status: Good';
        } else {
            indicator.classList.add('disconnected');
            statusText.textContent = 'Connection Status: Disconnected';
        }
    }

    // Example usage:
    // updateConnectionStatus(false); // To show disconnected state
    updateConnectionStatus(true);  // To show connected state
</script>

<script>
    // Auto-hide alert messages after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.querySelector('.alert-dismissible');
    if (alert) {
        // Add initial opacity for smooth transition
        alert.style.opacity = '1';
        alert.style.transition = 'opacity 0.5s ease';

        setTimeout(function() {
            // Fade out
            alert.style.opacity = '0';

            // Remove element after fade completes
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 3000);
    }
});
</script>


</body>
</html>
@endsection
