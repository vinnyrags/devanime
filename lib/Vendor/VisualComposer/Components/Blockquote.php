<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\Component;

/**
 * Class Blockquote
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class Blockquote extends Component
{
    const NAME = 'Blockquote';
    const TAG = 'blockquote';

    protected array $componentConfig = [
        'description' => 'A block quotation with attribution and footnote',
        'icon' => 'icon-wpb-toggle-small-expand',
        'wrapper_class' => 'clearfix',
        'is_container' => false,
        'category' => 'Content',
        'params' => [
            [
                'type' => 'textarea_html',
                'heading' => 'Quote',
                'param_name' => 'content',
                'value' => '',
                'description' => 'Quotation marks added automatically.',
                'group' => 'General',
                'admin_label' => true
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Size Override',
                'param_name' => 'size',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => [
                    '-- select --' => '',
                    'Default Heading' => 'default',
                    'Medium Heading' => 'medium',
                    'Large Heading' => 'large',
                    'X-Large Heading' => 'xlarge'
                ],
                'dependency' => [
                    'element' => 'use_custom_icon',
                    'is_empty' => true,
                ],
                'group' => 'General',
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Icon',
                'param_name' => 'icon',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => [
                    '-- select --' => '',
                    'Twitter' => 'twitter',
                    'Facebook' => 'facebook',
                    'Instagram' => 'instagram',
                    'Youtube' => 'youtube'
                ],
                'dependency' => [
                    'element' => 'use_custom_icon',
                    'is_empty' => true,
                ],
                'group' => 'General',
            ],
            [
                'type' => 'textfield',
                'heading' => 'Icon',
                'param_name' => 'custom_icon',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => '',
                'description' => 'SVG icon slug (must be supported by theme)',
                'dependency' => [
                    'element' => 'use_custom_icon',
                    'not_empty' => true,
                ],
                'group' => 'General',
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Custom Icon?',
                'param_name' => 'use_custom_icon',
                'edit_field_class' => 'vc_col-sm-4',
                'value' => '',
                'group' => 'General',
            ],

            [
                'type' => 'textfield',
                'heading' => 'Attribution',
                'param_name' => 'attribution',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => '',
                'description' => 'Quote author. Leading dash added automatically',
                'dependency' => [
                    'element' => 'link_attribution',
                    'is_empty' => true,
                ],
                'group' => 'General',
                'admin_label' => true
            ],
            [
                'type' => 'vc_link',
                'heading' => 'Attribution Link',
                'param_name' => 'attribution_link',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => '',
                'dependency' => [
                    'element' => 'link_attribution',
                    'not_empty' => true
                ],
                'description' => 'Quote author. Leading dash added automatically',
                'group' => 'General',
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Link Attribution?',
                'param_name' => 'link_attribution',
                'edit_field_class' => 'vc_col-sm-4',
                'value' => '',
                'group' => 'General',
            ],
            [
                'type' => 'textfield',
                'heading' => 'Cite',
                'param_name' => 'cite',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => '',
                'dependency' => [
                    'element' => 'link_cite',
                    'is_empty' => true,
                ],
                'description' => 'Quote source (title of book, publication, song, movie, etc.)',
                'group' => 'General',
                'admin_label' => true
            ],
            [
                'type' => 'vc_link',
                'heading' => 'Cite Link',
                'param_name' => 'cite_link',
                'edit_field_class' => 'vc_col-sm-8',
                'value' => '',
                'dependency' => [
                    'element' => 'link_cite',
                    'not_empty' => true
                ],
                'description' => 'Quote source (title of book, publication, song, movie, etc.)',
                'group' => 'General',
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Link Cite?',
                'param_name' => 'link_cite',
                'edit_field_class' => 'vc_col-sm-4',
                'value' => '',
                'group' => 'General',
            ],
            [
                'type' => 'textarea',
                'heading' => 'Footnote',
                'param_name' => 'footnote',
                'value' => '',
                'description' => 'Additional text related to quote or attribution',
                'group' => 'General'
            ]
        ]
    ];
}
