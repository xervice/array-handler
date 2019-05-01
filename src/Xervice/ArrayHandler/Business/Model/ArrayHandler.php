<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model;


use Xervice\ArrayHandler\Business\Exception\ArrayHandlerException;
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
    public function __construct(
        FieldHandlerPluginInterface $fieldHandler
    ) {
        $this->fieldHandler = $fieldHandler;
    }

    /**
     * Cases:
     * '*' => callable
     * '*' => [ [...] ]
     * '*' => [ ... => [], ...]
     * int => 'string',
     * 'string' => 'string',
     * 'string' => [ [...] ]
     * 'string' => [ ... => [], ... ]
     * 'string' => callable
     * 'string.*' => [ [...] ]
     * 'string.*' => [ ... => [], ... ]
     * 'string.*' => callable
     *
     * @param array $payload
     * @param array $config
     *
     * @return array
     * @throws \Xervice\ArrayHandler\Business\Exception\ArrayHandlerException
     */
    public function handleConfig(array $payload, array $config): array
    {
        foreach ($config as $key => $configItem) {
            $payload = $this->handleByType($payload, $key, $configItem);
        }

        return $payload;
    }

    /**
     * @param array $payload
     * @param mixed $key
     * @param mixed $config
     *
     * @return array
     * @throws \Xervice\ArrayHandler\Business\Exception\ArrayHandlerException
     */
    protected function handleByType(array $payload, $key, $config): array
    {
        if (is_string($key) && is_callable($config)) {
            $payload = $this->handleCallable($payload, $key, $config);
        } elseif (is_string($config)) {
            $payload = $this->handleString($payload, $key, $config);
        } elseif (is_array($config)) {
            $payload = $this->handleArray($payload, $key, $config);
        } else {
            throw new ArrayHandlerException('Config data is invalid.');
        }

        return $payload;
    }

    /**
     * Cases:
     * '*' => [ [...] ]
     * '*' => [ ... => [], ...]
     * 'string' => [ [...] ]
     * 'string' => [ ... => [], ... ]
     * 'string.*' => [ [...] ]
     * 'string.*' => [ ... => [], ... ]
     * int => []
     *
     * @param array $payload
     * @param mixed $key
     * @param array $config
     *
     * @return array
     */
    protected function handleArray(array $payload, $key, array $config): array
    {
        if ($key === '*') {
            $payload = $this->handleArrayWildcard($payload, $config);
        } elseif (is_string($key) && strpos($key, '.*') !== false) {
            $payload = $this->handleArraySubwildcard($payload, $key, $config);
        } elseif (is_int($key) && is_array($config)) {
            $payload = $this->handleConfig($payload, $config);
        } else {
            $payload = $this->handleArrayConfig($payload, $key, $config);
        }

        return $payload;
    }

    /**
     * Cases:
     * int => 'string',
     * 'string' => 'string',
     *
     * @param array $payload
     * @param mixed $key
     * @param string $configItem
     *
     * @return array
     */
    protected function handleString(array $payload, $key, string $configItem): array
    {
        if (is_int($key)) {
            $key = $configItem;
        }

        return $this->fieldHandler->handleSimpleConfig($payload, $key, $configItem);
    }

    /**
     * Cases:
     * '*' => callable
     * 'string' => callable
     * 'string.*' => callable
     *
     * @param mixed $payload
     * @param $key
     * @param callable $callable
     *
     * @return array
     */
    protected function handleCallable($payload, $key, callable $callable): array
    {
        if ($key === '*') {
            foreach ($payload as $pkey => $pdata) {
                $payload = $this->handleCallable($payload, (string) $pkey, $callable);
            }
        } elseif (strpos($key, '.*') !== false) {
            $primary = substr($key, 0, strpos($key, '.*'));
            $payload[$primary] = $this->handleCallable($payload[$primary], '*', $callable);
        } else {
            $payload = $this->fieldHandler->handleCallableConfig($payload, $key, $callable);
        }

        return $payload;
    }

    /**
     * @param array $payload
     * @param array $config
     *
     * @return array
     * @throws \Xervice\ArrayHandler\Business\Exception\ArrayHandlerException
     */
    protected function handleArrayWildcard(array $payload, array $config): array
    {
        foreach ($payload as $pkey => $pdata) {
            $payload[$pkey] = $this->handleConfig($payload[$pkey], $config);
        }
        return $payload;
}

    /**
     * @param array $payload
     * @param $key
     * @param array $config
     *
     * @return array
     * @throws \Xervice\ArrayHandler\Business\Exception\ArrayHandlerException
     */
    protected function handleArraySubwildcard(array $payload, $key, array $config): array
    {
        $primary = substr($key, 0, strpos($key, '.*'));
        foreach ($payload[$primary] as $pkey => $pdata) {
            $payload[$primary][$pkey] = $this->handleConfig($pdata, $config);
        }
        return $payload;
}

    /**
     * @param array $payload
     * @param $key
     * @param array $config
     *
     * @return array
     * @throws \Xervice\ArrayHandler\Business\Exception\ArrayHandlerException
     */
    protected function handleArrayConfig(array $payload, $key, array $config): array
    {
        if ($key === FieldHandlerPluginInterface::HANDLE_THIS) {
            $payload = $this->fieldHandler->handleArrayConfig($payload, $config);
        } elseif (isset($payload[$key]) && !is_array($payload[$key])) {
            $payload = $this->fieldHandler->handleNestedConfig($payload, $key, $config);
        } else {
            $payload[$key] = $this->handleConfig($payload[$key], $config);
        }
        return $payload;
}
}