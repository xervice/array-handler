<?php
namespace XerviceTest\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Xervice\ArrayHandler\Business\ArrayHandlerFacade;
use Xervice\Core\Business\Model\Locator\Locator;

class XerviceTest extends \Codeception\Module
{
    /**
     * @return \Xervice\ArrayHandler\Business\ArrayHandlerFacade
     */
    public function getFacade(): ArrayHandlerFacade
    {
        return Locator::getInstance()->arrayHandler()->facade();
    }
}
