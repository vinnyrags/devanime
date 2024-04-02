<?php

namespace DevAnime\Support;

/**
 * Trait Singleton
 * @package DevAnime\Support
 */
trait Singleton
{
    private static array $instances = [];

    final public function __construct()
    {
    }

    public function __clone()
    {
    }

    public function __sleep()
    {
    }

    public function __wakeup()
    {
    }

    final public static function getInstance(): static
    {
        return static::$instances[static::class] ??= new static();
    }
}
