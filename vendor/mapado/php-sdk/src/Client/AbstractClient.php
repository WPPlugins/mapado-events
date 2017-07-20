<?php

namespace Mapado\Sdk\Client;

use GuzzleHttp\Client as HttpClient;
use Mapado\Sdk\Model\AccessToken;
use Mapado\Sdk\Transformer\TransformerInterface;

abstract class AbstractClient
{
    const VERSION_URL = '/v1';
    const HOST = 'https://api.mapado.com';

    /**
     * accessToken
     *
     * @var string
     * @access private
     */
    private $accessToken;

    /**
     * transformer
     *
     * @var TransformerInterface
     * @access protected
     */
    protected $transformer;

    /**
     * __construct
     *
     * @param HttpClient $http
     * @param AccessToken $accessToken
     * @param TransformerInterface $transformer
     * @access public
     */
    public function __construct(HttpClient $http, AccessToken $accessToken, TransformerInterface $transformer = null)
    {
        $this->http = $http;
        $this->accessToken = $accessToken;
        $this->transformer = $transformer;
    }

    /**
     * apiGet
     *
     * @param string $rootUrl
     * @param string $url
     * @access protected
     * @return Response
     * @throw \OAuth2\OAuth2Exception
     */
    protected function apiGet($url = '', array $parameters = [])
    {
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        $url = self::HOST . self::VERSION_URL . $this->getRootUrl() . $url;
        $auth = 'Bearer ' . $this->accessToken->getAccessToken();

        return $this->http->get($url, ['headers' => [ 'Authorization' => $auth ]]);
    }

    /**
     * getRootUrl
     *
     * @abstract
     * @access public
     * @return string
     */
    abstract protected function getRootUrl();
}
