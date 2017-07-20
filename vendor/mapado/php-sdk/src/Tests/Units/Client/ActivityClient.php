<?php

namespace Mapado\Sdk\Tests\Units\Client;

use atoum;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Ring\Client\MockHandler;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Mapado\Sdk\Model\AccessToken;

/**
 * Class ActivityClient
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class ActivityClient extends atoum
{
    private $token;
    private $transformer;

    public function beforeTestMethod($method)
    {
        $this->token = new AccessToken;
        $addressTansformer = new \Mapado\Sdk\Transformer\AddressTransformer();
        $this->transformer = new \Mapado\Sdk\Transformer\ActivityTransformer($addressTansformer);
    }

    public function testFindBy()
    {
        $http = new HttpClient();
        $history = new History();
        $mock = new Mock(
            [
                new Response(
                    200,
                    [],
                    Stream::factory(file_get_contents(__DIR__ . '/../../data/activity-list.json'))
                ),
            ]
        );

        $http->getEmitter()->attach($mock);
        $http->getEmitter()->attach($history);

        $this
            ->given($instance = $this->newTestedInstance($http, $this->token, $this->transformer))
            ->then
                ->given($list = $instance->findBy(['image_sizes' => ['320x160', '640x320']]))
                ->object($list)
                    ->isInstanceOf('Mapado\Sdk\Model\MapadoList')
                ->integer($list->getLimit())
                    ->isEqualTo(9)
                ->integer($list->getPage())
                    ->isEqualTo(2)
                ->integer($list->getPages())
                    ->isEqualTo(9765)
                ->integer($list->getTotalHits())
                    ->isEqualTo(87879)
                ->array($list->getLinks())
                    ->size->isEqualTo(5)
            ->given($lastRequest = $history->getLastRequest())
            ->then
                ->string($lastRequest->getUrl())
                ->contains('image_sizes%5B0%5D=320x160')
                ->contains('image_sizes%5B1%5D=640x320')
        ;
    }

    /**
     * testFindOne
     *
     * @access public
     * @return void
     */
    public function testFindOne()
    {
        $http = new HttpClient();
        $history = new History();
        $mock = new Mock(
            [
                new Response(
                    200,
                    [],
                    Stream::factory('{"data":[]}')
                ),
            ]
        );

        $http->getEmitter()->attach($mock);
        $http->getEmitter()->attach($history);

        $this
            ->given($instance = $this->newTestedInstance($http, $this->token, $this->transformer))
            ->then
                ->object($instance->findOne('abc', ['image_sizes' => ['320x160', '640x320']]))
            ->given($lastRequest = $history->getLastRequest())
            ->then
                ->string($lastRequest->getUrl())
                ->contains('/activities/abc?image_sizes%5B0%5D=320x160&image_sizes%5B1%5D=640x320')
        ;
    }

    /**
     * testProgram
     *
     * @access public
     * @return void
     */
    public function testProgram()
    {
        // return the same response over and over
        $handler = new MockHandler(
            [
                'status' => 200,
                'body' => file_get_contents(__DIR__ . '/../../data/transbordeur-program.json'),
            ]
        );
        $http = new HttpClient([ 'handler' => $handler ]);
        $history = new History();
        $http->getEmitter()->attach($history);

        $this
            ->given($instance = $this->newTestedInstance($http, $this->token, $this->transformer))
            ->then
                ->given($program = $instance->program('uuid'))
                ->object($program)
                    ->isInstanceOf('IteratorAggregate')
                ->if($lastRequest = $history->getLastRequest())
                ->then
                    ->string($lastRequest->getUrl())
                    ->contains('/activities/uuid/program')
                ->object($program->getIterator()->current())
                    ->isInstanceOf('Mapado\Sdk\Model\Activity')

            ->then
                ->object($instance->program('uuid', ['limit' => 20]))
                    ->isInstanceOf('IteratorAggregate')
                ->then
                    ->string($history->getLastRequest()->getUrl())
                    ->contains('/activities/uuid/program?limit=20')

            ->then
                ->object($instance->program('uuid', ['limit' => 20, 'offset' => 30]))
                    ->isInstanceOf('IteratorAggregate')
                ->then
                    ->string($history->getLastRequest()->getUrl())
                    ->contains('/activities/uuid/program?limit=20&offset=30')
        ;
    }
}
