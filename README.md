ArrayHandler
=====================

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
     * @param string $fieldName
     * @param string $config
     *
     * @return array
     */
    public function handleSimpleConfig(array $data, string $fieldName, string $config): array
    {
        $data[$fieldName] = $config;

        return $data;
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @param array $config
     *
     * @return array
     */
    public function handleNestedConfig(array $data, string $fieldName, array $config): array
    {
        $data[$fieldName] = $config['testvalue'];

        return $data;
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @param callable $config
     *
     * @return array
     */
    public function handleCallableConfig(array $data, string $fieldName, callable $config): array
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

```php


```