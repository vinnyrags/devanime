<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\BackgroundImageTrait;
use DevAnime\Vendor\VisualComposer\Support\ChildContainer;

/**
 * Class Slide
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Slide extends ChildContainer
{
    const NAME = 'Slide';
    const TAG = 'slide';
    protected ?string $parent = 'slider';
    protected array $componentConfig = [
        'description' => 'Slide Container',
        'is_container' => true,
        'content_element' => true,
        'show_settings_on_create' => false,
        'js_view' => 'VcColumnView',
        'icon' => 'icon-slide',
        'class' => 'slide-container',
        'category' => 'Structure',
        'params' => [],
    ];

    use BackgroundImageTrait;

    protected function setupConfig(): void
    {
        parent::setupConfig();
        $this->componentConfig['params'] = $this->appendBackgroundImageConfig($this->componentConfig['params']);
        $this->applyBackgroundColorFilter();
    }
}
