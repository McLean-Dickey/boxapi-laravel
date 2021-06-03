<?php

namespace Kaswell\BoxApi;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Kaswell\BoxApi\Auth\Authenticate;

/**
 * Class ApiAbstract
 * @package Kaswell\BoxApi
 */
class ApiAbstract extends Authenticate
{
    /**
     * @var string
     */
    protected $base_api_url = 'https://api.box.com/2.0/';

    /**
     * @var string $bodyFormat
     */
    protected $bodyFormat = 'json';

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var int
     */
    protected $status_code;

    /**
     * @var string
     */
    protected $status_message = '';

    /**
     * @var bool
     */
    protected $as_array = false;

    /**
     * @param $error
     * @return $this
     */
    protected function setErrors($error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $data
     * @return $this
     */
    protected function setData(array $data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * @param int $code
     * @return void
     */
    protected function setStatus(int $code)
    {
        $returnedCode = [
            100 => "Continue",
            101 => "Switching Protocols",
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            203 => "Non-Authoritative Information",
            204 => "No Content",
            205 => "Reset Content",
            206 => "Partial Content",
            300 => "Multiple Choices",
            301 => "Moved Permanently",
            302 => "Found",
            303 => "See Other",
            304 => "Not Modified",
            305 => "Use Proxy",
            306 => "(Unused)",
            307 => "Temporary Redirect",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Timeout",
            409 => "Conflict",
            410 => "Gone",
            411 => "Length Required",
            412 => "Precondition Failed",
            413 => "Request Entity Too Large",
            414 => "Request-URI Too Long",
            415 => "Unsupported Media Type",
            416 => "Requested Range Not Satisfiable",
            417 => "Expectation Failed",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout",
            505 => "HTTP Version Not Supported"
        ];

        $this->status_code = $code;
        $this->status_message = $returnedCode[$code];
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        return [
            'code' => $this->status_code,
            'message' => $this->status_message,
        ];
    }

    /**
     * @return $this
     */
    public function asArray()
    {
        $this->as_array = true;
        return $this;
    }

    /**
     * @param string $path
     * @param string $method
     * @param bool $as_array
     * @return array|object|void
     */
    protected function send(string $path, string $method = GET_METHOD)
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken($this->token, 'Bearer')
                ->baseUrl($this->base_api_url)
                ->bodyFormat($this->bodyFormat)
                ->withOptions([$this->bodyFormat => $this->data])
                ->send($method, $path);
        } catch (Exception $exception) {
            $this->setErrors($exception);
            return;
        }
        return $this->parseResponse($response);
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return object|array|void
     */
    protected function parseResponse(\Illuminate\Http\Client\Response $response)
    {
        if ($response instanceof \Illuminate\Http\Client\Response){
            $this->setStatus($response->status());
            return $this->as_array ? $response->json() : $response->object();
        }
        return;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if (count($this->errors) > 0)
            Log::error('Box API has errors.', $this->errors);
    }
}