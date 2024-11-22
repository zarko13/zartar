<?php

namespace App\Repositories;

use Exception;

class ServiceResponse
{
    public $data;

    public $errors;

    public $statusCode;

    public function __construct($errors = null, $data = [], $statusCode = null)
    {
        $this->errors = $errors;
        $this->data = $data;
        $this->statusCode = $statusCode ? $statusCode : ($this->errors ? 422 : 200);
    }

    public function throwExceptionWithErrorMessages()
    {
        throw new Exception($this->convertErrorsToString());
    }

    public function convertErrorsToString()
    {
        return implode('.', $this->errors);
    }

    public function toAPIResponse()
    {
        return $this->errors ? $this->errors : $this->data;
    }

    public function toAgGridResponse()
    {
        return $this->errors ? response($this->errors, 422) : response($this->data, 200);
    }


    public function returnOrFail(){
        return $this->errors ? $this->throwExceptionWithErrorMessages() : $this;
    }

    public function toAsyncResponse(){
        return response()->json($this->toAPIResponse(), $this->errors ? 422 : 200);
    }

    public function hasErrors(){
        return $this->errors ? true : false;
    }
}
