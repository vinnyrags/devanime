<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\ChildComponent;
use DevAnime\Vendor\VisualComposer\Views\TabView;

/**
 * Class Tab
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Tab extends ChildComponent
{
    const NAME = 'Tab';
    const TAG = 'tab';
    const VIEW = TabView::class;
    protected ?string $parent = 'tabs';

    protected array $componentConfig = [
        'description' => 'Create a tab.',
        'icon' => 'icon-wpb-toggle-small-expand',
        'wrapper_class' => 'clearfix',
        'is_container' => false,
        'category' => 'Content',
        'params' => [
            [
                'type' => 'textfield',
                'heading' => 'Title',
                'param_name' => 'title',
                'value' => '',
                'group' => 'Content',
                'description' => 'Set the tab title.',
                'admin_label' => true
            ],
            [
                'type' => 'textfield',
                'heading' => 'Url',
                'param_name' => 'url',
                'value' => '',
                'description' => 'Set the tab url.',
                'admin_label' => true,
                'group' => 'Content'
            ]
        ]
    ];
}
