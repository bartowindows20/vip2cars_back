<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                $validationErrors = [];
                foreach ((array)$e->errors() as $error => $key) {
                    if (preg_match('/\.\d+/', $error, $matches) == 1) {
                        $index = explode('.', $error)[1];
                        $message = str_replace($error, $index + 1, $key[0]);
                    } else $message = $key[0];
                    if (!in_array(["error" => $message], $validationErrors)) {
                        $validationErrors[] = ["error" => $message];
                    }
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validationErrors,
                ], 422);
            }

            if ($e instanceof NotFoundHttpException && $e->getPrevious() instanceof ModelNotFoundException) {
                $previous = $e->getPrevious();

                $model = $previous->getModel();
                $ids   = $previous->getIds();
                $modelName = class_basename($model);

                return response()->json([
                    'success' => false,
                    'message' => "La entidad {$modelName} con id(s) " . implode(',', $ids) . " no fue encontrado.",
                ], 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ruta no fue encontrada.',
                ], 404);
            }

            if ($e instanceof AccessDeniedHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso denegado. No tienes permiso para realizar esta acción.',
                ], 403);
            }
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error interno',
                'error' => $e . '',
                'clase' => get_class($e)
            ], 500);
        });
    })->create();
