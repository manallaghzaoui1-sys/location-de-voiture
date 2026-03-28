<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user() ?? Auth::user();

        if ($admin && $admin->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('admin.login')->with('error', 'Accès administrateur non autorisé.');
    }
}
