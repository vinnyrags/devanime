<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\BackgroundContainerTrait;
use DevAnime\Vendor\VisualComposer\Support\BackgroundImageTrait;
use DevAnime\Vendor\VisualComposer\Support\ComponentRegistrationTrait;
use DevAnime\Vendor\VisualComposer\Support\RegistersComponentConfig;
use WPBakeryShortCode;

require_once vc_path_dir('SHORTCODES_DIR', 'vc-row.php');

/**
 * Class VcRow
 * @package Theme\Components
 */
class VcRow extends \WPBakeryShortCode_VC_Row implements RegistersComponentConfig
{
    const NAME = 'Row';
    const TAG = 'vc_row';

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
            'description' => 'Place content elements inside the row.',
            'icon' => 'icon-wpb-row',
            'wrapper_class' => 'clearfix',
            'is_container' => true,
            'category' => 'Content',
            'show_settings_on_create' => false,
            'class' => 'vc_main-sortable-element',
            'js_view' => 'VcRowView',
            'params' => [
                [
                    'type' => 'el_id',
                    'heading' => 'Row ID',
                    'param_name' => 'el_id',
                    'description' => 'Enter row ID (Note: make sure it is unique and valid according to <a href="http://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a>',
                    'group' => 'General'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Use Row ID as Tab ID',
                    'param_name' => 'tab',
                    'value' => '',
                    'description' => 'Removes default html attribute and anchor behavior',
                    'group' => 'General'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Default Tab',
                    'param_name' => 'default_tab',
                    'value' => '',
                    'description' => 'Set this row to be the default tab.',
                    'group' => 'General'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Disable row',
                    'param_name' => 'disable_element',
                    'description' => 'If checked the row won\'t be visible on the public side of your website. You can switch it back any time.',
                    'value' => ['Yes' => 'yes'],
                    'group' => 'General'
                ],
                [
                    'type' => 'textfield',
                    'heading' => 'Extra class name',
                    'param_name' => 'el_class',
                    'description' => 'Style particular content element differently - add a class name and refer to it in custom CSS.',
                    'group' => 'General'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Bottom Margin',
                    'param_name' => 'bottom_margin',
                    'value' => [
                        'Default' => '',
                        'Double' => 'mb-double',
                        'Half' => 'mb-half',
                        'None' => 'mb-none'
                    ],
                    'description' => 'Set the margin space below the row.',
                    'group' => 'Layout'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Vertical Alignment',
                    'param_name' => 'valignment',
                    'value' => [
                        'Default (Top)' => '',
                        'Center' => 'align-items-center',
                        'Bottom' => 'align-items-end',
                        'Match Content Height' => 'match-content-height'
                    ],
                    'description' => 'Set the column alignment.',
                    'group' => 'Layout'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Horizontal Column Alignment',
                    'param_name' => 'halignment',
                    'value' => [
                        'Default (Left)' => '',
                        'Center' => 'justify-content-center',
                        'Right' => 'justify-content-end',
                        'Distribute' => 'justify-content-between'
                    ],
                    'description' => 'Set the column alignment.',
                    'group' => 'Layout'
                ]
            ]
        ];
        $this->componentConfig['params'] = $this->appendBackgroundImageConfig($this->componentConfig['params']);
        $this->componentConfig['params'] = $this->appendBackgroundContainerConfig($this->componentConfig['params']);
    }

    protected function setupConfig(): void
    {
        $this->setupConfigBase();
        $this->applyBackgroundColorFilter();
        if ($additional_options = apply_filters('visual_composer/row_additional_options', [])) {
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
