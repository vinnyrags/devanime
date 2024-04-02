<?php

namespace DevAnime\Controller\Vendor;

/**
 * class AcfController
 * @package DevAnime\Controller\Vendor
 *
 * @TODO unused
 */
class AcfController
{
    public function __construct()
    {
        if (function_exists('get_field')) {
            add_action('acf/include_field_types', [$this, 'includeFieldTypes']);
        }
    }

    public function includeFieldTypes()
    {
        if (did_action('after_setup_theme')) {
            return;
        }
        $acf_json = acf()->json;
        remove_action('acf/include_fields', [$acf_json, 'include_json_folders']);
        add_action('init', [$acf_json, 'include_json_folders'], 1);
    }
}
