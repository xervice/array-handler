<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model\ArrayLocator;

interface ArrayLocatorInterface
{
    /**
     * @param string $path
     *
     * @return array
     */
    public function getKeysByPath(string $path);

    /**
     * ArrayLocator constructor.
     *
     * @param array $array
     */
    public function init(array $array);
}