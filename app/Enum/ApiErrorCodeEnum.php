<?php

namespace App\Enum;

use Illuminate\Http\Response;

class ApiErrorCodeEnum
{
    const InternalServerError           = ['errorCode' => 0, 'errorMessage' => 'Internal error', 'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR];
    const NotFoundModel                 = ['errorCode' => 1, 'errorMessage' => 'Registry not found', 'statusCode' => Response::HTTP_NOT_FOUND];
    const ValidationError               = ['errorCode' => 2, 'errorMessage' => 'Validation error', 'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY];
    const MethodNotAllowed              = ['errorCode' => 3, 'errorMessage' => 'Method not allowed', 'statusCode' => Response::HTTP_METHOD_NOT_ALLOWED];
    const NotFoundRoute                 = ['errorCode' => 4, 'errorMessage' => 'Route not found', 'statusCode' => Response::HTTP_NOT_FOUND];
    const NotImplemented                = ['errorCode' => 5, 'errorMessage' => 'Not implemented', 'statusCode' => Response::HTTP_NOT_IMPLEMENTED];
    const Unauthorized                  = ['errorCode' => 6, 'errorMessage' => 'Unauthorized', 'statusCode' => Response::HTTP_UNAUTHORIZED];
}
