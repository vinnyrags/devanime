<?php

namespace DevAnime\Model\Field;

/**
 * Class FieldCollection
 * @package DevAnime\Model\Field
 */
abstract class FieldCollection implements Field
{
    abstract protected function getFields(): array;

    public function getValue(): array
    {
        $fields = $this->getFields();
        foreach ($fields as &$value) {
            if ($value instanceof Field) {
                $value = $value->getValue();
            }
        }
        return $fields;
    }
}
