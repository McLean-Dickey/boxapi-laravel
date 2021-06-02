<?php

namespace Kaswell\BoxApi\Auth;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

/**
 * Class AuthJWT
 * @package Kaswell\BoxApi\Auth
 */
abstract class AuthJWT extends AuthenticateAbstract
{
    /**
     * APIBoxRequest constructor.
     */
    public function __construct()
    {
        $this->dev_token = env('BOX_DEV_TOKEN', '');

        $json = Storage::get('box_app_config.json');
        $this->config = json_decode($json);

        $this->getAccessToken();
    }

    protected function getAccessToken()
    {

        $private_key = $this->config->boxAppSettings->appAuth->privateKey;
        $passphrase = $this->config->boxAppSettings->appAuth->passphrase;
        $key = openssl_pkey_get_private($private_key, $passphrase);

        $authenticationUrl = env('BOX_AUTH_URL');

        $claims = [
            'iss' => $this->config->boxAppSettings->clientID,
            'sub' => $this->config->enterpriseID,
            'box_sub_type' => 'enterprise',
            'aud' => $authenticationUrl,
            'jti' => base64_encode(random_bytes(64)),
            'exp' => time() + 45,
            'kid' => $this->config->boxAppSettings->appAuth->publicKeyID
        ];

        $assertion = JWT::encode($claims, $key, 'RS512');

        $params = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
            'client_id' => $this->config->boxAppSettings->clientID,
            'client_secret' => $this->config->boxAppSettings->clientSecret
        ];

        $client = new Client();
        $response = $client->request('POST', $authenticationUrl, [
            'form_params' => $params
        ]);

        $data = $response->getBody()->getContents();

        $this->token = json_decode($data)->access_token;
    }


    /**
     * Example request with Guzzle package
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exampleRequest()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.box.com/2.0/folders/0/items', [
            'headers' => [
                'Authorization' => "Bearer {$this->token}"
            ]
        ])->getBody()->getContents();

        return $response;
    }
}
