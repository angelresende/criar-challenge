<?php

namespace App\Exceptions;

use App\Enum\ApiErrorCodeEnum;

class ApiErrorException extends ApiException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: ApiErrorCodeEnum::NotFoundModel['errorMessage'],
            errorCode: ApiErrorCodeEnum::NotFoundModel['errorCode'],
            statusCode: ApiErrorCodeEnum::NotFoundModel['statusCode'],
            errors: $errors
        );
    }
}
