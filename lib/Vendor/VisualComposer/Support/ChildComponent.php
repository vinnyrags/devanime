<?php

namespace DevAnime\Vendor\VisualComposer\Support;

/**
 * Class ChildComponent
 * @package DevAnime\Vendor\VisualComposer\Support
 */
class ChildComponent extends Component
{
    protected ?string $parent = null;
    protected static bool $serialize = true;

    protected function setupConfig(): void
    {
        parent::setupConfig();
        if ($this->parent !== null) {
            $this->componetConfig['as_child'] = ['only' => $this->parent];
            add_filter('visual_composer/children/' . $this->parent, fn($children) => [...$children, static::TAG]);
        }
    }
}
