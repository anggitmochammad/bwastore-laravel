<?php

namespace App\Http\Middleware;

use Closure;

// harus ditambahkan
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // memanggil field roles pada tabel user apakah roles tersebut ADMIN
        if(Auth::user() && Auth::user()->roles == 'ADMIN')
        {
            return $next($request);
        }
        return redirect('/');
    }
}
