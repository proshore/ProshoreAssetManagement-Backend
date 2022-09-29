<?php

namespace App\Exceptions;

use PDOException;
use App\Traits\HttpResponse;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use HttpResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (UnauthorizedException $e, $request) {
            if ($request->is('api/*')) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
            }
        });

        $this->renderable(function (PDOException $e, $request) {
            if ($request->is('api/*')) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
            }
        });

        $this->renderable(function (ExpiredException $e, $request) {
            if ($request->is('api/*')) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
            }
        });


    }
}
