<!DOCTYPE html>
<html>
<head>
    <title>PDF Preview</title>
    <style>
        /* Add any necessary styles for the PDF preview here */
    </style>
</head>
<body>
    <div id="pdfContainer"></div>

    <script src="{{ asset('pdfjs-dist/build/pdf.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pdfData = "{{ $pdfContent }}";
            var pageCount = {{ $pageCount }};
            var currentPage = 1;

            // Initialize PDF.js
            pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('pdfjs-dist/build/pdf.worker.js') }}";

            // Load the PDF
            var loadingTask = pdfjsLib.getDocument({ data: atob(pdfData) });

            loadingTask.promise.then(function (pdf) {
                // Display the initial page
                renderPage(pdf, currentPage);

                // Handle next button click
                document.getElementById('nextBtn').addEventListener('click', function () {
                    if (currentPage < pageCount) {
                        currentPage++;
                        renderPage(pdf, currentPage);
                    }
                });

                // Handle previous button click
                document.getElementById('prevBtn').addEventListener('click', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        renderPage(pdf, currentPage);
                    }
                });
            });

            // Function to render a specific page
            function renderPage(pdf, pageNumber) {
                pdf.getPage(pageNumber).then(function (page) {
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');

                    var viewport = page.getViewport({ scale: 1.5 });

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };

                    page.render(renderContext).promise.then(function () {
                        var pdfContainer = document.getElementById('pdfContainer');
                        pdfContainer.innerHTML = '';
                        pdfContainer.appendChild(canvas);

                        // Update the page counter
                        document.getElementById('pageCounter').textContent = pageNumber + ' / ' + pageCount;
                    });
                });
            }
        });
    </script>

    <div>
        <button id="prevBtn">Previous</button>
        <button id="nextBtn">Next</button>
    </div>

    <div id="pageCounter"></div>
</body>
</html>
