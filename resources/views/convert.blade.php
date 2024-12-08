<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Convert Word To PDF</h1>
    <div>
        <a class="btn btn-info" href="{{ route('upload') }}">Upload File</a>
    </div>
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif

         <div class="mt-4">
        <a class="" href="{{ route('convertToPdf') }}">Convert Uploaded docx/doc File To PDF</a>
        </div>
        
        {{--
        <div class="mt-4">
            <a class="" href="{{ route('doctopdf') }}">Convert Uploaded doc File To PDF</a>
        </div>

        <div class="mt-4">
            <a class="" href="{{ route('xlsxtopdf') }}">Convert Uploaded xlsx File To PDF</a>
        </div> --}}
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>

