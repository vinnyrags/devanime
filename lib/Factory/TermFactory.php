<?php

namespace DevAnime\Factory;

use DevAnime\Model\Term\TermBase;
use DevAnime\Model\Term\TermGeneric;

/**
 * Class TermFactory
 * @package DevAnime\Factory
 */
class TermFactory
{
    private static array $models = [];

    public function create($term = null, $taxonomy = null)
    {
        if ($taxonomy) {
            $term = get_term($term, $taxonomy);
        }
        $modelClass = static::$models[$term->taxonomy] ?? TermGeneric::class;
        return new $modelClass($term);
    }

    public static function registerTermModel(string $modelClass): void
    {
        if (!is_subclass_of($modelClass, TermBase::class)) {
            throw new \InvalidArgumentException('Invalid term factory registration');
        }

        $taxonomy = $modelClass::TAXONOMY;
        static::$models[$taxonomy] = $modelClass;
    }
}
