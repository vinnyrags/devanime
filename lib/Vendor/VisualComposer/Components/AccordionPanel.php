<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\ChildComponent;

/**
 * Class AccordionPanel
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class AccordionPanel extends ChildComponent
{
    const NAME = 'Accordion Panel';
    const TAG = 'accordion_panel';

    protected ?string $parent = 'accordion';

    protected array $componentConfig = [
        'description' => 'Accordion Panel',
        'icon' => 'icon-wpb-toggle-small-expand',
        'wrapper_class' => 'clearfix',
        'is_container' => false,
        'category' => 'Content',
        'params' => [
            [
                'type' => 'textfield',
                'heading' => 'Heading',
                'param_name' => 'heading',
                'value' => '',
                'group' => 'Content',
                'admin_label' => true
            ],
            [
                'type' => 'textarea_html',
                'heading' => 'Content',
                'param_name' => 'content',
                'value' => '',
                'group' => 'Content'
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Expanded by default?',
                'description' => 'If checked the accordion panel will be expanded on page load',
                'param_name' => 'expanded',
                'value' => [
                    'Yes' => 'yes',
                ],
                'group' => 'Content',
                'admin_label' => true
            ]
        ]
    ];
}
