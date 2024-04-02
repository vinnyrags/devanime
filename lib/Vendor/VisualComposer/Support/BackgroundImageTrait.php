<?php

namespace DevAnime\Vendor\VisualComposer\Support;

/**
 * Trait BackgroundImageTrait
 * @package DevAnime\Vendor\VisualComposer\Support
 */
trait BackgroundImageTrait
{
    protected function appendBackgroundImageConfig($config): array
    {
        return array_merge($config, [
            [
                'type' => 'dropdown',
                'heading' => 'Background Color',
                'param_name' => 'background_color',
                'value' => [
                    'Transparent' => '',
                    'Dark' => 'dark'
                ],
                'description' => 'Set the section background color.',
                'group' => 'Background'
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Placeholder',
                'param_name' => 'bg_use_placeholder',
                'description' => 'Use a temporary placeholder image for scaffolding',
                'value' => ['Use Placeholder' => 'yes'],
                'group' => 'Background'
            ],
            [
                'type'        => 'textfield',
                'heading'     => 'Placeholder Width',
                'param_name'  => 'placeholder_width',
                'edit_field_class'=> 'vc_col-xs-6',
                'group'       => 'Background',
                'dependency' => [
                    'element' => 'bg_use_placeholder',
                    'not_empty' => true
                ],
            ],
            [
                'type'        => 'textfield',
                'heading'     => 'Placeholder Height',
                'param_name'  => 'placeholder_height',
                'edit_field_class'=> 'vc_col-xs-6',
                'group'       => 'Background',
                'dependency' => [
                    'element' => 'bg_use_placeholder',
                    'not_empty' => true
                ],
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Placeholder Category',
                'param_name' => 'placeholder_category',
                'edit_field_class'=> 'vc_col-xs-6',
                'value' => [
                    'All' => 'any',
                    'Animals' => 'animals',
                    'Architecture' => 'arch',
                    'Nature' => 'nature',
                    'People' => 'people',
                    'Tech' => 'tech'
                ],
                'dependency' => [
                    'element' => 'bg_use_placeholder',
                    'not_empty' => true
                ],
                'group' => 'Background'
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Placeholder Filters',
                'param_name' => 'placeholder_filters',
                'edit_field_class'=> 'vc_col-xs-6',
                'value' => [
                    'None' => '',
                    'Grayscale' => 'grayscale',
                    'Sepia' => 'sepia'
                ],
                'dependency' => [
                    'element' => 'bg_use_placeholder',
                    'not_empty' => true
                ],
                'group' => 'Background'
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Responsive',
                'param_name' => 'mobile_alternate',
                'description' => 'Use a different image on narrow viewports. If empty, no background image will be used on mobile',
                'dependency' => [
                    'element' => 'background_image_id',
                    'not_empty' => true
                ],
                'value' => ['Alternate Image on Mobile' => 'yes'],
                'group' => 'Background'
            ],
            [
                'type' => 'attach_image',
                'heading' => 'Background Image',
                'param_name' => 'background_image_id',
                'value' => '',
                'description' => 'Set an optional background image',
                'edit_field_class' => 'vc_col-xs-6',
                'group' => 'Background',
                'dependency' => [
                    'element' => 'bg_use_placeholder',
                    'is_empty' => true
                ],
            ],
            [
                'type' => 'attach_image',
                'heading' => 'Mobile Image',
                'param_name' => 'background_mobile_image_id',
                'value' => '',
                'edit_field_class' => 'vc_col-xs-6',
                'dependency' => [
                    'element' => 'mobile_alternate',
                    'not_empty' => true,
                ],
                'description' => 'Set optional mobile background image',
                'group' => 'Background'
            ],
            [
                'type'        => 'textfield',
                'heading'     => 'Image Opacity',
                'param_name' => 'bg_image_opacity',
                'edit_field_class' => 'vc_col-xs-4',
                'value' => 100,
                'group'       => 'Background',
                'dependency' => [
                    'element' => 'background_image_id',
                    'not_empty' => true
                ],
            ],
            [
                'type' => 'checkbox',
                'heading' => 'Video',
                'param_name' => 'bg_use_video',
                'description' => 'Use a background video',
                'value' => ['Use Background Video' => 'yes'],
                'group' => 'Background'
            ],
            [
                'type'        => 'textfield',
                'heading'     => 'Desktop Video',
                'param_name'  => 'desktop_video',
                'edit_field_class'=> 'vc_col-xs-4',
                'group'       => 'Background',
                'dependency' => [
                    'element' => 'bg_use_video',
                    'not_empty' => true
                ],
            ],
            [
                'type'        => 'textfield',
                'heading'     => 'Mobile Video',
                'param_name'  => 'mobile_video',
                'edit_field_class'=> 'vc_col-xs-4',
                'group'       => 'Background',
                'dependency' => [
                    'element' => 'bg_use_video',
                    'not_empty' => true
                ],
            ],
            [
                'type'        => 'textfield',
                'heading'     => 'Darken Opacity',
                'param_name' => 'video_overlay_percent',
                'edit_field_class' => 'vc_col-xs-4',
                'value' => 0,
                'group'       => 'Background',
                'description' => 'Range 0-100',
                'dependency' => [
                    'element' => 'bg_use_video',
                    'not_empty' => true
                ],
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Background Image Size',
                'param_name' => 'background_size',
                'value' => [
                    'Default (Cover)' => '',
                    'Contain' => 'contain',
                    'Tile' => 'tile'
                ],
                'dependency' => [
                    'element' => 'background_image_id',
                    'not_empty' => true
                ],
                'description' => 'Determine the size of the background image.',
                'group' => 'Background'
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Background Image Vertical Position',
                'param_name' => 'background_v_position',
                'value' => [
                    'Default (Center)' => '',
                    'Top' => 'top',
                    'Bottom' => 'bottom'
                ],
                'dependency' => [
                    'element' => 'background_image_id',
                    'not_empty' => true
                ],
                'edit_field_class'=> 'vc_col-xs-6',
                'description' => 'Determine the position of the background image.',
                'group' => 'Background'
            ],
            [
                'type' => 'dropdown',
                'heading' => 'Background Image Horizontal Position',
                'param_name' => 'background_h_position',
                'value' => [
                    'Default (Center)' => '',
                    'Left' => 'left',
                    'Right' => 'right'
                ],
                'dependency' => [
                    'element' => 'background_image_id',
                    'not_empty' => true
                ],
                'edit_field_class'=> 'vc_col-xs-6',
                'description' => 'Determine the position of the background image.',
                'group' => 'Background'
            ],

            [
                'type' => 'dropdown',
                'heading' => 'Background Image Repeat',
                'param_name' => 'background_repeat',
                'value' => [
                    'Default (None)' => '',
                    'Both' => 'repeat-both',
                    'Horizontal' => 'repeat-x',
                    'Vertical' => 'repeat-y',
                ],
                'dependency' => [
                    'element' => 'background_size',
                    'not_empty' => true
                ],
                'description' => 'Repeat direction of tiled background.',
                'group' => 'Background'
            ]
        ]);
    }

    protected function applyBackgroundColorFilter(): void
    {
        $index = array_search('background_color', array_column($this->componentConfig['params'], 'param_name'));
        if ($index !== false) {
            $this->componentConfig['params'][$index]['value'] = apply_filters(
                'visual_composer/background_colors/' . static::TAG,
                apply_filters('visual_composer/background_colors', $this->componentConfig['params'][$index]['value'])
            );
        }
    }
}
