<?php

namespace DevAnime\Vendor\VisualComposer\Support;

use WPBakeryShortCodesContainer;

/**
 * Class ComponentContainer
 * @package DevAnime\Vendor\VisualComposer\Support
 */
class ComponentContainer extends WPBakeryShortCodesContainer implements RegistersComponentConfig
{
    protected const NAME = null;
    protected const TAG = null;

    use ComponentRegistrationTrait;

    public function __construct($settings = [])
    {
        $settings['base'] = static::TAG;
        parent::__construct($settings);
    }
}
