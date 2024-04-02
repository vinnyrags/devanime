<?php

namespace DevAnime\Controller\Vendor;

/**
 * class BuVersionsController
 * @package DevAnime\Controller\Vendor
 */
class BuVersionsController
{
    private array $post_types_to_remove = [
        'acf-field-group'
    ];

    public function __construct()
    {
        add_filter('bu_alt_versions_for_type', [$this, 'removePostTypes'], 10, 2);
    }

    public function removePostTypes($should_register, \WP_Post_Type $type)
    {
        if (in_array($type->name, $this->post_types_to_remove)) {
            $should_register = false;
        }
        return $should_register;
    }
}