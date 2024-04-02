<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\Component;

/**
 * Class ModalComponent
 * @package Theme
 */
class Modal extends Component
{
    const NAME = 'Modal';
    const TAG = 'modal';

    protected array $componentConfig = [
        'description' => 'Create a static modal that can be referenced from a CTA.',
        'icon' => 'icon-wpb-toggle-small-expand',
        'wrapper_class' => 'clearfix',
        'is_container' => false,
        'category' => 'Content',
        'params' => [
            [
                'type' => 'textfield',
                'heading' => 'ID',
                'param_name' => 'id',
                'value' => '',
                'description' => 'Please omit the hashtag. This is the field that gets referenced from a CTA.',
                'admin_label' => true
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Types',
                'param_name' => 'type',
                'value' => [
                    '-- Select A Type --' => '',
                    'Box' => 'box',
                    'Centered' => 'centered'
                ],
                'description' => 'Select the type of modal.',
                'admin_label' => true
            ],
            [
                'type' => 'textarea_html',
                'heading' => 'Content',
                'param_name' => 'content',
                'value' => '',
                'description' => ''
            ]
        ]
    ];
}
