<?php

namespace DevAnime\Controller;

/**
 * class EditorStylesController
 * @package DevAnime\Controller
 */
class EditorStylesController
{
    protected $style_formats = [];

    public function __construct()
    {
        add_filter('mce_buttons_2', [$this, 'reorderButtons']);
        add_filter('tiny_mce_before_init', [$this, 'addStyleFormats']);
    }

    public function reorderButtons($buttons)
    {
        array_unshift($buttons, 'styleselect');
        $buttons = array_flip($buttons);
        unset($buttons['forecolor'], $buttons['outdent'], $buttons['indent']);
        return array_keys($buttons);
    }

    public function addStyleFormats($init)
    {
        $init['style_formats'] = json_encode(array_values(apply_filters('devanime/editor_styles', $this->style_formats)));
        $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;';
        return $init;
    }


    public function add(string $title, string $classes, array $config = ['inline' => 'span'])
    {
        $this->style_formats[$classes] = array_merge(compact('title', 'classes'), $config);
        return $this;
    }

    public function remove($key)
    {
        unset($this->style_formats[$key]);
        return $this;
    }
}