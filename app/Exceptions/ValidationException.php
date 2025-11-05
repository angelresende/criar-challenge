<?php

namespace App\Exceptions;

use App\Enum\ApiErrorCodeEnum;

class ValidationException extends ApiException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: ApiErrorCodeEnum::ValidationError['errorMessage'],
            errorCode: ApiErrorCodeEnum::ValidationError['errorCode'],
            statusCode: ApiErrorCodeEnum::ValidationError['statusCode'],
            errors: $errors
        );
    }
}

