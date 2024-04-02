<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\Component;

/**
 * Class Button
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Button extends Component
{
    const NAME = 'Button';
    const TAG = 'button';

    protected static array $defaultStyles = [
        'Select Style' => '',
        'Primary' => 'primary',
        'Secondary' => 'secondary',
        'Inverted' => 'inverted'
    ];

    protected array $componentConfig = [
        'description' => 'Create a button link.',
        'icon' => 'icon-wpb-toggle-small-expand',
        'wrapper_class' => 'clearfix',
        'is_container' => false,
        'category' => 'Content',
        'params' => [
            [
                'type' => 'textarea_html',
                'heading' => 'Button Text',
                'param_name' => 'content',
                'description' => 'Enter link title.'
            ],
            [
                'type' => 'textfield',
                'heading' => 'Url',
                'param_name' => 'url',
                'description' => 'Enter link url.',
                'admin_label' => true
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Open in new tab?',
                'param_name' => 'target',
                'description' => 'Enter link target.',
                'value' => [
                    'Yes' => true,
                ],
                'admin_label' => true
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Alternative Button Style',
                'param_name' => 'style',
                'description' => 'Style button different from the default.',
                'value' => ''
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Full Width',
                'param_name' => 'full_width',
                'description' => 'Make the button fill the width of its container.'
            ],
            [
                'type' => 'textfield',
                'heading' => 'Extra class name',
                'param_name' => 'el_class',
                'description' => 'Style particular content element differently - add a class name and refer to it in custom CSS.',
            ]
        ]
    ];

    protected function setupConfig(): void
    {
        parent::setupConfig();
        $this->setStyles();
    }

    protected function setStyles(): void
    {
        $this->componentConfig['params'][3]['value'] = apply_filters('visual_composer/button_styles', static::$defaultStyles);
    }

}
