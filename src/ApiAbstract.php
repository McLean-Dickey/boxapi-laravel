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
    public function setData(array $data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param int $code
     * @return array
     */
    protected function getStatus(int $code): array
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

        $this->code = $code;
        $this->message = $returnedCode[$code];

        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }

    /**
     * @param string $path
     * @param string $method
     * @return false|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
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
            $response = false;
        }
        return $response;
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