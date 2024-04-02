<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\ParentComponent;

/**
 * Class Accordion
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Accordion extends ParentComponent
{
    const NAME = 'Accordion';
    const TAG = 'accordion';

    protected array $componentConfig = [
        'description' => 'Create accordion.',
        'is_container' => true,
        'content_element' => true,
        'js_view' => 'VcColumnView',
        'category' => 'Structure',
        'params' => [
            [
                'type' => 'dropdown',
                'heading' => 'Heading Level',
                'param_name' => 'heading_level',
                'value' => [
                    '-- Select Heading Level -- ' => '',
                    'H2' => 'h2',
                    'H3' => 'h3',
                    'H4' => 'h4',
                    'H5' => 'h5',
                ],
                'description' => 'Set the heading level of the panel headlines. Default H2.',
                'admin_label' => true
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Single View',
                'param_name' => 'single_open',
                'description' => 'Only one panel open at a time.'
            ]
        ]
    ];
}
