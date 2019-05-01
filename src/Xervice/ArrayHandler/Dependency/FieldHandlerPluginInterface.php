<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Dependency;


interface FieldHandlerPluginInterface
{
    public const HANDLE_THIS = '___HANDLE_THIS___';

    /**
     * @param array $data
     * @param mixed $fieldName
     * @param mixed $config
     *
     * @return array
     */
    public function handleSimpleConfig(array $data, $fieldName, $config): array;

    /**
     * @param array $data
     * @param mixed $fieldName
     * @param array $config
     *
     * @return array
     */
    public function handleNestedConfig(array $data, $fieldName, array $config): array;

    /**
     * @param array $data
     * @param array $config
     *
     * @return array
     */
    public function handleArrayConfig(array $data, array $config): array;

    /**
     * @param array $data
     * @param mixed $fieldName
     * @param callable $config
     *
     * @return array
     */
    public function handleCallableConfig(array $data, $fieldName, callable $config): array;
}