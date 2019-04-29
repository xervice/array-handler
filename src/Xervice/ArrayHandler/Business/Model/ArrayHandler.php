<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model;


use Xervice\ArrayHandler\Business\Model\ArrayLocator\ArrayLocatorInterface;
use Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface;

class ArrayHandler implements ArrayHandlerInterface
{
    /**
     * @var \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface
     */
    private $fieldHandler;

    /**
     * @var \Xervice\ArrayHandler\Business\Model\ArrayLocator\ArrayLocatorInterface
     */
    private $arrayLocator;

    /**
     * ArrayHandler constructor.
     *
     * @param \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface $fieldHandler
     * @param \Xervice\ArrayHandler\Business\Model\ArrayLocator\ArrayLocatorInterface $arrayLocator
     */
    public function __construct(
        FieldHandlerPluginInterface $fieldHandler,
        ArrayLocatorInterface $arrayLocator
    ) {
        $this->fieldHandler = $fieldHandler;
        $this->arrayLocator = $arrayLocator;
    }

    /**
     * @param array $payload
     * @param array $config
     *
     * @return array
     */
    public function handleArray(array $payload, array $config): array
    {
        $this->arrayLocator->init($payload);

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
     * @param array $payload
     * @param array $config
     *
     * @return array
     */
    protected function validateNested(array $payload, array $config): array
    {
        foreach ($config as $configKeys => $configValue) {
            $keys = $this->arrayLocator->getKeysByPath($configKeys);
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
}