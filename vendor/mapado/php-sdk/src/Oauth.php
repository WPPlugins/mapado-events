<?php

namespace Mapado\Sdk;

use GuzzleHttp\Client as HttpClient;
use Mapado\Sdk\Model\AccessToken;

class Oauth
{
    const TOKEN_URL = '/oauth/v2/token';
    const HOST = 'https://oauth2.mapado.com';

    /**
     * __construct
     *
     * @param string $clientId
     * @param string $clientSecret
     * @access public
     */
    public function __construct(HttpClient $http, $clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->http = $http;
    }

    /**
     * getClientToken
     *
     * @param string $scope
     * @access public
     * @return AccessToken
     */
    public function getClientToken($scope = 'activity:all:read')
    {
        return $this->callOauth([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => $scope,
        ]);
    }

    /**
     * getUserToken
     *
     * @param string $username
     * @param string $password
     * @param string $scope
     * @access public
     * @return AccessToken
     */
    public function getUserToken($username, $password, $scope = 'activity:all:read')
    {
        return $this->callOauth([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
            'scope' => $scope,
        ]);
    }

    /**
     * getFacebookToken
     *
     * @param string $facebookAccessToken
     * @param string $scope
     * @access public
     * @return AccessToken
     */
    public function getFacebookToken($facebookAccessToken, $scope = 'activity:all:read')
    {
        return $this->callOauth([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'https://facebookaccesstoken.oauth.mapado.com',
            'facebook_access_token' => $facebookAccessToken,
            'scope' => $scope,
        ]);
    }

    /**
     * refreshUserToken
     *
     * @param string $refreshToken
     * @param mixed $scope
     * @access public
     * @return AccessToken
     */
    public function refreshUserToken($refreshToken, $scope = null)
    {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
        if ($scope) {
            $params['scope'] = $scope;
        }
        return $this->callOauth($params);
    }

    /**
     * callOauth
     *
     * @param array $datas post datas
     * @access private
     * @return array
     */
    private function callOauth($datas)
    {
        $response = $this->http->post(
            self::HOST . self::TOKEN_URL,
            [
                'body' => $datas
            ]
        );

        return $this->transformToModel($response->json());
    }

    /**
     * transformToModel
     *
     * @param array $datas
     * @access private
     * @return AccessToken
     */
    private function transformToModel(array $datas)
    {
        $model = new AccessToken();

        $model->setAccessToken($datas['access_token'])
            ->setExpiresAt(new \DateTime('+ ' . $datas['expires_in'] . 'sec'))
            ->setTokenType($datas['token_type'])
            ->setScope($datas['scope'])
        ;

        if (isset($datas['refresh_token'])) {
            $model->setRefreshToken($datas['refresh_token']);
        }

        return $model;
    }

    /**
     * createClient
     *
     * @param string $clientId
     * @param string $clientSecret
     * @static
     * @access public
     * @return Oauth
     */
    public static function createOauth($clientId, $clientSecret)
    {
        // inject http client
        $client = new HttpClient();
        return new self($client, $clientId, $clientSecret);
    }
}
