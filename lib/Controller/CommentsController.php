<?php

namespace DevAnime\Controller;

/**
 * class CommentsController
 * @package DevAnime\Controller
 */
class CommentsController
{
    public function __construct()
    {
        if (!apply_filters('devanime/enable_comments', false)) {
            add_action('admin_menu', [$this, 'removeComments']);
        }
    }

    public function removeComments()
    {
        remove_menu_page('edit-comments.php');
    }
}