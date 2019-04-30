<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model;


use Xervice\ArrayHandler\Business\Exception\ArrayHandlerException;
use Xervice\ArrayHandler\Business\Model\ArrayLocator\ArrayLocatorInterface;
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
    public function handleArray(array $payload, array $config): array
    {
        foreach ($config as $key => $configItem) {
            if (is_string($key) && is_callable($configItem)) {
                $payload = $this->handleCallable($payload, $key, $configItem);
            } elseif (is_string($configItem)) {
                // TODO: ? => string
            } elseif (is_array($configItem)) {
                // TODO: ? => array
            } else {
                throw new ArrayHandlerException('Config data is invalid.');
            }
        }

        return $payload;
    }

    /**
     * Cases:
     * '*' => callable
     * 'string' => callable
     * 'string.*' => callable
     *
     * @param mixed $payload
     * @param string $key
     * @param callable $callable
     *
     * @return array
     */
    protected function handleCallable($payload, string $key, callable $callable): array
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
}