<?php

namespace DevAnime\Controller;

/**
 * class AdminMenuController
 * @package DevAnime\Controller
 */
class AdminMenuController
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'updateAdminMenu']);
    }

    public function updateAdminMenu()
    {
        $this->removeCustomizer();
        $this->removePatterns();
        if (apply_filters('devanime/remove_default_post_type', false)) {
            add_action('admin_menu', [$this, 'removeDefaultPostType']);
        }
    }

    public function removeCustomizer()
    {
        $customize_url = add_query_arg('return', urlencode(remove_query_arg(wp_removable_query_args(), wp_unslash($_SERVER['REQUEST_URI']))), 'customize.php');
        remove_submenu_page('themes.php', $customize_url);
    }

    public function removePatterns()
    {
        remove_submenu_page('themes.php', 'edit.php?post_type=wp_block');
    }

    public function removeDefaultPostType()
    {
        remove_menu_page('edit.php');
    }
}