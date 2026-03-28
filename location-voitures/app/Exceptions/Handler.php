<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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
        $this->reportable(function (Throwable $e) {
            // Keep full technical details in logs only.
            Log::error('Unhandled exception', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);
        });

        $this->renderable(function (Throwable $e, $request) {
            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

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
