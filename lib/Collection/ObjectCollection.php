<?php

namespace DevAnime\Collection;

use ArrayIterator;
use InvalidArgumentException;

/**
 * Class ObjectCollection
 * @package DevAnime\Collection
 */
abstract class ObjectCollection extends ImmutableCollection
{
    protected array $items = [];
    protected array $itemsHashmap = [];
    protected static string $objectClassName = '';

    public function __construct(array $items = [])
    {
        if (!empty($this->objectClassName)) {
            static::$objectClassName = $this->objectClassName;
        }
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function add($item): self
    {
        return $this->addItem($item);
    }

    public function replace($offset, $item): self
    {
        return $this->replaceItem($offset, $item);
    }

    public function remove($item): self
    {
        return $this->removeItem($item);
    }

    public function find($id)
    {
        $hash = $this->getHashFromId($id);
        return isset($this->itemsHashmap[$hash]) ? $this->items[$this->itemsHashmap[$hash]] : false;
    }

    public function walk(callable $callback): self
    {
        array_map($callback, $this->items);
        return $this;
    }

    public function walkMethod(string $method_name, ...$method_args): self
    {
        $this->mapMethod($method_name, ...$method_args);
        return $this;
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    public function mapMethod(string $method_name, ...$method_args): array
    {
        return $this->map(fn($item) => $this->callMethodChain($item, $method_name, $method_args));
    }

    public function filter(callable $callback): self
    {
        $this->items = array_filter($this->items, $callback);
        return $this;
    }

    public function filterMethod(string $method_name, ...$method_args): self
    {
        $this->items = array_filter($this->items, fn($item) => $this->callMethodChain($item, $method_name, $method_args));
        return $this;
    }

    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function reduceMethod(string $method_name, ...$args)
    {
        $initial = array_shift($args);
        return $this->reduce(fn($carry, $item) => $this->callMethodChain($item, $method_name, $args), $initial);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getAll());
    }

    public function getAll(): array
    {
        return $this->items;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function hasItems(): bool
    {
        return !$this->isEmpty();
    }

    public function first()
    {
        return reset($this->items) ?: null;
    }

    public function last()
    {
        return end($this->items) ?: null;
    }

    protected function addItem($item): self
    {
        $this->validateClass($item);
        $hash = $this->getObjectHash($item);
        if (!$this->hashExists($hash)) {
            $this->items[] = $item;
            $this->itemsHashmap[$hash] = key($this->items);
        }
        return $this;
    }

    protected function replaceItem($offset, $item): self
    {
        $this->validateClass($item);
        $hash = $this->getObjectHash($item);
        if (!$this->hashExists($hash)) {
            $this->items[$offset] = $item;
            $this->itemsHashmap[$hash] = $offset;
        }
        return $this;
    }

    protected function removeItem($item): self
    {
        $this->validateClass($item);
        $index = $this->getHashIndex($item);
        if (false !== $index) {
            unset($this->itemsHashmap[$this->getObjectHash($item)]);
            unset($this->items[$index]);
        }
        return $this;
    }

    protected function getHashFromId($id): string
    {
        return md5($id);
    }

    protected function validateClass($item): void
    {
        if (!($item instanceof static::$objectClassName)) {
            throw new InvalidArgumentException('Object passed to collection is not a ' . static::$objectClassName);
        }
    }

    protected function hashExists($hash): bool
    {
        return isset($this->itemsHashmap[$hash]);
    }

    protected function getHashIndex($item): ?int
    {
        $hash = $this->getObjectHash($item);
        return $this->itemsHashmap[$hash] ?? null;
    }

    protected function callMethodChain($item, $method_chain, $method_args)
    {
        $chain = array_reverse(explode('.', $method_chain));
        while (!empty($chain)) {
            $method_name = array_pop($chain);
            $item = call_user_func_array([$item, $method_name], count($chain) ? [] : $method_args);
        }
        return $item;
    }

    public function __invoke(): self
    {
        return clone $this;
    }

    abstract protected function getObjectHash($item);
}
