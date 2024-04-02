<?php

namespace DevAnime\Register;

use DevAnime\Support\Util;
use DevAnime\Controller;
use DevAnime\Register\PostType\PostTypeArguments;
use DevAnime\Register\PostType\PostTypeAdminColumns;
use DevAnime\Register\PostType\PostTypeSort;
use DevAnime\Register\PostType\PostTypeAdminFilters;

/**
 * Class RegisterPostType
 * @package DevAnime\Register
 */
class RegisterPostType
{
    protected string $slug;
    protected PostTypeArguments $register;
    protected array $extras;
    protected PostTypeAdminColumns $admin_columns;
    protected PostTypeSort $query_filter;

    public function __construct(string $slug, array $data)
    {
        $this->slug = $slug;
        $this->register = new PostTypeArguments($data['labels'], $data['args']);
        $this->extras = $data['extras'] ?? [];
        $this->admin_columns = new PostTypeAdminColumns($this->slug, $this->register);
        $this->query_filter = new PostTypeSort($this->slug, $this->register);

        if (empty($this->extras['admin_columns'])) {
            $this->extras['admin_columns'] = [];
        }

        foreach ($this->extras as $key => $args) {
            $method_name = Util::toCamelCase($key);
            if (method_exists($this, $method_name)) {
                $this->{$method_name}($args);
            }
        }

        add_action('init', [$this, 'registerPostType'], 8);
    }

    public function registerPostType(): void
    {
        if (!post_type_exists($this->slug)) {
            register_post_type($this->slug, $this->register->args);
        }
    }

    protected function defaultSort(array $args): void
    {
        $this->query_filter->setDefaultSort($args);
    }

    protected function adminSort(array $args): void
    {
        $this->query_filter->setAdminSort($args);
    }

    protected function titlePlaceholder(string $title): void
    {
        if (empty($title)) {
            return;
        }

        add_filter('enter_title_here', function ($default, $post) use ($title) {
            return $post->post_type === $this->slug ? $title : $default;
        }, 10, 2);
    }

    protected function adminColumns(array $args): void
    {
        new Controller\PostTypeAdminColumnController();
        $this->admin_columns->init($args);
        $this->query_filter->setColumns($args);
        new PostTypeAdminFilters($this->slug, $args, $this->register);
    }

    protected function acfSettings($args): void
    {
        if (is_string($args)) {
            $args = ['page_title' => $args];
        }

        if (is_bool($args)) {
            $args = ['page_title' => $this->register->labels['singular_name'] . ' Settings'];
        }

        if (is_array($args)) {
            $defaults = ['parent_slug' => 'edit.php?post_type=' . $this->slug];
            $args = wp_parse_args($args, $defaults);
            new RegisterOption($args);
        }
    }

    protected function cmspoLabel(string $label): void
    {
        $this->query_filter->cmspoLabel($label);
    }
}
