<?php 

namespace App\Http\Middleware;

use App\Models\Csv;
use Carbon\Carbon;
use Closure;

class AuthorizeUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $last_csv = Csv::whereDay('created_at', Carbon::today()->day)->get();

        if ($last_csv->isEmpty()) 
        {
            return redirect()->back()->withErrors(['unavailable' => 'Service Unavailable. A CSV file has already been upload today.']);
        }

        return $next($request);
    }
}