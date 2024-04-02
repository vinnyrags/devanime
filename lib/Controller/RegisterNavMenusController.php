<?php

namespace DevAnime\Controller;

/**
 * class RegisterNavMenusController
 * @package DevAnime\Controller
 */
class RegisterNavMenusController
{
    protected array $nav_menus = [
        'header_navigation' => 'Header Navigation',
        'footer_navigation' => 'Footer Navigation'
    ];

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'registerNavMenus']);
    }

    public function registerNavMenus()
    {
        register_nav_menus(apply_filters('devanime/register_nav_menus', $this->nav_menus));
    }
}