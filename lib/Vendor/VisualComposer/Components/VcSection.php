<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\BackgroundContainerTrait;
use DevAnime\Vendor\VisualComposer\Support\BackgroundImageTrait;
use DevAnime\Vendor\VisualComposer\Support\ComponentRegistrationTrait;
use DevAnime\Vendor\VisualComposer\Support\RegistersComponentConfig;
use WPBakeryShortCode;

require_once vc_path_dir('SHORTCODES_DIR', 'vc-section.php');

/**
 * Class VcSection
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class VcSection extends \WPBakeryShortCode_VC_Section implements RegistersComponentConfig
{
    const NAME = 'Section';
    const TAG = 'vc_section';

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
            'description' => __( 'Group multiple rows in section', 'devanime' ),
            'icon' => 'vc_icon-vc-section',
            'wrapper_class' => 'clearfix',
            'is_container' => true,
            'category' => 'Layout',
            'show_settings_on_create' => false,
            'class' => 'vc_main-sortable-element',
            'js_view' => 'VcSectionView',
            'weight' => 100,
            'as_parent' => [
                'only' => 'vc_row',
            ],
            'as_child' => [
                'only' => '', // Only root
            ],
            'params' => [
                [
                    'type' => 'el_id',
                    'heading' => 'Section ID',
                    'param_name' => 'el_id',
                    'description' => 'Enter section ID (Note: make sure it is unique and valid according to <a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a>',
                    'group' => 'General'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Use Section ID as Tab ID',
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
                    'description' => 'Set this section to be the default tab.',
                    'group' => 'General'
                ],
                [
                    'type' => 'checkbox',
                    'heading' => 'Disable section',
                    'param_name' => 'disable_element',
                    'description' => 'If checked the section won\'t be visible on the public side of your website. You can switch it back any time.',
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
                    'heading' => 'Column Layout',
                    'param_name' => 'column_layout',
                    'value' => [
                        'Default (Row)' => '',
                        'Grid' => 'grid',
                        'Tile' => 'tile'
                    ],
                    'description' => 'In a <strong>Row</strong> layout, columns within each row contain related content that should stack evenly (separated by standard component spacing) on mobile, while retaining separation between rows. <br>In a <strong>Grid</strong> layout, each column contains discrete content that should separate evenly when stacked. <br>In a <strong>Tile</strong> layout, there is no space between columns or rows.',
                    'group' => 'Layout'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Content Width',
                    'param_name' => 'width',
                    'value' => [
                        'Default' => '',
                        'Full' => 'width-full',
                        'Narrow' => 'width-narrow'
                    ],
                    'description' => 'Set the section\'s inner content width.',
                    'group' => 'Layout'
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
                    'description' => 'Set the section bottom spacing.',
                    'group' => 'Layout'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Height',
                    'param_name' => 'height',
                    'value' => [
                        'Default' => '',
                        'Full' => 'height-full',
                    ],
                    'description' => 'Set the section height.',
                    'group' => 'Layout'
                ]
            ]
        ];
        $this->componentConfig['params'] = $this->appendBackgroundImageConfig($this->componentConfig['params']);
        $this->componentConfig['params'] = $this->appendBackgroundContainerConfig($this->componentConfig['params']);
        $this->componentConfig['params'] = $this->appendSectionSpecificBackgroundConfig($this->componentConfig['params']);
    }

    protected function appendSectionSpecificBackgroundConfig($config): array
    {
        return array_merge($config, [
            [
                'type' => 'dropdown',
                'param_name' => 'container_position',
                'heading' => 'Background Container Position',
                'description' => 'Set the background container orientation.',
                'group' => 'Background',
                'value' => [
                    'Default' => '',
                    'Left' => 'bg-container-left',
                    'Right' => 'bg-container-right'
                ],
                'edit_field_class'=> 'vc_col-xs-6',
            ]
        ]);
    }

    protected function setupConfig(): void
    {
        $this->setupConfigBase();
        $this->applyBackgroundColorFilter();
        if ($additional_options = apply_filters('visual_composer/section/additional-options', [])) {
            $this->componentConfig['params'][] = [
                'type' => 'checkbox',
                'param_name' => 'options',
                'heading' => 'Additional Options',
                'group' => 'Layout',
                'value' => $additional_options
            ];
        }
        // eg: add_filter('visual_composer/section/param/column_layout', [$this, 'layoutTemplates']);
        foreach ($this->componentConfig['params'] as $key => $param) {
            if ($param['type'] === 'dropdown') {
                $this->componentConfig['params'][$key]['value'] = apply_filters(
                    'visual_composer/section/param/' . $param['param_name'],
                    $param['value']
                );
            }
        }
    }
}