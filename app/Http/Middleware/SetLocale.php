<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = 'en';

        if (Auth::check() && !empty(Auth::user()->locale)) {
            $locale = Auth::user()->locale;
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
