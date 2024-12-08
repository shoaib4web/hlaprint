<form action="{{route('testPPTX')}}" method="post" enctype="multipart/form-data"> 
    @csrf
    <input type="file" name="pptx_file">
    <button type="submit">Convert to PDF</button>
    <script>
        function myFunction() {
            var x = document.getElementById("pptx_file").value;
            document.getElementById("demo").innerHTML = x;
        }
    </script>
</form>