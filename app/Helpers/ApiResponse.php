<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use stdClass;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    public function __construct(
        private stdClass $response = new stdClass
    ) {
    }

    public function responseErrorEnveloper(Request $request, array $errorCodeEnum, array $errors = [])
    {

        if (env('APP_ENV') == 'local') {
        foreach ($errors as $key => $error) {
            unset($errors[$key]);
                $errors[$this->snakeCaseToCamelCase($key)] = $error;
            }
        } else {
            $errors = [];
        }

        $this->response->success = false;
        $this->response->result = [];
        $this->response->error = $this->getErrorResponse(
            errorCode: $errorCodeEnum['errorCode'],
            errorMessage: $errorCodeEnum['errorMessage'],
            errorList: $errors
        );
        $this->response->meta = $this->getMetaResponse();

        Log::channel('criarApi')->warning(
            $errorCodeEnum['errorMessage'],
            [
                'url' => $request->url(),
                'method' => $request->method(),
                'request' => $request->all(),
                'errorList' => $errors
            ]
        );

        return response()->json($this->response, $errorCodeEnum['statusCode']);
    }

    public function responseEnveloper(
        array|Model|Collection|JsonResource $data = [],
        array $errorList = [],
        bool $status = true,
        ?int $errorCode = null,
        string $errorMessage = "Generic Error",
        int $statusCode = Response::HTTP_OK,
        array $additionalInformation = [],
        $jsonOptions = 0
    ) {

        if (isset($data['errorCodeJuniper'])) {
            $status = false;
            $statusCode = $data['statusCode'];
            $errorCode = $data['errorCode'];
            $errorMessage = $data['errorMessage'];
            $data = [];
        }

        $this->response->success = $status;
        $this->response->result = $data;
        $this->response->error = $status ? [] : $this->getErrorResponse($errorCode, $errorMessage, $errorList);
        $this->response->meta = $this->getMetaResponse($data, $additionalInformation);

        return response()->json($this->response, $statusCode, options: $jsonOptions);
    }

    protected function getErrorResponse($errorCode, $errorMessage, $errorList): stdClass|array
    {
        $error = new stdClass;

        $error->errorCode = $errorCode;
        $error->errorMessage = $errorMessage;
        $error->errorList = $errorList;

        return $error;
    }

    protected function getMetaResponse(array|Model|Collection|JsonResource $data = [], array $additionalInformation = []): stdClass
    {
        $meta = new stdClass;
        $paginationOptions = new stdClass;
        $paginationLinks = new stdClass;

        if ($data && !is_array($data) && $data->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            foreach ($this->makePaginationLinks($data) as $key => $value) {
                $paginationLinks->$key = $value;
            }

            $paginationOptions->totalPages   = $data->lastPage();
            $paginationOptions->totalResults = $data->total();
            $paginationOptions->pageResults  = $data->count();
            $paginationOptions->pageNumber   = $data->currentPage();
            $paginationOptions->links        = $paginationLinks;

            $meta->pagination = $paginationOptions;
        }

        if (!empty($additionalInformation)) {
            $meta->additionalInformation = $additionalInformation;
        }

        return $meta;
    }

    protected function makePaginationLinks($data)
    {
        $baseUrl = url()->current();

        $queryString = array_filter(request()->query(), function ($key) {
            return $key !== 'page';
        }, ARRAY_FILTER_USE_KEY);

        $baseUrlWithQuery = $baseUrl . '?' . http_build_query($queryString);

        $result["currentPage"] = $baseUrlWithQuery . "&page=" . $data->currentPage();

        if ($data->currentPage() != 1) {
            $result["firstPage"] = $baseUrlWithQuery . "&page=1";
        }

        if ($data->currentPage() < $data->lastPage()) {
            $result["lastPage"] = $baseUrlWithQuery . "&page=" . $data->lastPage();
        }

        if (($data->currentPage() + 1) <= $data->lastPage()) {
            $result["nextPage"] = $baseUrlWithQuery . "&page=" . ($data->currentPage() + 1);
        }

        if (($data->currentPage() - 1) >= 1) {
            $result["previousPage"] = $baseUrlWithQuery . "&page=" . ($data->currentPage() - 1);
        }

        return $result;
    }

    public function snakeCaseToCamelCase(string $string, array $noStrip = []): string
    {
        $string = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $string);
        $string = trim($string);

        $string = ucwords($string);
        $string = str_replace(" ", "", $string);
        $string = lcfirst($string);

        return $string;
    }
}
