<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('public/assets/english') }}/css/document.css">
    <style>
        .document .amount h2 {
            display: flex !important;
        }

        @media screen and (max-width: 990px) {

            .document .amount {
                justify-content: space-between !important;
            }
        }

        @media screen and (max-width : 480px) {
            .document .amount:nth-child(2) {
                flex-direction: column !important;
                margin-left: 0;
            }

            .document .amount:nth-child(2) h2 {
                margin-bottom: 1rem;
            }
        }

        @media screen and (max-width : 384px) {
            .document .main {
                margin-top: 1rem !important;
            }

            .document .amount:nth-child(2) h2 {
                margin-top: 1rem;
            }

            .document .amount:nth-child(2) {
                margin-top: 0 !important;
            }
        }
    </style>
</head>

<body>
    <section class="w-100 m-auto document">
        <div class="container  m-auto">

            <div class="header d-flex justify-content-center align-items-center">
                <p class="">Welcome to <br>
                    Our Printing Shop</p>
                <img src="{{ asset('public/assets/english') }}/img/logo.png" alt="" class="logo ms-5">
                <div class="contact contactPc d-flex justify-content-end align-items-right text-right ">
                    <p class="text-end"> Customer Support <br>
                        123 456 789</p>
                    <img src="{{ asset('public/assets/english') }}/img/contact.png" alt="">
                </div>
                <div class="Contact  dropdown">
                    <img src="{{ asset('public/assets/english') }}/img/contact.png" alt=""
                        class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu">
                        <p class="text-end"> Customer Support <br>
                            123 456 789</p>
                    </ul>
                </div>
            </div>


            <div class="main d-flex justify-content-between align-items-start">
                <div>
                    <div class="settings position-relative " style="display: block !important;">
                        <div
                            class="TopHeading d-flex top-0 left-0 justify-content-end align-items-center position-absolute">
                            Subtotal
                        </div>
                        <div class="d-flex options">
                            <p><img src="{{ asset('public/assets/english') }}/img/color.png" style="width: 3.8rem;"
                                    alt=""> Color:</p>

                            <div class="d-flex fle-column">
                                <div class="d-flex input">
                                    <input id="blackwhite" checked="true" type="radio" onchange="(uncheck('color'))">
                                    <p>Grayscale</p>
                                </div>
                                <div class="d-flex input">
                                    <input type="radio" id="color" onchange="(uncheck('blackwhite'))">
                                    <p style="margin-right: 0;">Color</p>
                                </div>
                            </div>
                            <p class="amount">10 SAR</p>

                        </div>

                        <div class="d-flex options">
                            <p><img src="{{ asset('public/assets/english') }}/img/sides.png" style="width: 3.2rem;"
                                    alt=""> Sides:</p>
                            <div class="d-flex fle-column">

                                <div class="d-flex input">
                                    <input type="radio" checked="true" id="oneside" onchange="(uncheck('twoside'))">
                                    <p>One Sided</p>
                                </div>
                                <div class="d-flex input">
                                    <input type="radio" id="twoside" onchange="(uncheck('oneside'))">
                                    <p style="margin-right: 0;">Two Sided</p>
                                </div>
                            </div>
                            <p class="amount">10 SAR</p>
                        </div>
                        <div class="d-flex options">
                            <p><img src="{{ asset('public/assets/english') }}/img/range.png" style="width: 3.3rem;"
                                    alt=""> Range:</p>
                            <div class="d-flex fle-column">
                                <div class="d-flex input">
                                    <input type="radio" checked="true" id="all"
                                        onchange="(uncheck('custom', 'all'))">
                                    <p>All Pages</p>
                                </div>
                                <div class="d-flex input">
                                    <input type="radio" id="custom" onchange="(uncheck('all', 'custom'))">
                                    <p style="margin-right: 0;">Custom</p>
                                </div>
                            </div>
                            <p class="amount">10 SAR</p>

                        </div>
                        <div class="Pages " id="CopiesNumber">
                            <div class="d-flex fle-column">

                                <div class="d-flex justify-content-center align-items-center">

                                    <p style="margin-right: 2rem; margin-left: 0; font-weight: 400;">From</p>
                                    <div class="position-relative d-flex align-items-center">

                                        <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 25px; font-weight: 500; "
                                            onclick="IncreaseCount('from', false)">-</p>
                                        <input disabled type="text" class="number text-center" id="from"
                                            value="1">

                                        <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 25px; font-weight: 500; transform: rotate(180deg);"
                                            onclick="IncreaseCount('from', true)">+</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <p style="margin-right: 2rem; margin-left: 5.5rem; font-weight: 400;">To</p>
                                    <div class="position-relative d-flex align-items-center">
                                        <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 25px; font-weight: 500; transform: rotate(180deg);"
                                            onclick="IncreaseCount('to', false)">-</p>
                                        <input disabled type="text" class="number text-center" id="to"
                                            value="2">

                                        <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 25px; font-weight: 500; transform: rotate(180deg);"
                                            onclick="IncreaseCount('to', true)">+</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="d-flex Pages">
                            <div class="d-flex justify-content-center align-items-center">
                                <p style="margin-right: 8.1rem; margin-left: 0"><img style="width: 2.8rem;"
                                        src="{{ asset('public/assets/english') }}/img/copies.png" alt="">
                                    Copies:</p>
                                <div class="position-relative d-flex align-items-center">
                                    <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 25px; font-weight: 500; transform: rotate(180deg);"
                                        onclick="IncreaseCount('copies', false)">-</p>
                                    <input disabled type="text" class="number text-center" id="copies"
                                        value="1">

                                    <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 25px; font-weight: 500; transform: rotate(180deg);"
                                        onclick="IncreaseCount('copies', true)">+</p>

                                </div>

                            </div>
                            <p class="amount">10 SAR</p>

                        </div>
                    </div>
                    <div class="amount d-flex justify-content-end align-items-center">
                        <h2>Total:<span>10 SAR</span></h2>
                        <div class="d-flex">
                            <a href="./document.html" class="d-flex justify-content-center align-items-center back">
                                < </a>
                                    <a href="./pay.html"
                                        class="d-flex justify-content-center align-items-center pay">Confirm Pay</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>


    <script>
        let custom = false
        document.getElementById("CopiesNumber").style.opacity = "0.5"
        const uncheck = (id, e) => {
            document.getElementById(id).checked = false
            if (e == "custom") {
                document.getElementById("CopiesNumber").style.opacity = "1"
                custom = true
            } else if (e == "all") {
                document.getElementById("CopiesNumber").style.opacity = "0.5"
                custom = false
            }
        }


        let from = document.getElementById("from").value
        let to = document.getElementById("from").value
        let copies = document.getElementById("from").value


        const IncreaseCount = (id, value) => {


            if (id == "from") {
                if (custom) {
                    if (value) {
                        document.getElementById(id).value++

                    } else {
                        if (document.getElementById(id).value <= 0) {
                            document.getElementById(id).value = 0
                        } else {
                            document.getElementById(id).value--
                        }

                    }
                }
            } else if (id == "to") {
                if (custom) {

                    if (value) {
                        document.getElementById(id).value++

                    } else {
                        if (document.getElementById(id).value <= 0) {
                            document.getElementById(id).value = 0
                        } else {
                            document.getElementById(id).value--

                        }
                    }
                }
            }
            if (id == "copies") {
                if (value) {
                    document.getElementById(id).value++

                } else {
                    if (document.getElementById(id).value <= 0) {
                        document.getElementById(id).value = 0
                    } else {
                        document.getElementById(id).value--
                    }

                }
            }


        }



        window.addEventListener("resize", () => {
            if (window.innerWidth >= 990) {
                window.location.href = "./document.html"
            }
        })
    </script>
</body>

</html>
