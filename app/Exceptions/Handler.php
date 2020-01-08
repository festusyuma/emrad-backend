<?php

namespace Emrad\Exceptions;

use Exception;
use ErrorException;
use BadMethodCallException;
use Illuminate\Support\Facades\App;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (!App::environment('local'))
        {
            if($exception instanceof NotFoundHttpException) {
                return response([
                                    'status'=> 'fail',
                                    'error'=> 'Route not found',
                                    'data' => [],
                                    'message' => $exception->getMessage()
                                ],400);
            }
            else if ($exception instanceof UnauthorizedException) {
                return response([
                                    'status' => 'fail',
                                    'error' => $exception->getMessage(),
                                    'data' => []
                                ], 403);
            }
            else if ($exception instanceof ModelNotFoundException) {
                return response([
                                    'status' => 'fail',
                                    'error' => 'Entry for ' . str_replace('Emrad\\Models\\', '', $exception->getModel()) . ' not found',
                                    'data' => []
                                ], 404);
            }
            else if ($exception instanceof BadMethodCallException) {
                return response([
                                    'status' => 'fail',
                                    'error' => 'Call to undefined method',
                                    'data' => [],
                                    'message' => $exception->getMessage()
                                ], 500);
            }
            else if ($exception instanceof ErrorException) {
                return response([
                                    'status' => 'fail',
                                    'error' => 'property not found',
                                    'data' => [],
                                    'message' => $exception->getMessage()
                                ], 500);
            }
            else if ($exception instanceof AuthorizationException) {
                return response([
                                    'status' => 'fail',
                                    'error' => 'Not authorized to make the request',
                                    'data' => [],
                                    'message' => $exception->getMessage()
                                ], 403);
            }
            else if ($exception instanceof AuthenticationException) {
                return response([
                                    'status' => 'fail',
                                    'error' => 'Not authenticated to make the request',
                                    'data' => [],
                                    'message' => $exception->getMessage()
                                ], 403);
            }
        }
        return parent::render($request, $exception);
    }

}

