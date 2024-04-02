<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\ParentComponent;

/**
 * Class Tabs
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Tabs extends ParentComponent
{
    const NAME = 'Tabs';
    const TAG = 'tabs';

    protected array $componentConfig = [
        'description' => 'Create a set of tabs.',
        'show_settings_on_create' => true,
        'is_container' => true,
        'content_element' => true,
        'js_view' => 'VcColumnView',
        'category' => 'Structure',
        'params' => [
            'format' => [
                'type' => 'dropdown',
                'heading' => 'Format',
                'param_name' => 'format',
                'value' => [
                    'Buttons' => 'buttons',
                    'Text' => 'text'
                ]
            ],
            'style' => [
                'type' => 'dropdown',
                'heading' => 'Style',
                'param_name' => 'style',
                'value' => [
                    'Default' => '',
                    'Secondary' => 'secondary',
                    'Inverted' => 'inverted'
                ]
            ],
            'layout' => [
                'type' => 'dropdown',
                'heading' => 'Layout',
                'param_name' => 'layout',
                'value' => [
                    'Horizontal' => 'horizontal',
                    'Dropdown on Mobile' => 'default',
                    'Dropdown Only' => 'dropdown'
                ]
            ]
        ]
    ];
}
