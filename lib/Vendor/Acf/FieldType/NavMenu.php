<?php

namespace DevAnime\Vendor\Acf\FieldType;

/**
 * Class NavMenu
 * @package DevAnime\Vendor\Acf\FieldType
 * @author  Vincent Ragosta <vragosta@situationinteractive.com>
 * @version 1.0
 */
class NavMenu extends \acf_field
{
    function __construct()
    {
        $this->name = 'nav-menu';
        $this->label = 'Nav Menu';
        $this->category = 'relational';
        parent::__construct();
    }

    function render_field($field)
    {
        $field['type'] = 'select';
        $field['ui'] = 0;
        $field['ajax'] = 0;
        $field['choices'] = [];
        $field['allow_null'] = true;
        $field['multiple'] = false;
        $field['placeholder'] = 'Select Nav Menu';
        foreach (wp_get_nav_menus() as $NavMenu) {
            $field['choices'][$NavMenu->term_id] = $NavMenu->name;
        }
        asort($field['choices']);
        acf_render_field($field);
    }
}