<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class CustomErrorHandler extends ExceptionHandler
{
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function render($request, Exception $exception)
    {
        return view('arabic.error', [], 500);
    }
}?>