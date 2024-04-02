<?php

namespace DevAnime\Register\PostType;

/**
 * Class PostTypeAdminFilters
 * @package DevAnime\Register\PostType
 */
class PostTypeAdminFilters
{
    protected string $slug;
    protected array $columns = [];
    protected PostTypeArguments $register;

    public function __construct(string $slug, array $columns, PostTypeArguments $register)
    {
        $this->slug = $slug;
        $this->columns = $columns;
        $this->register = $register;
        add_action('restrict_manage_posts', [$this, 'renderColumnFilters']);
        add_action('parse_query', [$this, 'filterColumnsByMeta']);
    }

    public function renderColumnFilters(): void
    {
        global $typenow;
        if ($typenow !== $this->slug) {
            return;
        }

        $filters = array_merge($this->getCustomFilters(), $this->getTaxonomyFilters());
        $keys = array_map(fn ($val) => $val['id'], $filters);
        $filters = array_combine($keys, $filters);
        $filters = apply_filters('devanime/admin_filters', $filters, $this->slug);
        $filters = array_filter(array_map(fn ($filter) => count($filter['options']) <= 1 ? false : $filter, $filters));

        if (empty($filters)) {
            return;
        }

        foreach ($filters as $filter) {
            echo "<select name='{$filter['id']}' id='{$filter['id']}' class='postform'>";
            foreach ($filter['options'] as $option) {
                $selected = $option['selected'] ? " selected='selected'" : "";
                echo "<option value={$option['value']}{$selected}>{$option['label']}</option>";
            }
            echo "</select>";
        }
    }

    private function getCustomFilters(): array
    {
        global $wpdb;
        $filters = [];
        if (empty($this->columns)) {
            return $filters;
        }

        foreach ($this->columns as $id => $column) {
            if (!is_array($column) || (!empty($this->register->args['taxonomies']) && in_array($id, $this->register->args['taxonomies'])) || empty($column['filterable'])) {
                continue;
            }

            $fields = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key=%s ORDER BY 1", $id));

            if (empty($fields)) {
                continue;
            }

            $filter = ['id' => $id];
            $filter['options'][] = ['value' => '', 'label' => "Filter by {$column['label']}", 'selected' => false];

            array_walk($fields, fn ($el) => $el->label = (string)$el->meta_value);
            $fields = apply_filters("devanime/admin_filters/$id", $fields, $this->slug);

            foreach ($fields as $field) {
                $filter['options'][] = ['value' => urlencode($field->meta_value), 'label' => $field->label, 'selected' => isset($_GET[$id]) && $_GET[$id] == urlencode($field->meta_value)];
            }

            $filters[] = $filter;
        }

        return $filters;
    }

    private function getTaxonomyFilters(): array
    {
        $filters = [];
        if (empty($this->register->args['taxonomies'])) {
            return $filters;
        }

        foreach ($this->register->args['taxonomies'] as $taxSlug) {
            if (in_array($taxSlug, ['category', 'post_tag'])) {
                continue;
            }

            $taxObj = get_taxonomy($taxSlug);

            if (!$taxObj->show_admin_column) {
                continue;
            }

            $terms = get_terms(['taxonomy' => $taxSlug]);

            if (is_wp_error($terms)) {
                continue;
            }

            $filter = ['id' => $taxSlug];
            $filter['options'][] = ['value' => '', 'label' => "All {$taxObj->labels->name}", 'selected' => false];

            foreach ($terms as $term) {
                $filter['options'][] = ['value' => $term->slug, 'label' => $term->name, 'selected' => isset($_GET[$taxSlug]) && $_GET[$taxSlug] == $term->slug];
            }

            $filters[] = $filter;
        }

        return $filters;
    }

    public function filterColumnsByMeta(\WP_Query $query): void
    {
        global $pagenow;
        if (!(is_admin() && $pagenow == 'edit.php') || !$query->is_main_query() || $query->get('post_type') !== $this->slug || empty($this->columns)) {
            return;
        }

        foreach ($this->columns as $id => $column) {
            if ((!empty($this->register->args['taxonomies']) && in_array($id, $this->register->args['taxonomies'])) || empty($column['filterable'])) {
                continue;
            }

            if (isset($_GET[$id]) && $_GET[$id] != '') {
                $query->query_vars['meta_query'][] = ['key' => $id, 'value' => urldecode($_GET[$id])];
            }
        }
    }
}
