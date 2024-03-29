<?php

namespace DevAnime\Controller;

use DevAnime\Factory;

/**
 * class FactoryController
 * @package DevAnime\Controller
 */
class FactoryController
{
    protected $post_type_models = [];
    protected $taxonomy_models = [];
    protected $data;

    function __construct()
    {
        add_action('init', [$this, 'initFactories'], 7);
    }

    public function initFactories()
    {
        $this->post_type_models = array_values(array_filter(apply_filters('devanime/register_post_type_models', [])));
        foreach ($this->post_type_models as $model_class) {
            Factory\PostFactory::registerPostModel($model_class);
        }
        $this->taxonomy_models = array_values(array_filter(apply_filters('devanime/register_taxonomy_models', [])));
        foreach ($this->taxonomy_models as $model_class) {
            Factory\TermFactory::registerTermModel($model_class);
        }
    }

}