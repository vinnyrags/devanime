<?php

namespace DevAnime\Controller;

/**
 * class ThemeSupportController
 * @package DevAnime\Controller
 */
class ThemeSupportController
{
    protected array $default_theme_support = [
        'title-tag',
        'post-thumbnails'
    ];

//    protected array $remove_theme_support = [
//        'core-block-patterns'
//    ];

    protected array $soil_theme_support = [
        'clean-up',
        'disable-asset-versioning',
        'disable-trackbacks',
        'nav-walker',
        'nice-search',
        'relative-urls'
    ];

    public function __construct()
    {
        add_action('after_setup_theme', function () {
//            remove_theme_support('core-block-patterns');
//            array_map('remove_theme_support', apply_filters('devanime/remove_theme_support', $this->remove_theme_support));
            array_map('add_theme_support', apply_filters('devanime/theme_support', $this->default_theme_support));
            if (!empty($soil_theme_support = apply_filters('devanime/soil_theme_support', $this->soil_theme_support))) {
                add_theme_support('soil', $soil_theme_support);
            }
        });
    }
}