<?php
declare(strict_types=1);

namespace Xervice\ArrayHandler\Business\Model\ArrayLocator;


class ArrayLocator implements ArrayLocatorInterface
{
    /**
     * @var array
     */
    private $array;

    /**
     * @var array
     */
    private $fieldMap;

    /**
     * ArrayLocator constructor.
     */
    public function __construct()
    {
        $this->array = [];
        $this->fieldMap = [];
    }


    /**
     * ArrayLocator constructor.
     *
     * @param array $array
     */
    public function init(array $array)
    {
        $this->array = $array;
        $this->fieldMap = [];
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public function getKeysByPath(string $path)
    {
        if ($this->fieldMap === null) {
            $this->buildFieldMap();
        }

        return $this->getAllKeys($this->fieldMap, $path);
    }

    /**
     * @param $array
     * @param $path
     *
     * @return array
     */
    protected function getAllKeys($array, $path): array
    {
        $search = str_replace('\*', '.*?', preg_quote($path, '/'));
        return preg_grep('/^' . $search . '$/i', array_keys($array));
    }

    protected function buildFieldMap(): void
    {
        $this->fieldMap = $this->getAllFields($this->array, '', []);
    }

    /**
     * @param array $array
     * @param string $path
     * @param array $fieldMap
     *
     * @return array
     */
    protected function getAllFields(array $array, string $path, array $fieldMap): array
    {
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $fieldMap = $this->getAllFields($item, $path . '.' . $key, $fieldMap);
            }
            else {
                $keypath = (string) substr($path . '.' . $key, 1);
                $fieldMap[$keypath] = $keypath;
            }
        }

        return $fieldMap;
    }
}