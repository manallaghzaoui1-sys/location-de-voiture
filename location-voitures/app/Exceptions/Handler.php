<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (TokenMismatchException $e, $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login')
                    ->with('error', 'Session expiree. Reconnectez-vous.');
            }

            return redirect()->route('login')
                ->with('error', 'Session expiree. Reessayez.');
        });

        $this->reportable(function (Throwable $e) {
            // Keep full technical details in logs only.
            Log::error('Unhandled exception', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);
        });

        $this->renderable(function (Throwable $e, $request) {
            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($status === 419 && ! $request->expectsJson()) {
                if ($request->is('admin') || $request->is('admin/*')) {
                    return redirect()->route('admin.login')
                        ->with('error', 'Session expiree. Reconnectez-vous.');
                }

                return redirect()->route('login')
                    ->with('error', 'Session expiree. Reessayez.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue.',
                ], $status);
            }

            return null;
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guard = $exception->guards()[0] ?? null;
        $loginRoute = $guard === 'admin' ? route('admin.login') : route('login');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifie.',
            ], 401);
        }

        return redirect()->guest($loginRoute);
    }
}
