<?php

namespace DevAnime\Collection;

/**
 * class ImmutableCollection
 * @package DevAnime\Collection
 */
abstract class ImmutableCollection implements Collection
{
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('Value setting is not allowed for an Immutable Collection');
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('Value un-setting is not allowed for an Immutable Collection');
    }
}