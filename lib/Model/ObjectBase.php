<?php

namespace DevAnime\Models;

use DevAnime\Support\Util;

/**
 * Class ObjectBase
 * @package DevAnime\Model
 */
abstract class ObjectBase
{
    public function __construct(array $properties = [])
    {
        $this->setProperties($properties);
    }

    public function setProperties(array $properties): void
    {
        foreach ($properties as $key => $value) {
            if ($this->hasProperty($key)) {
                $this->$key = $value;
            }
        }
    }

    public function __get(string $name): mixed
    {
        $accessorMethod = 'get' . Util::toPascalCase($name);
        if (method_exists($this, $accessorMethod)) {
            return $this->$accessorMethod();
        }
        return null;
    }

    public function __set(string $name, $value): void
    {
        $mutatorMethod = 'set' . Util::toPascalCase($name);
        if (method_exists($this, $mutatorMethod)) {
            $this->$mutatorMethod($value);
        }
    }

    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }

    protected function hasProperty(string $key): bool
    {
        return property_exists($this, $key);
    }
}
