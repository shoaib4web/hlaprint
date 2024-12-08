<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

private $openRoutes = ['whatsapp','handle-payment-response','getTotalPages','printJobStatus','handle-payment-end/*','Verify'];

//modify this function
public function handle($request, Closure $next)
    {
        //add this condition 
    foreach($this->openRoutes as $route) {

      if ($request->is($route)) {
        return $next($request);
      }
    }

    return parent::handle($request, $next);
  }
}
