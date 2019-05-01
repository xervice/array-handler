ArrayHandler
=====================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xervice/array-handler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xervice/array-handler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/xervice/array-handler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xervice/array-handler/?branch=master)

Helper module to run a handler based on an configuration array for an array. Can be used to write array-hydrator, validator and mapper.

Installation
-----------------
```
composer require xervice/array-mapper
```

Using
-----------------
First you have to write your field handler plugin. That plugin implements the interface \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface.

```php
<?php
declare(strict_types=1);

namespace XerviceTest\ArrayHandler\Business\Helper;


use Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface;

class TestFieldHandler implements FieldHandlerPluginInterface
{
    /**
     * @param array $data
     * @param mixed $fieldName
     * @param string $config
     *
     * @return array
     */
    public function handleSimpleConfig(array $data, $fieldName, string $config): array
    {
        $data[$fieldName] = $config;

        return $data;
    }

    /**
     * @param array $data
     * @param mixed $fieldName
     * @param array $config
     *
     * @return array
     */
    public function handleNestedConfig(array $data, $fieldName, array $config): array
    {
        $data[$fieldName] = $config['testvalue'] ?? null;

        return $data;
    }

    /**
     * @param array $data
     * @param array $config
     *
     * @return array
     */
    public function handleArrayConfig(array $data, array $config): array
    {
        $data = $config['testvalue'] ?? null;

        return $data;
    }


    /**
     * @param array $data
     * @param mixed $fieldName
     * @param callable $config
     *
     * @return array
     */
    public function handleCallableConfig(array $data, $fieldName, callable $config): array
    {
        $data[$fieldName] = $config($data[$fieldName]);

        return $data;
    }

}
```

You can use the array mapper via the facade with the handleArray method.
```
$arrayMapperFacade->handleArray(
    new TestFieldHandler(),
    $payLoadArray,
    $configArray
);
```


The config array is an array which can contain a simple value or an array. Inside of an array it also can be a simple value or an array or a callable function. Based on that three types on of the method in your FieldMapper will be called.
For the FieldName you can use a direct way with the fieldname for single dimensional arrays, or the keychain seperated by a point. Also you can configure all items inside of an array by using a wildcard .*.

***Example based on the field handler above***
```php
$data = [
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
              'testvalue' => 'TEST'
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
              FieldHandlerPluginInterface::HANDLE_THIS => [
                  'testvalue' => [
                      'data' => 'tested!'
                  ]
              ]
          ],
          'keyEight' => [
              [
                  'nesting.*' => [
                      [
                          'foo.*' => [
                              FieldHandlerPluginInterface::HANDLE_THIS => [
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

$result = [
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
];
```