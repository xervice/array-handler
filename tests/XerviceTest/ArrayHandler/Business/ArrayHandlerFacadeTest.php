<?php namespace XerviceTest\ArrayHandler\Business;

use function foo\func;
use XerviceTest\ArrayHandler\Business\Helper\TestFieldHandler;

class ArrayHandlerFacadeTest extends \Codeception\Test\Unit
{
    /**
     * @var \XerviceTest\XerviceTestTester
     */
    protected $tester;

    /**
     * @group Xervice
     * @group ArrayHandler
     * @group Business
     * @group Facade
     * @group Integration
     */
    public function testHandleArray()
    {

        $sample = [
            [
                'keyOne'   => 'valueOne',
                'keyTwo'   => 'valueTwo',
                'keyThree' => 'valueThree',
                'keyFour'  => 'valueFour',
                'keyFive'  => 'valueFive',
                'keySix'   => [
                    'isNested' => [
                        'foo' => 'bar'
                    ],
                    'multi'    => 'array'
                ],
                'keySeven' => [
                    [
                        'data' => 'test'
                    ],
                    [
                        'data' => 'test'
                    ],
                    [
                        'data' => 'test'
                    ],
                    [
                        'data' => 'test'
                    ]
                ],
                'keyEight' => [
                    'eightsElement' => 'string',
                    'nesting'       => [
                        [
                            'foo' => [
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar']
                            ]
                        ],
                        [
                            'foo' => [
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar']
                            ]
                        ],
                        [
                            'foo' => [
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar']
                            ]
                        ],
                        [
                            'foo' => [
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar']
                            ]
                        ],
                        [
                            'foo' => [
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar'],
                                ['subfoo' => 'bar']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $config = [
            '*' => [
                'keyOne',
                'keyFour',
                [
                    'keyFive',
                    'keyTwo'   => function ($value) {
                        return $value . 'DONE';
                    },
                    'keyThree' => [
                        'testvalue' => 'TEST'
                    ]
                ],
                [
                    'keySix.isNested' => function ($value) {
                        return 'multiTest';
                    },
                    'keySix.*'        => function ($value) {
                        return $value . 'NESTED';
                    }
                ],
                [
                    'keySeven.*'               => [
                        [
                            'testvalue' => [
                                'data' => 'tested!'
                            ]
                        ]
                    ],
                    'keyEight.nesting.*.foo.*' => [
                        [
                            'testvalue' => [
                                'subfoo' => 'bartested'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $handler = new TestFieldHandler();

        $result = $this->tester->getFacade()->handleArray(
            $handler,
            $sample,
            $config
        )
        ;

        $this->assertEquals(
            [
                [
                    'keyOne'   => 'keyOne',
                    'keyTwo'   => 'valueTwoDONE',
                    'keyThree' => 'TEST',
                    'keyFour'  => 'keyFour',
                    'keyFive'  => 'keyFive',
                    'keySix'   => [
                        'multi'    => 'arrayNESTED',
                        'isNested' => 'multiTestNESTED'
                    ],
                    'keySeven' => [
                        [
                            'data' => 'tested!'
                        ],
                        [
                            'data' => 'tested!'
                        ],
                        [
                            'data' => 'tested!'
                        ],
                        [
                            'data' => 'tested!'
                        ]
                    ],
                    'keyEight' => [
                        'eightsElement' => 'string',
                        'nesting'       => [
                            [
                                'foo' => [
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested']
                                ]
                            ],
                            [
                                'foo' => [
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested']
                                ]
                            ],
                            [
                                'foo' => [
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested']
                                ]
                            ],
                            [
                                'foo' => [
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested']
                                ]
                            ],
                            [
                                'foo' => [
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested'],
                                    ['subfoo' => 'bartested']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            $result
        );
    }
}