<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business;


use Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface;
use Xervice\Core\Business\Model\Facade\AbstractFacade;

/**
 * @method \Xervice\ArrayHandler\Business\ArrayHandlerBusinessFactory getFactory()
 */
class ArrayHandlerFacade extends AbstractFacade
{
    /**
     * @param \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface $handlerPlugin
     * @param array $payload
     * @param array $config
     *
     * @return array
     */
    public function handleArray(FieldHandlerPluginInterface $handlerPlugin, array $payload, array $config): array
    {
        return $this->getFactory()
            ->createArrayHandler($handlerPlugin)
            ->handleArray($payload, $config);
    }
}