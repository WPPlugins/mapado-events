<?php

namespace Mapado\Sdk\Tests\Units\Transformer;

use atoum;

/**
 * Class RubricTransformer
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class RubricTransformer extends atoum
{
    public function testTransformItem()
    {
        $this
            ->given($rubricArray = $this->getRubricArray())
            ->if($rubric = $this->newTestedInstance->transformItem($rubricArray))
            ->then
                ->object($rubric)
                    ->isInstanceOf('Mapado\Sdk\Model\Rubric')
                ->string($rubric->getUuid())
                    ->isEqualTo('ac2f3fa5-d358-4427-a585-f829d0cb6089')
                ->string($rubric->getName())
                    ->isEqualTo('spectacle')
                ->object($parentList = $rubric->getParentList())
                    ->isInstanceOf('Mapado\Sdk\Model\MapadoList')
                    ->integer(count($parentList->getIterator()))->isEqualTo(1)
                ->object($parentList->getIterator()[0])
                    ->isInstanceOf('Mapado\Sdk\Model\Rubric')
                ->object($childrenList = $rubric->getChildrenList())
                    ->isInstanceOf('Mapado\Sdk\Model\MapadoList')
                    ->integer(count($childrenList->getIterator()))->isEqualTo(2)
                ->object($childrenList->getIterator()[0])
                    ->isInstanceOf('Mapado\Sdk\Model\Rubric')
        ;
    }

    private function getRubricArray()
    {
        return [
            "parent_list"=> [
                [
                    "uuid" => "4f7f10a9-984c-484b-a5f3-70c1df8efb72",
                    "name" => "activité",
                    "children_list" => [],
                    "_links" => [
                        "self" => [
                            "href" => "https =>//api.mapado.com/v1/rubrics/4f7f10a9-984c-484b-a5f3-70c1df8efb72"
                        ]
                    ]
                ]
            ],
            "uuid" => "ac2f3fa5-d358-4427-a585-f829d0cb6089",
            "name" => "spectacle",
            "children_list" => [
                [
                    "parent_list" => [],
                    "uuid" => "b9c4293f-56ae-4e35-bff1-940f81203c2e",
                    "name" => "cinéma",
                    "children_list" => [],
                    "_links" => [
                        "self" => [
                            "href" => "https =>//api.mapado.com/v1/rubrics/b9c4293f-56ae-4e35-bff1-940f81203c2e"
                        ],
                        "parent" => [
                            "href" => "https =>//api.mapado.com/v1/rubrics/ac2f3fa5-d358-4427-a585-f829d0cb6089"
                        ]
                    ]
                ],
                [
                    "parent_list" => [],
                    "uuid" => "9f122923-261c-4fbe-adfb-04d0a3ddd746",
                    "name" => "spectacle de cirque",
                    "children_list" => [],
                    "_links" => [
                        "self" => [
                            "href" => "https =>//api.mapado.com/v1/rubrics/9f122923-261c-4fbe-adfb-04d0a3ddd746"
                        ],
                        "parent" => [
                            "href" => "https =>//api.mapado.com/v1/rubrics/ac2f3fa5-d358-4427-a585-f829d0cb6089"
                        ]
                    ]
                ]
            ],
            "_links" => [
                "self" => [
                    "href" => "https =>//api.mapado.com/v1/rubrics/ac2f3fa5-d358-4427-a585-f829d0cb6089"
                ],
                "parent" => [
                    "href" => "https =>//api.mapado.com/v1/rubrics/4f7f10a9-984c-484b-a5f3-70c1df8efb72"
                ]
            ]
        ];
    }
}
