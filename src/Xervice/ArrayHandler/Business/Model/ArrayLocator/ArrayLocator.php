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
        $this->fieldMap = null;
    }


    /**
     * ArrayLocator constructor.
     *
     * @param array $array
     */
    public function init(array $array)
    {
        $this->array = $array;
        $this->fieldMap = null;
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

        return array_values(
            $this->getAllKeys($this->fieldMap, $path)
        );
    }

    /**
     * @param $array
     * @param $path
     *
     * @return array
     */
    protected function getAllKeys($array, $path): array
    {
        $search = str_replace('*', '([^\.]*)', $path);
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
            $keypath = (string) substr($path . '.' . $key, 1);
            if (is_array($item)) {
                $fieldMap[$keypath] = $keypath;
                $fieldMap = $this->getAllFields($item, $path . '.' . $key, $fieldMap);
            }
            else {
                $fieldMap[$keypath] = $keypath;
            }
        }

        return $fieldMap;
    }
}