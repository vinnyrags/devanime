<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\BackgroundContainerTrait;
use DevAnime\Vendor\VisualComposer\Support\BackgroundImageTrait;
use DevAnime\Vendor\VisualComposer\Support\ComponentRegistrationTrait;
use DevAnime\Vendor\VisualComposer\Support\RegistersComponentConfig;
use WPBakeryShortCode;

require_once vc_path_dir('SHORTCODES_DIR', 'vc-column.php');

/**
 * Class VcColumn
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class VcColumn extends \WPBakeryShortCode_VC_Column implements RegistersComponentConfig
{
    const NAME = 'Column';
    const TAG = 'vc_column';

    use ComponentRegistrationTrait {
        setupConfig as private setupConfigBase;
    }
    use BackgroundImageTrait;
    use BackgroundContainerTrait;

    public function __construct(array $settings = [])
    {
        $settings['base'] = static::TAG;
        WPBakeryShortCode::__construct($settings); //avoids premature script registration in parent
        $this->componentConfig = [
            'description' => 'Place content elements inside the column.',
            'icon' => 'icon-wpb-row',
            'is_container' => true,
            'content_element' => false,
            'js_view' => 'VcColumnView',
            'params' => [
                [
                    'type' => 'el_id',
                    'heading' => 'Element ID',
                    'param_name' => 'el_id',
                    'description' => 'Enter element ID (Note: make sure it is unique and valid according to <a href="http://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a>.',
                ],
                [
                    'type' => 'textfield',
                    'heading' => 'Extra class name',
                    'param_name' => 'el_class',
                    'description' => 'Style particular content element differently - add a class name and refer to it in custom CSS.',
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Width',
                    'param_name' => 'width',
                    'value' => [
                        '1 column - 1/12' => '1/12',
                        '2 columns - 1/6' => '1/6',
                        '3 columns - 1/4' => '1/4',
                        '4 columns - 1/3' => '1/3',
                        '5 columns - 5/12' => '5/12',
                        '6 columns - 1/2' => '1/2',
                        '7 columns - 7/12' => '7/12',
                        '8 columns - 2/3' => '2/3',
                        '9 columns - 3/4' => '3/4',
                        '10 columns - 5/6' => '5/6',
                        '11 columns - 11/12' => '11/12',
                        '12 columns - 1/1' => '1/1',
                        '20% - 1/5' => '1/5',
                        '40% - 2/5' => '2/5',
                        '60% - 3/5' => '3/5',
                        '80% - 4/5' => '4/5'
                    ],
                    'group' => 'Responsive Options',
                    'description' => 'Select column width.',
                    'std' => '1/1',
                ],
                [
                    'type' => 'column_offset',
                    'heading' => 'Responsiveness',
                    'param_name' => 'offset',
                    'group' => 'Responsive Options',
                    'description' => 'Adjust column for different screen sizes. Control width, offset and visibility settings.',
                ],

                [
                    'type' => 'dropdown',
                    'heading' => 'Vertical Alignment',
                    'param_name' => 'alignment',
                    'value' => [
                        'Default (Top)' => '',
                        'Center' => 'align-self-center',
                        'Bottom' => 'align-self-end'
                    ],
                    'description' => 'Set the column alignment.',
                    'group' => 'Layout'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Horizontal Alignment',
                    'param_name' => 'halignment',
                    'value' => [
                        'Default (Left)' => '',
                        'Center' => 'text--center',
                        'Right' => 'text--right'
                    ],
                    'description' => 'Set the column alignment.',
                    'group' => 'Layout'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Remove Gutter',
                    'param_name' => 'no_gutter',
                    'description' => 'Remove gutters by breakpoint.',
                    'value' => [
                        'Mobile Left/Right' => 'no-gutter--xs',
                        'Desktop Left (md)' => 'no-gutter--md-left',
                        'Desktop Right (md)' => 'no-gutter--md-right',
                    ],
                    'group' => 'Layout'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Fill Gutter',
                    'param_name' => 'fill',
                    'description' => 'Background image or color will fill left and right gutters',
                    'value' => ['Fill gutter' => 'fill-gutter'],
                    'group' => 'Background'
                ],
            ]
        ];
        $this->componentConfig['params'] = $this->appendBackgroundImageConfig($this->componentConfig['params']);
        $this->componentConfig['params'] = $this->appendBackgroundContainerConfig($this->componentConfig['params']);
    }

    protected function setupConfig(): void
    {
        $this->setupConfigBase();
        $this->applyBackgroundColorFilter();
        if ($additional_options = apply_filters('visual_composer/column_additional_options', [])) {
            $this->componentConfig['params'][] = [
                'type' => 'checkbox',
                'param_name' => 'options',
                'heading' => 'Additional Options',
                'group' => 'Layout',
                'value' => $additional_options
            ];
        }
    }
}
