<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\ComponentRegistrationTrait;
use DevAnime\Vendor\VisualComposer\Support\RegistersComponentConfig;

require_once vc_path_dir('SHORTCODES_DIR', 'vc-column-text.php');

/**
 * Class VcColumnText
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class VcColumnText extends \WPBakeryShortCode_VC_Column_text implements RegistersComponentConfig
{
    const NAME = 'Text Block';
    const TAG = 'vc_column_text';

    use ComponentRegistrationTrait;

    public function __construct(array $settings = [])
    {
        $settings['base'] = static::TAG;
        parent::__construct($settings);
        $this->componentConfig = [
            'icon' => 'icon-wpb-layer-shape-text',
            'wrapper_class' => 'clearfix',
            'category' => 'Content',
            'description' => 'A block of text with WYSIWYG editor.',
            'params' => [
                [
                    'type' => 'textarea_html',
                    'holder' => 'div',
                    'heading' => 'Text',
                    'param_name' => 'content',
                    'value' => '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>',
                ],
                [
                    'type' => 'el_id',
                    'heading' => 'Element ID',
                    'param_name' => 'el_id',
                    'description' => 'Enter element ID (Note: make sure it is unique and valid according to <a href="http://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a>)',
                ],
                [
                    'type' => 'textfield',
                    'heading' => 'Extra class name',
                    'param_name' => 'el_class',
                    'description' => 'Style particular content element differently - add a class name and refer to it in custom CSS.',
                ]
            ]
        ];
    }
}
