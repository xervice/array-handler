<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model;


use Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface;

class ArrayHandler implements ArrayHandlerInterface
{
    /**
     * @var \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface
     */
    private $fieldHandler;

    /**
     * ArrayHandler constructor.
     *
     * @param \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface $fieldHandler
     */
    public function __construct(FieldHandlerPluginInterface $fieldHandler)
    {
        $this->fieldHandler = $fieldHandler;
    }

    /**
     * @param array $payload
     * @param array $config
     *
     * @return array
     */
    public function handleArray(array $payload, array $config): array
    {
        foreach ($config as $key => $configItem) {
            if (is_string($configItem)) {
                $payload = $this->validateField($payload, $configItem, $configItem);
            } elseif ($key === '*') {
                foreach ($payload as $dataKey => $subdata) {
                    $payload[$dataKey] = $this->handleArray($subdata, $configItem);
                }
            } else {
                $payload = $this->validateNested($payload, $configItem);
            }
        }

        return $payload;
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @param mixed $config
     *
     * @return array
     */
    protected function validateField(array $data, string $fieldName, $config): array
    {
        if (is_string($config)) {
            $data = $this->fieldHandler->handleSimpleConfig($data, $fieldName, $config);
        } elseif (is_array($config)) {
            $data = $this->fieldHandler->handleNestedConfig($data, $fieldName, $config);
        } elseif (is_callable($config)) {
            $data = $this->fieldHandler->handleCallableConfig($data, $fieldName, $config);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param array $configs
     *
     * @return array
     */
    protected function validateNested(array $data, array $configs): array
    {
        foreach ($configs as $key => $fieldConfig) {
            $data = $this->validateByType($data, $key, $fieldConfig);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param mixed $key
     * @param mixed $fieldConfig
     *
     * @return array
     */
    protected function validateByType(array $data, $key, $fieldConfig): array
    {
        if (is_string($fieldConfig)) {
            $key = $fieldConfig;
        }

        if (strpos($key, '.*') !== false) {
            $data = $this->validateAllArrayFields($data, $key, $fieldConfig);
        } elseif (strpos($key, '.') !== false) {
            $data = $this->validateArrayKey($data, $key, $fieldConfig);
        } else {
            $data = $this->validateField($data, $key, $fieldConfig);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $key
     * @param mixed $fieldConfig
     *
     * @return array
     */
    protected function validateArrayKey(array $data, string $key, $fieldConfig): array
    {
        $keychain = explode('.', $key);
        $lastKey = $this->getLastArrayKey($keychain);

        $subdata = $this->getElementWithKey($data, $keychain);
        $subdata = $this->validateField($subdata, $keychain[$lastKey], $fieldConfig);

        return $this->setElementWithKey($data, $keychain, $subdata);
    }

    /**
     * @param array $data
     * @param string $key
     * @param mixed $fieldConfig
     *
     * @return array
     */
    protected function validateAllArrayFields(array $data, string $key, $fieldConfig): array
    {
        $keychain = explode('.', $key);
        $lastKey = $this->getLastArrayKey($keychain);

        $subdata = $this->getElementWithKey($data, $keychain);
        foreach ($subdata as $childkey => $childdata) {
            $subdata = $this->validateField($subdata, (string) $childkey, $fieldConfig);
        }

        return $this->setElementWithKey($data, $keychain, $subdata);
    }

    /**
     * @param array $data
     * @param array $keychain
     * @param $value
     *
     * @return array
     */
    private function setElementWithKey(array $data, array $keychain, $value): array
    {
        if (count($keychain) === 1) {
            return $value;
        }

        $key = array_shift($keychain);
        $data[$key] = $this->setElementWithKey($data[$key], $keychain, $value);

        return $data;
    }

    /**
     * @param array $data
     * @param array $keychain
     *
     * @return mixed
     */
    private function getElementWithKey(array $data, array $keychain)
    {
        if (count($keychain) === 1) {
            return $data;
        }

        $key = array_shift($keychain);
        return $this->getElementWithKey($data[$key], $keychain);
    }

    /**
     * @param array $array
     *
     * @return mixed
     */
    private function getLastArrayKey(array $array)
    {
        if (!function_exists("array_key_last")) {
            return array_keys($array)[count($array) - 1];
        }

        return array_key_last($array);
    }
}