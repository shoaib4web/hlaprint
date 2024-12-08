<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.min.js"></script>

    <title>Document</title>
    <link rel="stylesheet" href="./css/document.css">
</head>

<body>
    <section class="w-100 m-auto document">
        <div class="container  m-auto"> 

            <div class="header d-flex flex-row-reverse justify-content-center align-items-center">
                <p class="text-end">مرحبا بك في <br> محل الطباعة لدينا</p>
                <div class="imgdiv">
                    <img src="./img/logo.png" alt="" class="logo ms-5">
                </div>
                <div class="contact d-flex justify-content-end align-items-right text-right ">
                    <p class="text-end"> دعم العملاء
                       <br> 123 456 789</p>
                    <img src="./img/contact.png" alt="">
                </div>
            </div>

 
        <div class="main flex-row-reverse d-flex justify-content-center align-items-start">
            <div class="documents">
                <div class="top">
                    <p class="mainText text-end">راجع ملفك لو سمحت</p>
                    <!-- <p class="doc text-end flex-row-reverse d-flex justify-content-start align-items-start"><img src="./img/pdficon.png"
                            alt="">اسم الملف</p> -->
                </div>
                <div style="width: 37.9rem; height: 60.5rem; 
                    background: #80808059;  margin: auto; margin-right: 0; margin-bottom: 2.9rem;"
                        class="d-flex justify-content-center align-items-center">

                        <div class="documentFiles overflow-hidden d-flex justify-content-center align-items-center">
                        </div>
                    </div>
                <div class="pagination d-flex justify-content-start align-items-center">
                    <img src="./img/Larrow.png" alt="" onclick="Pagination(false)">
                    <button class="currentPage">1</button>
                    <p>Of</p>
                    <p class="number">3</p>
                    <img src="./img/Larrow.png" alt="" style="transform: rotate(180deg);"
                        onclick="Pagination(true)">
                </div>
            </div>
        <div>
            <div class="settings">
                <div class="d-flex options flex-row-reverse">
                    <p> :لون <img src="./img/color.png" style="width: 3.8rem;" alt=""></p>
                    <div class="d-flex input flex-row-reverse">
                        <input id="blackwhite" checked="true" type="radio" onchange="(uncheck('color'))">
                        <p>تدرج الرمادي</p>
                    </div>
                    <div class="d-flex input flex-row-reverse">
                        <input type="radio" id="color"  onchange="(uncheck('blackwhite'))">
                        <p style="margin-left: 0;"> من جانب واحد </p>
                    </div>
                    <p class="amount">10 SAR</p>

                </div>
                <div class="d-flex options flex-row-reverse">
                    <p> :الجوانب  <img src="./img/sides.png" style="width: 3.2rem;" alt=""></p>
                    <div class="d-flex input flex-row-reverse">
                        <input type="radio" checked="true" id="oneside"  onchange="(uncheck('twoside'))">
                        <p>من جانب واحد</p>
                    </div>
                    <div class="d-flex input flex-row-reverse">
                        <input type="radio" id="twoside"  onchange="(uncheck('oneside'))">
                        <p style="margin-left: 0;">وجهان</p>
                    </div>
                    <p class="amount">10 SAR</p>

                </div>
                <div class="d-flex options flex-row-reverse">
                    <p> :يتراوح <img src="./img/copies.png" alt="" style="width: 3.3rem;"></p>
                    <div class="d-flex input flex-row-reverse">
                        <input type="radio" checked="true" id="all"  onchange="(uncheck('custom', 'all'))">
                        <p>كل الصفحات</p>
                    </div>
                    <div class="d-flex input flex-row-reverse">
                        <input type="radio" id="custom"  onchange="(uncheck('all', 'custom'))">
                        <p style="margin-left: 0;">مخصص</p>
                    </div>
                    <p class="amount">10 SAR</p>

                </div>
                <div class="Pages flex justify-content-start flex-row-reverse" id="CopiesNumber">

                    <div class="d-flex justify-content-center align-items-center">
                        <div class="position-relative d-flex align-items-center">

                            <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 35px; font-weight: 500; "
                                onclick="IncreaseCount('from', false)">-</p>
                            <input disabled type="text" class="number text-center" id="from" value="1">

                            <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 35px; font-weight: 500; transform: rotate(180deg);"
                                onclick="IncreaseCount('from', true)">+</p>
                        </div>
                        <p style="margin-left: 2rem; margin-right: 0; font-weight: 400;" >من</p>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="position-relative d-flex align-items-center">

                            <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 35px; font-weight: 500; "
                                onclick="IncreaseCount('to', false)">-</p>
                            <input disabled type="text" class="number text-center" id="to" value="2">

                            <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 35px; font-weight: 500; transform: rotate(180deg);"
                                onclick="IncreaseCount('to', true)">+</p>
                        </div>
                        <p style="margin-left: 2rem; margin-right: 4.8rem; font-weight: 400;">ل</p>
                    </div>
                    
                </div>
                <div class="d-flex Pages justify-content-end"> 
                    <div class="d-flex justify-content-center w-100 align-items-center flex-row-reverse">
                    <p style="margin-left: 6.9rem; margin-right: 1.2rem;"> :نسخ<img style="margin-left: 1.2rem; width: 2.8rem;" src="./img/copies2.png"  alt=""></p>
                    <div class="position-relative d-flex align-items-center">

                        <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 35px; font-weight: 500; "
                            onclick="IncreaseCount('copies', false)">-</p>
                        <input disabled type="text" class="number text-center" id="copies" value="1">

                        <p style="user-select: none; cursor: pointer; margin: 0 !important; padding: 0; font-size: 35px; font-weight: 500; transform: rotate(180deg);"
                            onclick="IncreaseCount('copies', true)">+</p>
                    </div>
                    
                    <p class="amount">10 SAR</p>
                    </div>                    
                </div>
            </div>
            <div class="amount d-flex justify-content-between align-items-center flex-row-reverse">
                <h2 style="letter-spacing: .1rem;"> المجموع: <span>10 ريال</span></h2>   
                <div class="d-flex">
                    <a href="./pay.html" class="d-flex justify-content-center align-items-center back">  < تأكيد الدفع</button>
                        <a href="./code.html" class="d-flex justify-content-center align-items-center pay">></a>
                    </div> 
                    
            </div>
        </div>

        </div>
 
       </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


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

        const documentFiles = document.querySelector(".documentFiles")
        const currentPage = document.querySelectorAll(".currentPage")
        const paginationBtn = document.querySelector(".pagination button")
        const paginationP = document.querySelector(".pagination .number")
        const PDFs = ["./img/sample.pdf", "./img/sample2.pdf", "./img/sample.pdf", "./img/sample2.pdf"]


        for (let index = 0; index < PDFs.length; index++) {
            const element = PDFs[index];
            const canvas = document.createElement("canvas")
            canvas.setAttribute("class", "canvas")
            canvas.style.width = "100%"
            canvas.style.height = "auto"
            documentFiles.append(canvas)

        }
        const docs = document.querySelectorAll(".canvas")

        const buttonCount = docs.length / 2

        let initialCount = 0
        paginationP.innerHTML = docs.length
        for (let index = 0; index < docs.length; index++) {
            const element = docs[index];
            element.style.display = "none"
        }
        docs[initialCount].style.display = "flex"
        const Pagination = (value) => {

            if (value) {
                if (initialCount >= docs.length - 1) {
                    return
                } else {
                    initialCount++
                    paginationBtn.innerHTML = initialCount + 1
                    for (let index = 0; index < docs.length; index++) {
                        const element = docs[index];
                        element.style.display = "none"
                    }
                    docs[initialCount].style.display = "flex"
                }

            } else {
                if (initialCount <= 0) {
                    return
                } else {
                    initialCount--
                    paginationBtn.innerHTML = initialCount + 1
                    for (let index = 0; index < docs.length; index++) {
                        const element = docs[index];
                        element.style.display = "none"
                    }
                    docs[initialCount].style.display = "flex"
                }
            }
        }



        for (let index = 0; index < PDFs.length; index++) {
            const element = PDFs[index];
            const canvas = docs[index]
            console.log(element)
            pdfjsLib.getDocument(`${element}`).promise.then(doc => {
                // console.log(doc)
                doc.getPage(1).then(page => {

                    const context = canvas.getContext("2d")
                    // console.log(page.getViewport({scale:1}))
                    var viewport = page.getViewport({ scale: 1 })
                    // console.log(viewport)

                    canvas.width = viewport.width
                    canvas.height = viewport.height

                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    })
                })
            })
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
            }
            else if (id == "to") {
            if (custom) {

                if (value) {
                    document.getElementById(id).value++

                } else {
                    if (document.getElementById(id).value <= 0) {
                        document.getElementById(id).value = 0
                    }
                    else {
                        document.getElementById(id).value--

                    }
                }
            }
            } if (id == "copies") {
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

    </script>
</body>

</html>