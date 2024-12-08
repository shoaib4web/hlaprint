<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade as PDF;

class PDFController extends Controller
{
    public function preview(Request $request, \Barryvdh\DomPDF\PDF $pdf)
    {
        $pdfData = $this->generatePDF($request);

        // Load the PDF into a DOMPDF instance
        $dompdf = $pdf->loadHTML($pdfData);

        // Render the PDF as a string
        $pdfContent = $dompdf->output();

        // Determine the total number of pages in the PDF
        $pageCount = $dompdf->getCanvas()->get_page_count();

        // Pass the PDF content, page count, and any other necessary data to the view
        return view('pdf.preview', compact('pdfContent', 'pageCount'));
    }

    // Your existing PDF generation logic goes here
    private function generatePDF(Request $request)
    {
        $pdf = app('dompdf.wrapper');

        // Generate the PDF content using Laravel DomPDF
        $pdf->loadView('pdf.template', ['data' => $request->all()]);



        // Return the PDF content
        return $pdf->output();
    }
}
