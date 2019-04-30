<?php namespace XerviceTest\ArrayHandler\Business\Model\ArrayLocator;

use Codeception\Test\Unit;
use Xervice\ArrayHandler\Business\Model\ArrayLocator\ArrayLocator;

class ArrayLocatorTest extends Unit
{

    /**
     * @group Xervice
     * @group ArrayHandler
     * @group Business
     * @group Model
     * @group ArrayLocator
     * @group Integration
     */
    public function testGetKeysByPath()
    {
        $array = [
            'foo' => 'bar',
            'nestedOne' => [
                [
                    'insideOne' => 'value',
                    'insideTwo' => [
                        'chained1' => 'bar',
                        'chained2' => 'bar'
                    ]
                ],
                [
                    'insideOne' => 'value2',
                    'insideTwo' => [
                        'chained3' => 'bar',
                        'chained4' => 'bar'
                    ]
                ],
                [
                    'insideOne' => 'value3',
                    'insideTwo' => [
                        'chained5' => 'bar',
                        'chained6' => 'bar'
                    ]
                ]
            ]
        ];

        $arrayLocator = new ArrayLocator();
        $arrayLocator->init($array);

        $this->assertEquals(
            [
                'nestedOne.0.insideTwo.chained1',
                'nestedOne.0.insideTwo.chained2',
                'nestedOne.1.insideTwo.chained3',
                'nestedOne.1.insideTwo.chained4',
                'nestedOne.2.insideTwo.chained5',
                'nestedOne.2.insideTwo.chained6'
            ],
            $arrayLocator->getKeysByPath('nestedOne.*.insideTwo.*')
        );

        $this->assertEquals(
            [
                'nestedOne.0',
                'nestedOne.1',
                'nestedOne.2',
            ],
            $arrayLocator->getKeysByPath('nestedOne.*')
        );
    }
}