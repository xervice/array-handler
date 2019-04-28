<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business;


use Xervice\ArrayHandler\Business\Model\ArrayHandler;
use Xervice\ArrayHandler\Business\Model\ArrayHandlerInterface;
use Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface;
use Xervice\Core\Business\Model\Factory\AbstractBusinessFactory;

/**
 * @method \Xervice\ArrayHandler\ArrayHandlerConfig getConfig()
 */
class ArrayHandlerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param \Xervice\ArrayHandler\Dependency\FieldHandlerPluginInterface $handlerPlugin
     *
     * @return \Xervice\ArrayHandler\Business\Model\ArrayHandlerInterface
     */
    public function createArrayHandler(FieldHandlerPluginInterface $handlerPlugin): ArrayHandlerInterface
    {
        return new ArrayHandler($handlerPlugin);
    }
}