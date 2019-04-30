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
    public function testHandleCallable()
    {
        $sample = [
            'testOne' => 'valueOne',
            'testTwo' => [
                'valueOne',
                'valueTwo',
                [
                    'valueThree'
                ]
            ]
        ];

        $config = [
            '*' => function ($value) {
                if (is_array($value)) {
                    $value[0] = $value[0] . '1';
                } else {
                    return $value . '1';
                }

                return $value;
            },
            'testOne' => function ($value) {
                return $value . '2';
            },
            'testTwo.*' => function ($value) {
                $value[0] = $value[0] . '3';
                return $value;
            }
        ];

        $handler = new TestFieldHandler();

        $result = $this->tester->getFacade()->handleArray(
            $handler,
            $sample,
            $config
        );

        $this->assertEquals(
            [
                'testOne' => 'valueOne12',
                'testTwo' => [
                    'valueOne1',
                    'valueTwo',
                    [
                        'valueThree3'
                    ]
                ]
            ],
            $result
        );
    }

    /**
     * @group Xervice
     * @group ArrayHandler
     * @group Business
     * @group Facade
     * @group Integration
     */
    public function testHandleString()
    {
        $sample = [
            'testOne' => 'valueOne',
            'testTwo' => [
                'valueOne',
                'valueTwo',
                [
                    'valueThree'
                ]
            ]
        ];

        $config = [
            'testOne',
            'testTwo' => 'Tested'
        ];

        $handler = new TestFieldHandler();

        $result = $this->tester->getFacade()->handleArray(
            $handler,
            $sample,
            $config
        );

        $this->assertEquals(
            [
                'testOne' => 'testOne',
                'testTwo' => 'Tested'
            ],
            $result
        );
    }

    /**
     * @group Xervice
     * @group ArrayHandler
     * @group Business
     * @group Facade
     * @group Integration
     *
     * @skip
     */
    public function testHandleArray()
    {

        $sample = $this->getSampleData();

        $config = [
            '*' => [
                'keyOne',
                'keyFour',
                [
                    'keyFive',
                    'keyTwo' => function ($value) {
                        return $value . 'DONE';
                    },
                    'keyThree' => [
                        [
                            'testvalue' => 'TEST'
                        ]
                    ]
                ],
                [
                    'keySix' => [
                        'isNested' => function ($value) {
                            return 'multiTest';
                        }
                    ],
                    'keySix.*' => function ($value) {
                        return $value . 'NESTED';
                    }
                ],
                [
                    'keySeven.*' => [
                        'testvalue' => [
                            'data' => 'tested!'
                        ]
                    ],
                    'keyEight' => [
                        [
                            'nesting.*' => [
                                'foo.*' => [
                                    'subfoo' => [
                                        [
                                            'testvalue' => [
                                                'subfoo' => 'bartested'
                                            ]
                                        ]
                                    ]
                                ]
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
        );

        $this->assertEquals(
            [
                [
                    'keyOne' => 'keyOne',
                    'keyTwo' => 'valueTwoDONE',
                    'keyThree' => 'TEST',
                    'keyFour' => 'keyFour',
                    'keyFive' => 'keyFive',
                    'keySix' => [
                        'multi' => 'arrayNESTED',
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
                        'nesting' => [
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

    /**
     * @return array
     */
    private function getSampleData(): array
    {
        $sample = [
            [
                'keyOne' => 'valueOne',
                'keyTwo' => 'valueTwo',
                'keyThree' => 'valueThree',
                'keyFour' => 'valueFour',
                'keyFive' => 'valueFive',
                'keySix' => [
                    'isNested' => [
                        'foo' => 'bar'
                    ],
                    'multi' => 'array'
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
                    'nesting' => [
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
        return $sample;
    }
}