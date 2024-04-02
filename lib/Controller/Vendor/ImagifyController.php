<?php

namespace DevAnime\Controller\Vendor;

/**
 * class ImagifyController
 * @package DevAnime\Controller\Vendor
 */
class ImagifyController
{

    public function __construct()
    {
        add_filter('imagify_site_root', [$this, 'setSiteRoot'], 10001);
    }

    public function setSiteRoot($root_path)
    {
        $upload_basedir = imagify_get_filesystem()->get_upload_basedir(true);

        if (strpos($upload_basedir, '/wp-content/') === false) {
            return $root_path;
        }

        $upload_basedir = explode('/wp-content/', $upload_basedir);
        $upload_basedir = reset($upload_basedir);

        return trailingslashit($upload_basedir);
    }
}