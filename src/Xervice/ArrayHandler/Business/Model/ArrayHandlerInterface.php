<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model;

interface ArrayHandlerInterface
{
    /**
     * @param array $payload
     * @param array $config
     *
     * @return array
     */
    public function handleArray(array $payload, array $config): array;
}