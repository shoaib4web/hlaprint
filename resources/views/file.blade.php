<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Universal Converter</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap-5.0.2-dist/css/bootstrap.min.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.5.1/mammoth.browser.min.js" integrity="sha512-GXQyA7vCy/AVkekAX69TNpeV1QQu1m+K5Dhx38qyccm27y8nRDjUozszUdmGVnP/j7w9X5VEBmrXGaBZAORXgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html-pdf@3.0.1/lib/index.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/pptxgenjs@3.13.0-beta.0/dist/pptxgen.cjs.min.js"></script> --}}
    <script src="https://unpkg.com/pptxgenjs"></script>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <form action="save-uploaded" method="POST" enctype="multipart/form-data" id="form">
                        @csrf
                        <input type="file" name="file" id="file" accept=".jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx, .html, .txt, .pdf" hidden>
                        <div class="btn-select" id="file-select">Upload Your File</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/js/script.js')}}"></script>
</body>
</html>
