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

    protected $contentType = 'application/json';

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
     * @return $this
     */
    protected function multipart()
    {
        $this->bodyFormat = 'multipart';
        $this->contentType = 'multipart/form-data';
        return $this;
    }

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
        $this->status_code = $code;
        $this->status_message = RETURNED_CODE[$code];
    }

    /**
     * @return array
     */
    protected function getStatus()
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
                ->contentType($this->contentType)
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
        if ($response instanceof \Illuminate\Http\Client\Response) {
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
            Log::error('Box API has errors.', ['status' => $this->getStatus(), 'errors' => $this->errors]);
    }
}