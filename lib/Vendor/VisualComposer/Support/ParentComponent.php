<?php

namespace DevAnime\Vendor\VisualComposer\Support;

/**
 * Class ParentComponent
 * @package DevAnime\Vendor\VisualComposer\Support
 */
class ParentComponent extends ComponentContainer
{
    protected int $initPriority = 20;
    protected array $children = [];

    protected function setupConfig(): void
    {
        parent::setupConfig();
        $children = apply_filters('visual_composer/children/' . static::TAG, $this->children);
        $this->componentConfig['as_parent'] = ['only' => implode(',', $children)];
    }
}
