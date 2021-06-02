<?php

namespace Kaswell\BoxApi\Auth;

/**
 * Class AuthenticateAbstract
 * @package Kaswell\BoxApi\Auth
 */
class AuthenticateAbstract
{
    /**
     * Config Box App from file
     * @var mixed
     */
    protected $config;

    /**
     * Access Token
     * @var string $token
     */
    protected $token = '';

    /**
     * Access Token for developer
     * @var string $dev_token
     */
    protected $dev_token = '';
}