<?php

namespace DevAnime\Controller;

/**
 * class AdminAssetsController
 * @package DevAnime\Controller
 */
class AdminAssetsController
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'adminAssets'], 100);
    }

    public function adminAssets()
    {
        wp_enqueue_script('theme/admin/js', get_template_directory_uri() . '/assets/scripts/admin.js');
    }
}