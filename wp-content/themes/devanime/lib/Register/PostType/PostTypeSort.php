<?php

namespace DevAnime\Register\PostType;

/**
 * Class PostTypeSort
 * @package DevAnime\Register\PostType
 */
class PostTypeSort
{
    protected ?array $default_sort;
    protected ?array $admin_sort;
    private string $slug;
    private PostTypeArguments $register;
    private string $cmspo_label;
    private array $columns = [];

    public function __construct(string $slug, PostTypeArguments $register)
    {
        $this->slug = $slug;
        $this->register = $register;
        $this->cmspoLabel('Sort ' . $this->register->labels['name']);
        add_action('pre_get_posts', [$this, 'preGetPosts']);
        add_filter('cmspo_post_types', [$this, 'addToCmspo']);
        add_filter('cmspo_page_label', [$this, 'setCmspoLabel'], 10, 2);
        add_filter('cmspo_max_levels', [$this, 'cmspoMaxLevels']);
        add_action('save_post', [$this, 'savePost'], 13, 2);
    }

    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    public function setDefaultSort(array $default_sort): void
    {
        $this->default_sort = $default_sort;
    }

    public function setAdminSort(array $admin_sort): void
    {
        $this->admin_sort = $admin_sort;
    }

    public function preGetPosts(\WP_Query $query): void
    {
        if ($query->get('post_type') !== $this->slug) {
            return;
        }
        if (is_admin()) {
            if (isset($_GET['orderby'])) {
                if ($query->is_main_query()) {
                    $this->sortColumnsByMeta($query);
                }
            } else {
                $sort = $this->admin_sort ?: $this->default_sort;
                if (!empty($sort)) {
                    foreach ($sort as $key => $val) {
                        $query->set($key, $val);
                    }
                }
            }
        } else {
            if (!empty($this->default_sort) && !$query->get('orderby')) {
                foreach ($this->default_sort as $key => $val) {
                    $query->set($key, $val);
                }
            }
        }
    }

    protected function sortColumnsByMeta(\WP_Query $query): void
    {
        $orderby = $query->get('orderby');
        if (
            (isset($this->register->args['taxonomies']) && in_array($orderby, $this->register->args['taxonomies'])) ||
            in_array($orderby, [
                'none',
                'ID',
                'author',
                'title',
                'name',
                'date',
                'modified',
                'parent',
                'rand',
                'comment_count',
                'menu_order',
                'meta_value',
                'meta_value_num',
                'title menu_order',
                'post__in'
            ]) ||
            !isset($this->columns[$orderby])
        ) {
            return;
        }
        $column = $this->columns[$orderby];
        $query_params = is_array($column['sortable']) ? $column['sortable'] : [
            'orderby' => 'meta_value',
            'meta_key' => $orderby
        ];
        $query_params = apply_filters('devanime/admin_sort/' . $orderby, $query_params, $this->slug);
        foreach ($query_params as $key => $query_param) {
            $query->set($key, $query_param);
        }
    }

    public function addToCmspo(array $post_types): array
    {
        if ($this->isMenuOrder()) {
            $post_types[] = $this->slug;
        }

        return $post_types;
    }

    public function cmspoLabel(string $label): void
    {
        $this->cmspo_label = $label;
    }

    public function setCmspoLabel(string $label, string $post_type): string
    {
        return $this->slug == $post_type ? $this->cmspo_label : $label;
    }

    public function cmspoMaxLevels(int $levels): int
    {
        $screen = get_current_screen();
        if (!empty($screen->post_type) && $screen->post_type == $this->slug) {
            if (empty($this->register->args['hierarchical'])) {
                $levels = 1;
            }
        }

        return $levels;
    }

    private function isMenuOrder(): bool
    {
        return (
            (!empty($this->default_sort['orderby']) && $this->default_sort['orderby'] == 'menu_order') ||
            (!empty($this->admin_sort['orderby']) && $this->admin_sort['orderby'] == 'menu_order')
        );
    }

    public function savePost(int $post_id, \WP_Post $post_obj): void
    {
        if (!apply_filters('devanime/save_post/increment_menu_order', true, $post_obj)) {
            return;
        }
        if (!isset($_POST['post_type']) || $this->slug != $_POST['post_type']) {
            return;
        }
        remove_action('save_post', [$this, 'savePost'], 13);
        if ($this->isMenuOrder()) {
            $this->setPostMenuOrder($post_id, $post_obj);
        }
        add_action('save_post', [$this, 'savePost'], 13, 2);
    }

    public function setPostMenuOrder(int $post_id, \WP_Post $post_obj): void
    {
        if (
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
            (defined('DOING_AJAX') && DOING_AJAX) ||
            in_array($post_obj->post_status, ['auto-draft', 'inherit']) ||
            0 != $post_obj->menu_order
        ) {
            return;
        }
        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare(
            "SELECT MAX(menu_order) AS menu_order FROM $wpdb->posts WHERE post_type=%s", $this->slug
        ), ARRAY_A);
        $order = intval($result[0]['menu_order']) + 1;
        $post_obj->menu_order = $order;
        wp_update_post($post_obj);
    }
}
