<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enum\ApiErrorCodeEnum;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Log;
use App\Exceptions\NotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException as IlluminateValidationException;

class Handler extends ExceptionHandler
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }
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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return standardized responses
     */
    protected function handleApiException(Request $request, Throwable $e)
    {
        try {
            $apiResponse = new ApiResponse();

            $this->logException($request, $e);

            if ($e instanceof ApiException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    $e->toArray(),
                    is_array($e->getErrors()) ? $e->getErrors() : [$e->getErrors()]
                );
            }

            if ($e instanceof NotFoundException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::NotFoundModel,
                    $e->getErrors()
                );
            }

            if ($e instanceof IlluminateValidationException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::ValidationError,
                    is_array($e->errors()) ? $e->errors() : [$e->errors()]
                );
            }

            if ($e instanceof ModelNotFoundException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::NotFoundModel,
                    [$e->getMessage()]
                );
            }

            if ($e instanceof NotFoundHttpException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::NotFoundRoute,
                    [$e->getMessage()]
                );
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::MethodNotAllowed,
                    [$e->getMessage()]
                );
            }

            if ($e instanceof QueryException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::InternalServerError,
                    [$e->getMessage()]
                );
            }

            if ($e instanceof AuthenticationException) {
                return $apiResponse->responseErrorEnveloper(
                    $request,
                    ApiErrorCodeEnum::Unauthorized,
                    [$e->getMessage()]
                );
            }

            return $apiResponse->responseErrorEnveloper(
                $request,
                ApiErrorCodeEnum::InternalServerError,
                [config('app.debug') ? $e->getMessage() : 'Internal server error']
            );
        } catch (\Exception $handlerError) {
            Log::error('Error in Handler', [
                'original_exception' => $e->getMessage(),
                'handler_error' => $handlerError->getMessage(),
                'handler_file' => $handlerError->getFile(),
                'handler_line' => $handlerError->getLine()
            ]);


            return response()->json([
                'success' => false,
                'result' => [],
                'error' => [
                    'errorCode' => ApiErrorCodeEnum::InternalServerError['errorCode'],
                    'errorMessage' => ApiErrorCodeEnum::InternalServerError['errorMessage'],
                    'errorList' => []
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Log exceptions automatically
     */
    protected function logException(Request $request, Throwable $e): void
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_data' => $request->all(),
            'exception_class' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];

        // Se for uma ApiException, já tem trace_id
        if ($e instanceof ApiException) {
            $context['trace_id'] = $e->getTraceId();
            $context['error_code'] = $e->getErrorCode();
            $context['status_code'] = $e->getStatusCode();
        }

        $logLevel = $this->getLogLevel($e);
        Log::channel('criarApi')->$logLevel($e->getMessage(), $context);
    }

    /**
     * Determine log level based on exception type
     */
    protected function getLogLevel(Throwable $e): string
    {
        if ($e instanceof ApiException) {
            return 'warning';
        }

        if ($e instanceof IlluminateValidationException) {
            return 'warning';
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return 'warning';
        }

        return 'error';
    }
}
