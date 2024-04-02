<?php

namespace DevAnime\Model\Post;

/**
 * Class PostGeneric
 * @package DevAnime\Model\Post
 */
class PostGeneric extends PostBase
{
    protected function isValidPostInit(): bool
    {
        return true;
    }
}
