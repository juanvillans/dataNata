<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

     public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->json(['message' => 'No se encontró el ID'], 404);
        }

        if ($exception instanceof AccessDeniedHttpException ) {
            return response()->json(['message' => 'Permiso denegado'], 403);
        }

        return parent::render($request, $exception);
    }
}
