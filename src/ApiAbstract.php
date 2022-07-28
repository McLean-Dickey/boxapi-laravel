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
     * @var string $base_api_url
     */
    protected $base_api_url = 'https://api.box.com/2.0/';

    /**
     * @var string $bodyFormat
     */
    protected $bodyFormat = 'json';

    /**
     * @var string $contentType
     */
    protected $contentType = 'application/json';

    /**
     * @var array $errors
     */
    protected $errors = EMPTY_ARRAY;

    /**
     * @var array $data
     */
    protected $data = EMPTY_ARRAY;

    /**
     * @var array $files
     */
    protected $files = EMPTY_ARRAY;

    /**
     * @var int $status_code
     */
    protected $status_code;

    /**
     * @var string $status_message
     */
    protected $status_message = '';

    /**
     * @var \Illuminate\Http\Client\Response $response
     */
    private $response;

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
     * @param array $data
     * @return $this
     */
    protected function setFiles(array $files)
    {
        $this->files = array_merge($this->files, $files);
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
     * @param string $path
     * @param string $method
     * @param bool $as_array
     * @return $this
     */
    protected function send(string $path, string $method = GET_METHOD)
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $request = Http::retry(1, 5)->withToken($this->token(), 'Bearer')
                ->baseUrl($this->base_api_url)
                ->contentType($this->contentType)
                ->bodyFormat($this->bodyFormat);

            foreach ($this->files as $file) {
                $request->attach(...$file);
            }
            $this->response = $request->send($method, $path, [$this->bodyFormat => $this->data]);
        } catch (Exception $exception) {
            $this->setErrors($exception);
        }
        return $this;
    }

    /**
     * @param string $response_type
     * @return array|object|string|\Illuminate\Support\Collection|\Illuminate\Http\Client\Response|void
     */
    public function response(string $response_type = FULL_RESPONSE)
    {
        if ($this->response instanceof \Illuminate\Http\Client\Response) {
            $this->setStatus($this->response->status());
            switch ($response_type) {
                case AS_IT:
                    return $this->response->body();
                case AS_ARRAY:
                    return $this->response->json();
                case AS_OBJECT:
                    return $this->response->object();
                case AS_COLLECT:
                    return $this->response->collect();
                default:
                    return $this->get();
            }
        }
    }

    /**
     * @return \Illuminate\Http\Client\Response|void
     */
    public function get()
    {
        if (!is_null($this->response))
            return $this->response;
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
