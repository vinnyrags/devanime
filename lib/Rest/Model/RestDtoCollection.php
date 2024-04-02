<?php

namespace DevAnime\Rest\Model;

use DevAnime\Collection\ObjectCollection;

/**
 * Class RestDtoCollection
 * @package DevAnime\Rest\Model
 */
abstract class RestDtoCollection extends ObjectCollection implements \JsonSerializable
{
    protected static string $objectClassName = RestDto::class;

    public function __construct(iterable $items = [])
    {
        $dtoItems = [];
        foreach ($items as $item) {
            if ($dto = $this->getDataTransferObject($item)) {
                $dtoItems[] = $dto;
            }
        }
        parent::__construct($dtoItems);
    }

    public function jsonSerialize(): array
    {
        return $this->getAll();
    }

    public function getItems(): array
    {
        return $this->getAll();
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }

    protected function getDataTransferObject($item): RestDto
    {
        if (!($item instanceof RestDto)) {
            $item = $this->createDataTransferObject($item);
        }
        return $item;
    }

    protected function getObjectHash($item): string
    {
        return md5(serialize($item));
    }

    /**
     * @param object $item
     * @return RestDto
     */
    abstract protected function createDataTransferObject($item): RestDto;
}
