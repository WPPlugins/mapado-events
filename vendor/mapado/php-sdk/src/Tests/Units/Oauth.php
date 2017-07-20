<?php

namespace Mapado\Sdk\Tests\Units;

use atoum;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Symfony\Component\Yaml\Yaml;

class Oauth extends atoum
{
    /**
     * clientId
     *
     * @var string
     * @access private
     */
    private $clientId;

    /**
     * clientSecret
     *
     * @var string
     * @access private
     */
    private $clientSecret;

    /**
     * http
     *
     * @var HttpClient
     * @access private
     */
    private $http;

    /**
     * beforeTestMethod
     *
     * @param string $method
     * @access public
     * @return void
     */
    public function beforeTestMethod($method)
    {
        $this->clientId = 'client-id';
        $this->clientSecret = 'client-secret';

        $this->http = new HttpClient();
    }

    /**
     * testClientToken
     *
     * @access public
     * @return void
     */
    public function testClientToken()
    {
        $mock = new Mock(
            [
                new Response(
                    200,
                    [],
                    Stream::factory('{"access_token":"NzJkZTQyZTJjYjYyZjVmMDZiNzdmNDU5MjdiZTdkZTkwOWZjMTI4Y2M2ZGI2OTg3ZmQ4Y2QzMmM5ZGU3NjY3Zg","expires_in":3600,"token_type":"bearer","scope":null}')
                ),
                new Response(
                    400,
                    [],
                    Stream::factory('{"error":"invalid_client","error_description":"The client credentials are invalid"}')
                ),
            ]
        );

        $this->http->getEmitter()->attach($mock);


        $this
            ->given($this->newTestedInstance($this->http, $this->clientId, $this->clientSecret))

                ->if($token = $this->testedInstance->getClientToken())
                ->object($token)
                    ->isInstanceOf('\Mapado\Sdk\Model\AccessToken')
                ->string($token->getAccessToken())
                    ->isEqualTo('NzJkZTQyZTJjYjYyZjVmMDZiNzdmNDU5MjdiZTdkZTkwOWZjMTI4Y2M2ZGI2OTg3ZmQ4Y2QzMmM5ZGU3NjY3Zg')
                ->variable($token->getRefreshToken())
                    ->isNull()

            ->if($this->newTestedInstance($this->http, 'wrong client_id', 'wrong client password'))
            ->then
                ->exception(function () {
                    $this->testedInstance->getClientToken();
                })
                    ->isInstanceOf('GuzzleHttp\Exception\ClientException')
        ;
    }

    /**
     * testPasswordToken
     *
     * @access public
     * @return void
     */
    public function testPasswordToken()
    {
        $mock = new Mock(
            [
                new Response(
                    400,
                    [],
                    Stream::factory('{"error":"invalid_client","error_description":"The client credentials are invalid"}')
                ),
                new Response(
                    200,
                    [],
                    Stream::factory('{"access_token":"NTJkMTU3YzdjOThmZWNiNGY0MjYyZGZlYjYzMGJmYTcwNWE1ZDkwNWMxZjBkZmJlMTlmYmIzMjc1OTBkZDUzNg","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"YzU4NzQ5ZWQ4NmI0ZTM1ODZjMTBjYjhkYTI3NGU2YjI5ODEzYzc5N2FjNTMxMWRlOGI5NjQ4ZWFiYzZkN2ZjMQ"}')
                ),
            ]
        );

        $this->http->getEmitter()->attach($mock);

        $this
            ->if($this->newTestedInstance($this->http, $this->clientId, $this->clientSecret))
            ->then
                ->exception(function () {
                    $this->testedInstance->getUserToken('no_user', 'wrong_password');
                })
                    ->isInstanceOf('GuzzleHttp\Exception\ClientException')
            ->if($token = $this->testedInstance->getUserToken('test@mapado.com', 'test'))
            ->then
                ->string($token->getAccessToken())
                    ->isEqualTo('NTJkMTU3YzdjOThmZWNiNGY0MjYyZGZlYjYzMGJmYTcwNWE1ZDkwNWMxZjBkZmJlMTlmYmIzMjc1OTBkZDUzNg')
            ->then
                ->string($token->getRefreshToken())
                    ->isEqualTo('YzU4NzQ5ZWQ4NmI0ZTM1ODZjMTBjYjhkYTI3NGU2YjI5ODEzYzc5N2FjNTMxMWRlOGI5NjQ4ZWFiYzZkN2ZjMQ')
        ;
    }

    /**
     * testRefreshToken
     *
     * @access public
     * @return void
     */
    public function testRefreshToken()
    {
        $mock = new Mock(
            [
                new Response(
                    200,
                    [],
                    Stream::factory('{"access_token":"NTJkMTU3YzdjOThmZWNiNGY0MjYyZGZlYjYzMGJmYTcwNWE1ZDkwNWMxZjBkZmJlMTlmYmIzMjc1OTBkZDUzNg","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"YzU4NzQ5ZWQ4NmI0ZTM1ODZjMTBjYjhkYTI3NGU2YjI5ODEzYzc5N2FjNTMxMWRlOGI5NjQ4ZWFiYzZkN2ZjMQ"}')
                ),
                new Response(
                    200,
                    [],
                    Stream::factory('{"access_token":"MDE5OTU2MjQxMTMxMDY3Njk2NDU1ZTkyNmU3NTMwZDlmNjZlY2NiYmU1NmU2Yzk3M2FhNGMzZDFkMjVkMmY3Mw","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"NjdhMWM2MjdmNmI3YTMzODE3ZWQ5ZjhiMWQ4YmRmOTNhYTg2MjZjZTVmZTk0NDZiYTkxZTgxYzY4MGE0YTYyYg"}')
                ),
            ]
        );

        $this->http->getEmitter()->attach($mock);

        $this
            ->given($this->newTestedInstance($this->http, $this->clientId, $this->clientSecret))
            ->and($token = $this->testedInstance->getUserToken('test@mapado.com', 'test'))
            ->if($rToken = $this->testedInstance->refreshUserToken($token->getRefreshToken()))
            ->then
                ->string($rToken->getAccessToken())
                    ->isNotEqualTo($token->getAccessToken())
                    ->isEqualTo('MDE5OTU2MjQxMTMxMDY3Njk2NDU1ZTkyNmU3NTMwZDlmNjZlY2NiYmU1NmU2Yzk3M2FhNGMzZDFkMjVkMmY3Mw')
                ->string($rToken->getRefreshToken())
                    ->isEqualTo('NjdhMWM2MjdmNmI3YTMzODE3ZWQ5ZjhiMWQ4YmRmOTNhYTg2MjZjZTVmZTk0NDZiYTkxZTgxYzY4MGE0YTYyYg')

        ;
    }
}
