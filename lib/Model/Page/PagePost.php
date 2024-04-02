<?php

namespace DevAnime\Model\Page;

use DevAnime\Model\Post\PostBase;

/**
 * Class PagePost
 * @package DevAnime\Model\Page
 */
class PagePost extends PostBase
{
    public const POST_TYPE = 'page';

    public function parent(): ?PagePost
    {
        $parentId = $this->post()->post_parent;
        return $parentId ? static::create($parentId) : null;
    }

    public function template(): string
    {
        return get_page_template_slug($this->ID) ?? 'page.php';
    }
}
