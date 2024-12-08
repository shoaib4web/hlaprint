<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class MainController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function save_pdf(Request $request) {
        $file = $request->file('file');
        $file->move(public_path('files'), $file->getClientOriginalName());
        return response()->json(['success' => true]);
      }
    public function save_uploaded(Request $request) {

        $file = $request->file('file');
        $file->move(public_path('files'), $file->getClientOriginalName());
        return redirect()->back();
      }
      public function convertPptxToPdf(Request $request)
        {
            $request->validate([
                'ppt' => 'required|mimetypes:application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ]);

            $ppt = $request->file('ppt');
            $tempPath = $ppt->store('temp');

            $pdfPath = '/path/to/pdf/output.pdf';

            $command = sprintf(
                'soffice --headless --convert-to pdf --outdir %s %s',
                escapeshellarg(dirname($pdfPath)),
                escapeshellarg($ppt->getRealPath())
            );

            $process = Process::fromShellCommandline($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Failed to convert PPTX to PDF');
            }

            // Delete the temporary PPTX file
            unlink($tempPath);

            return response()->download($pdfPath, 'converted_file.pdf');
        }

}
