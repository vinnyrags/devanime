<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\ParentComponent;

/**
 * Class Slider
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Slider extends ParentComponent
{
    const NAME = 'Slider';
    const TAG = 'slider';
    protected array $children = ['slide'];
    protected array $componentConfig = [
        'description' => 'Slider Container',
        'is_container' => true,
        'content_element' => true,
        'show_settings_on_create' => false,
        'icon' => 'icon-wpb-ui-pageable',
        'class' => 'slider-container',
        'js_view' => 'VcColumnView',
        'category' => 'Structure',
        'params' => [
            [
                'type' => 'checkbox',
                'heading' => 'Slider Config',
                'description' => '',
                'param_name' => 'slider_config',
                'value' => [
                    'Loop' => 'loop',
                    'Autoplay' => 'autoplay',
                    'Dots Navigation' => 'dots'

                ],

                'admin_label' => false
            ],
            [
                'type' => 'textfield',
                'heading' => 'Autoplay Speed',
                'description' => 'Number of seconds to display each slide.',
                'param_name' => 'autoplay',
                'value' => 6,
                'admin_label' => false,
                'dependency' => [
                    'element' => 'slider_config',
                    'value' => [
                        'autoplay'
                    ]
                ],
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Slide Height',
                'description' => '',
                'param_name' => 'height',
                'value' => [
                    'Default' => 0,
                    'Match Height' => 'match',
                    'Full Height' => 'full',
                ],

                'admin_label' => false
            ],
            [
                'type' => 'textfield',
                'heading' => 'Extra class name',
                'param_name' => 'el_class'
            ],
            [
                'type' => 'textarea_raw_html',
                'heading' => 'Custom Config',
                'description' => 'Developer settings from <a href="https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html" target="_blank">Owl Carousel</a>. Must be valid JSON.',
                'param_name' => 'owl_config'
            ]
        ]
    ];
}
