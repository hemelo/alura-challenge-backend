<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Closure;

class ProcessingUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Storage::exists('temp.csv'))
        {
            return redirect()->back()->withErrors(['unavailable' => 'Service Unavailable. A CSV file is being processed.']);
        }

        $response = $next($request);
        return $response;
    }
}
