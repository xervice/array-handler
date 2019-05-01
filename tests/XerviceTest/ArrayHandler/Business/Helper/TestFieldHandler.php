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