<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;

abstract class Controller
{
    protected ApiResponse $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponse();
    }
}
