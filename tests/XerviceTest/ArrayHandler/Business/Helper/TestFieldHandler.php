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