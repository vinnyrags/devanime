<?php

namespace DevAnime\Register\PostType;

/**
 * Class PostTypeAdminColumns
 * @package DevAnime\Register\PostType
 */
class PostTypeAdminColumns
{
    private string $slug;
    private PostTypeArguments $register;
    private array $columns = [];

    public function __construct(string $slug, PostTypeArguments $register)
    {
        $this->slug = $slug;
        $this->register = $register;
        add_action('manage_edit-' . $this->slug . '_sortable_columns', [$this, 'sortableColumns']);
    }

    public function init(array $columns): void
    {
        $this->columns = $columns;
        add_filter('manage_' . $this->slug . '_posts_columns', [$this, 'columnHeaders']);
        add_action('manage_' . $this->slug . '_posts_custom_column', [$this, 'columnContent'], 10, 2);
        add_action('admin_print_styles', [$this, 'printAdminStyles']);
    }

    /**
     * Customize admin columns, but ensure cb, title, and date remain in the column list if not otherwise specified.
     *
     * @param array $columns
     *
     * @return array
     */
    public function columnHeaders(array $columns): array
    {
        $headers = $taxonomies = [];

        foreach ($this->columns as $slug => $column) {
            $taxSlug = 'taxonomy-' . $slug;
            $slug = array_key_exists($taxSlug, $columns) ? $taxSlug : $slug;
            $headers[$slug] = is_array($column) ? $column['label'] : $column;
        }
        foreach ($columns as $slug => $column) {
            if (str_contains($slug, 'taxonomy-')) {
                $taxonomies[] = $slug;
            }
        }

        $getDefaults = function (array $arr) use ($columns, $headers): array {
            /* Pulls default columns out if exists in custom columns list */
            return array_intersect_key($columns, array_diff_key(array_flip($arr), $headers));
        };

        $columns = empty($headers) ?
            $columns :
            array_merge(
                $getDefaults(['cb', 'title']),
                $headers,
                $getDefaults($taxonomies),
                $getDefaults(['author', 'comments', 'date'])
            );

        return apply_filters("devanime/admin_columns", $columns, $this->slug);
    }

    public function printAdminStyles(): void
    {
        if (apply_filters('devanime/print_admin_styles', true)) {
            $screen = get_current_screen();
            if ($screen->id === 'edit-' . $this->slug) {
                echo '<style type="text/css">';
                echo apply_filters(
                    'devanime/print_admin_styles/' . $this->slug,
                    '.column-thumbnail { text-align: center; width:75px; } .column-thumbnail img{ display:block;margin: 0 auto;max-width:100%; height:auto; }'
                );
                echo '</style>';
            }
        }
    }

    public function columnContent(string $columnId, int $postId): void
    {
        if (!array_key_exists($columnId, $this->columns)) {
            return;
        }
        $filterBase = 'devanime/admin_col';
        $content = '';

        /**
         * add_filter('devanime/admin_col', 'my_func', 10, 4);
         * function my_func($content, $postId, $columnId, $postType){ return $content; }
         */
        $content = apply_filters($filterBase, $content, $postId, $columnId, $this->slug);

        /**
         * add_filter('devanime/admin_col/{{column_key}}', 'my_func', 10, 3);
         * function my_func($content, $postId, $postType){ return $content; }
         */
        $content = apply_filters($filterBase . '/' . $columnId, $content, $postId, $this->slug);

        /**
         * add_filter('devanime/admin_col/{{column_key}}/{{post_type}}', 'my_func', 10, 2);
         * function my_func($content, $postId){ return $content; }
         */
        $content = apply_filters($filterBase . '/' . $columnId . '/' . $this->slug, $content, $postId);

        if (is_array($this->columns[$columnId]) && !empty($this->columns[$columnId]['content_filter'])) {
            /**
             * add_filter('my_custom_filter_name', 'my_func', 10, 2);
             * function my_func($content, $postId){ return $content; }
             */
            $content = apply_filters($this->columns[$columnId]['content_filter'], $content, $postId);
        }

        if (is_array($content)) {
            $content = implode(' | ', $content);
        }

        echo $content;
    }

    public function sortableColumns(array $columns): array
    {
        if (!empty($this->register->args['taxonomies'])) {
            if (is_array($this->register->args['taxonomies'])) {
                foreach ($this->register->args['taxonomies'] as $taxonomy) {
                    $columns['taxonomy-' . $taxonomy] = 'taxonomy-' . $taxonomy;
                }
            } elseif (is_string($this->register->args['taxonomies'])) {
                $columns['taxonomy-' . $this->register->args['taxonomies']] = 'taxonomy-' . $this->register->args['taxonomies'];
            }
        }

        foreach ($this->columns as $key => $column) {
            if (!empty($column['sortable'])) {
                $columns[$key] = $key;
            }
        }

        return $columns;
    }
}
