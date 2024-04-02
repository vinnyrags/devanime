<?php

namespace DevAnime\Vendor\VisualComposer\Support;

/**
 * Class ChildContainer
 * @package DevAnime\Vendor\VisualComposer\Support
 */
class ChildContainer extends ComponentContainer
{
    protected ?string $parent = null;
    protected static bool $serialize = true;

    protected function setupConfig(): void
    {
        parent::setupConfig();
        if ($this->parent !== null) {
            $this->componentConfig['as_child'] = ['only' => $this->parent];
        }
    }
}
