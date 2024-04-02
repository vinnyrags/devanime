<?php

namespace DevAnime\Model\Page;

use DevAnime\Collection\ObjectCollection;

/**
 * Class PageCollection
 * @package DevAnime\Model\Page
 */
class PageCollection extends ObjectCollection
{
    protected static string $objectClassName = PagePost::class;

    protected function getObjectHash($item)
    {
        return md5($item->ID ?: serialize($item));
    }
}
