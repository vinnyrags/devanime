<?php

namespace DevAnime\Model\Post;

use DevAnime\Collection\ObjectCollection;

/**
 * Class PostCollection
 * @package DevAnime\Model\Post
 */
class PostCollection extends ObjectCollection
{
    protected static $object_class_name = PostBase::class;

    protected function getObjectHash($item)
    {
        return md5($item->ID ?: serialize($item));
    }
}
