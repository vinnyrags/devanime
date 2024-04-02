<?php

namespace DevAnime\Vendor\VisualComposer\Support;

use WPBakeryShortCode;

/**
 * Class Component
 * @package DevAnime\Vendor\VisualComposer\Support
 */
class Component extends WPBakeryShortCode implements RegistersComponentConfig
{
    protected const NAME = null;
    protected const TAG = null;
    protected const VIEW = null;

    use ComponentRegistrationTrait;

    public function __construct($settings = [])
    {
        $settings['base'] = static::TAG;
        parent::__construct($settings);
        add_filter('visual_composer/admin_post_title', [$this, 'adminPostTitleView'], 5, 2);
    }

    public function singleParamHtmlHolder($param, $value)
    {
        if (!$this->hasImageParam()) {
            return parent::singleParamHtmlHolder($param, $value);
        }

        $output = '';
        $paramName = $param['param_name'] ?? '';
        $type = $param['type'] ?? '';
        $class = $param['class'] ?? '';

        if ('attach_image' === $param['type'] && $paramName === 'image') {
            $output .= '<input type="hidden" class="wpb_vc_param_value ' . $paramName . ' ' . $type . ' ' . $class . '" name="' . $paramName . '" value="' . $value . '" />';
            $elementIcon = $this->settings('icon');
            $img = wpb_getImageBySize([
                'attach_id' => (int) preg_replace('/[^\d]/', '', $value),
                'thumb_size' => 'thumbnail',
            ]);
            $this->setSettings('logo', ($img ? $img['thumbnail'] :
                    '<img width="150" height="150" src="' . vc_asset_url('vc/blank.gif') .
                    '" class="attachment-thumbnail vc_general vc_element-icon icon-wpb-single-image"  data-name="' . $paramName .
                    '" alt="" title="" style="display: none;" />') . '<span class="no_image_image vc_element-icon' .
                (!empty($elementIcon) ? ' ' . $elementIcon : '') .
                ($img && !empty($img['p_img_large'][0]) ? ' image-exists' : '') .
                '" /><a href="#" class="column_edit_trigger' .
                ($img && !empty($img['p_img_large'][0]) ? ' image-exists' : '') . '">' .
                __('Add image', 'devanime') . '</a>');
            $output .= '<h4 class="wpb_element_title">' . $this->settings['name'] . ' ' . $this->settings('logo') . '</h4>';
        } else {
            return parent::singleParamHtmlHolder($param, $value);
        }

        if (!empty($param['admin_label']) && true === $param['admin_label']) {
            $output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] .
                (empty($value) ? ' hidden-label' : '') .
                '"><label>' . $param['heading'] . '</label>: ' . $value . '</span>';
        }
        return $output;
    }

    protected function outputTitle($title)
    {
        return $this->hasImageParam() ? '' : parent::outputTitle($title);
    }

    protected function hasImageParam(): bool
    {
        $imageParams = array_filter($this->componentConfig['params'], function ($p) {
            return isset($p['type']) && $p['type'] == 'attach_image' && $p['param_name'] == 'image';
        });
        return !empty($imageParams);
    }

    /**
     * @param string $content
     * @param \WP_Post $postObj
     *
     * @return string
     */
    public function adminPostTitleView($content, $postObj): string
    {
        return $content;
    }
}
