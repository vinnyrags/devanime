<?php

namespace DevAnime\Controller\Vendor;

/**
 * class YoastController
 * @package DevAnime\Controller\Vendor
 *
 * @TODO unused
 */
class YoastController
{
    public function __construct()
    {
        add_filter('wpseo_metabox_prio', function () {
            return 'low';
        });
        add_filter('wpseo_premium_post_redirect_slug_change', '__return_true');
        add_filter('wpseo_sitemap_content_before_parse_html_images', '__return_empty_string');

        // TODO turn on soil?
//        add_filter('soil/relative-url-filters', function ($filters) {
//            if (! is_admin()) {
//                return $filters;
//            }
//            if (strpos($_SERVER['HTTP_REFERER'] ?? '', 'page=wpseo') !== false) {
//                $filters = array_values(array_diff($filters, ['wp_get_attachment_url']));
//            }
//
//            return $filters;
//        });
    }
}