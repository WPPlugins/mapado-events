<?php

namespace Mapado\Sdk\Tests\Units\Client;

use atoum;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;
use Mapado\Sdk\Model\AccessToken;

/**
 * Class UserClient
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class UserClient extends atoum
{
    private $token;
    private $transformer;

    public function beforeTestMethod($method)
    {
        $this->token = new AccessToken;
        $this->transformer = new \Mapado\Sdk\Transformer\UserTransformer();
    }

    /**
     * testMe
     *
     * @access public
     * @return void
     */
    public function testMe()
    {
        $http = new HttpClient();
        $mock = new Mock(
            [
                new Response(
                    403,
                    [],
                    Stream::factory('{"error":{"code":403,"message":"Forbidden"}}')
                ),
                new Response(
                    200,
                    [],
                    Stream::factory('{"fullname":"test@mapado.com","uuid":"1431cfe9-f56a-4f58-8abc-cec486632148","avatar":"http:\/\/img1.mapado.com\/2014\/4\/25\/535a0d891214e-animals-chocolate-cute-hedgehog-favim-com-521238.jpg","_links":{"self":{"href":"https:\/\/api.mapado.com\/v1\/users\/1431cfe9-f56a-4f58-8abc-cec486632148"},"favorites":{"href":"https:\/\/api.mapado.com\/v1\/users\/1431cfe9-f56a-4f58-8abc-cec486632148\/favorites"}}}')
                ),
            ]
        );

        $http->getEmitter()->attach($mock);


        $this
            ->given($instance = $this->newTestedInstance($http, $this->token, $this->transformer))
            ->then
                ->exception(function() use ($instance) {
                    $instance->me();
                })
                    ->isInstanceOf('GuzzleHttp\Exception\ClientException')
            ->given($instance = $this->newTestedInstance($http, $this->token, $this->transformer))
                ->and($me = $instance->me())
            ->then
                ->object($me)
                    ->isInstanceOf('Mapado\Sdk\Model\User')
                ->string($me->getFullname())
                    ->isEqualTo('test@mapado.com')
                ->string($me->getAvatar())
                    ->isEqualTo('http://img1.mapado.com/2014/4/25/535a0d891214e-animals-chocolate-cute-hedgehog-favim-com-521238.jpg')
                ->string($me->getUuid())
                    ->isEqualTo('1431cfe9-f56a-4f58-8abc-cec486632148')
        ;
    }
}
