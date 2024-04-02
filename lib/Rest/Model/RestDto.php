<?php

namespace DevAnime\Rest\Model;

/**
 * Class RestDto
 * @package DevAnime\Rest\Model
 */
abstract class RestDto implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        $data = $this->getData();
        $keys = array_map('DevAnime\Util::toCamelCase', array_keys($data));
        return array_combine($keys, array_values($data));
    }

    public function getData(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }

    public function __get(string $name): mixed
    {
        return $this->$name ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }
}
