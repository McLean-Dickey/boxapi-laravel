<?php

namespace Kaswell\BoxApi\Auth;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Class Authenticate
 * @package Kaswell\BoxApi\Auth
 */
abstract class Authenticate
{
    /**
     * @var string $auth_path
     */
    protected $auth_path = 'https://api.box.com/oauth2/token';

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

    /**
     * AuthenticateAbstract constructor.
     * @return void
     */
    public function __construct()
    {
        $this->dev_token = config('boxapi.dev_token');

        if (Storage::exists(config('boxapi.config_file'))) {
            $this->config = json_decode(Storage::get(config('boxapi.config_file')));
        } else {
            $this->config = json_decode(file_get_contents(__DIR__ . '/box_app_config.json'));
        }

        if (Cache::has($this->config->enterpriseID)) {
            $this->token = Cache::get($this->config->enterpriseID);
        } else {
            $this->getAccessToken();
        }
    }

    /**
     * @return void
     */
    private function getAccessToken(): void
    {
        try {
            if (config('boxapi.dev_mode')) {
                $this->token = $this->dev_token;
            } else {
                $key = openssl_pkey_get_private($this->config->boxAppSettings->appAuth->privateKey, $this->config->boxAppSettings->appAuth->passphrase);

                $claims = [
                    'iss' => $this->config->boxAppSettings->clientID,
                    'sub' => $this->config->enterpriseID,
                    'box_sub_type' => 'enterprise', /** 'enterprise' or 'user'  */
                    'aud' => $this->auth_path,
                    'jti' => base64_encode(random_bytes(64)),
                    'exp' => time() + 45,
                    'kid' => $this->config->boxAppSettings->appAuth->publicKeyID
                ];

                $data = [
                    'client_id' => $this->config->boxAppSettings->clientID,
                    'client_secret' => $this->config->boxAppSettings->clientSecret,
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => JWT::encode($claims, $key, 'RS512'),
                ];

                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::asForm()->post($this->auth_path, $data);

                if ($response->successful()) $this->token = $response->object()->access_token ?? '';
            }
        } catch (Exception $exception) {
            return;
        }
        Cache::put($this->config->enterpriseID, $this->token, now()->addSeconds(45));
    }

    /**
     * @return void
     */
    private function getClientCredentialGrant(): void
    {
        try {
            $data = [
                'client_id' => $this->config->boxAppSettings->clientID,
                'client_secret' => $this->config->boxAppSettings->clientSecret,
                'grant_type' => 'client_credentials',
                'box_subject_type' => 'enterprise',
                'box_subject_id' => $this->config->enterpriseID,
            ];

            $response = Http::asForm()->post($this->auth_path, $data);

            if ($response->successful()) $this->token = $response->object()->access_token ?? '';
        } catch (Exception $exception) {
            return;
        }
    }


    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }
    /*
     * --data-urlencode ‘client_id=<client_id>’ \
     * --data-urlencode ‘client_secret=<client_secret>’ \
     * --data-urlencode ‘grant_type=client_credentials’ \
     * --data-urlencode ‘box_subject_type=enterprise’ \
     * --data-urlencode ‘box_subject_id=<enterprise_id>’
     */
}
