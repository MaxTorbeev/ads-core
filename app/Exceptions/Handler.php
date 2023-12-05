<?php

namespace App\Exceptions;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected string $defaultErrorMessage = 'Произошла ошибка при обработке запроса. Обратитесь к разработчику.';

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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

    public function render($request, Throwable $exception)
    {
        $data = config('app.debug')
            ? [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'exception' => $exception::class
            ]
            : [];

        switch (get_class($exception)) {
            case ModelNotFoundException::class:
            case MethodNotAllowedHttpException::class :
            case NotFoundHttpException::class :
                return response()->error(404, $data, $exception->getMessage(), 404);

            case ValidationException::class:
                return response()->error($exception->getCode(), $data, $exception->validator->errors()->first(), 422);

            case AuthenticationException::class:
                return response()->error($exception->getCode(), $data, $exception->getMessage(), 401);

            case AuthorizationException::class:
                return response()->error($exception->getCode(), $data, $exception->getMessage(), 403);

            default:
                $message = !config('app.debug')
                    ? $this->defaultErrorMessage
                    : $exception->getMessage();

                return response()->error($exception->getCode(), $data, $message);
        }
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });
    }
}
