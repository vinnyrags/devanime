<?php

namespace DevAnime\Factory;

use DevAnime\Model\Post\PostBase;
use DevAnime\Model\Post\PostGeneric;

/**
 * Class PostFactory
 * @package DevAnime\Factory
 */
class PostFactory
{
    private const BASE_CLASS = PostBase::class;
    private const DEFAULT_CLASS = PostGeneric::class;

    private static array $models = [];

    public static function create($post = null): PostBase
    {
        $post = get_post($post);
        $modelClass = static::$models[$post->post_type] ?? static::DEFAULT_CLASS;
        return new $modelClass($post);
    }

    public static function registerPostModel(string $modelClass): void
    {
        if (!is_subclass_of($modelClass, static::BASE_CLASS)) {
            throw new \InvalidArgumentException('Invalid post factory registration');
        }

        $postType = $modelClass::POST_TYPE;
        static::$models[$postType] = $modelClass;
    }
}
