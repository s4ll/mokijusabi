<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,  ...$roles): Response
    {
        // Pastikan user sudah login dan memiliki salah satu role yang diperbolehkan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            return response()->view('pages.notFound' , [] , 403); // Jika tidak sesuai, tampilkan page notFound error 403
        }

        return $next($request);
    }
}
