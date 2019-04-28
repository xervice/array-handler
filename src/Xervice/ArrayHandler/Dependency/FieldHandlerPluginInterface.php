<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Dependency;


interface FieldHandlerPluginInterface
{
    /**
     * @param array $data
     * @param string $fieldName
     * @param string $config
     *
     * @return array
     */
    public function handleSimpleConfig(array $data, string $fieldName, string $config): array;

    /**
     * @param array $data
     * @param string $fieldName
     * @param array $config
     *
     * @return array
     */
    public function handleNestedConfig(array $data, string $fieldName, array $config): array;

    /**
     * @param array $data
     * @param string $fieldName
     * @param callable $config
     *
     * @return array
     */
    public function handleCallableConfig(array $data, string $fieldName, callable $config): array;
}