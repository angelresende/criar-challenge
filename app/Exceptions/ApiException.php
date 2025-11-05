<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiException extends Exception
{
    protected string $errorCode;
    protected array $errors = [];
    protected int $statusCode;
    protected string $traceId;

    public function __construct(
        string $message = '',
        string $errorCode = 'INTERNAL_ERROR',
        int $statusCode = 500,
        array $errors = [],
        ?Exception $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);

        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        $this->traceId = Str::uuid()->toString();

        $this->logException();
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getTraceId(): string
    {
        return $this->traceId;
    }

    protected function logException(): void
    {
        $context = [
            'trace_id' => $this->traceId,
            'error_code' => $this->errorCode,
            'status_code' => $this->statusCode,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
            'errors' => $this->errors,
        ];

        if (request()) {
            $context['request'] = [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];
        }

        Log::channel('criarApi')->error($this->getMessage(), $context);
    }

    public function toArray(): array
    {
        return [
            'errorCode' => $this->errorCode,
            'errorMessage' => $this->getMessage(),
            'statusCode' => $this->statusCode,
            'traceId' => $this->traceId,
            'errors' => $this->errors,
        ];
    }
}
