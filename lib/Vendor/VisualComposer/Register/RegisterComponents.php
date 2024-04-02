<?php

namespace DevAnime\Vendor\VisualComposer\Register;

use DevAnime\Vendor\VisualComposer\Components;

/**
 * Class RegisterComponents
 * @package DevAnime\Vendor\VisualComposer\Register
 */
class RegisterComponents
{
    private array $components = [
        Components\VcSection::class,
        Components\VcRow::class,
        Components\VcColumn::class,
        Components\VcColumnText::class,
        Components\VcSingleImage::class,
        Components\Tabs::class,
        Components\Tab::class,
        Components\Slide::class,
        Components\Slider::class,
        Components\Accordion::class,
        Components\AccordionPanel::class,
//        Components\Video::class,
        Components\SubNav::class,
        Components\Blockquote::class,
        Components\Button::class,
        Components\Heading::class,
        Components\Modal::class,
//        Components\ResponsiveSpacer::class
    ];

    public function __construct()
    {
        $this->registerComponents();
    }

    private function registerComponents(): void
    {
        foreach ($this->components as $componentClass) {
            if (class_exists($componentClass)) {
                $component = new $componentClass();
                if (method_exists($component, 'register')) {
                    $component->register();
                }
            }
        }
    }
}
