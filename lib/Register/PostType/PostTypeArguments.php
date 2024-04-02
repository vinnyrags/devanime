<?php

namespace DevAnime\Register\PostType;

use DevAnime\Support\Util;

/**
 * Class PostTypeArguments
 * @package DevAnime\Register\PostType
 */
class PostTypeArguments
{
    public array $labels;
    public array $args;

    public function __construct(array $labels, array $args)
    {
        $this->labels = wp_parse_args($labels, $this->getDefaultLabels($labels));
        $this->args = $args;
        $this->args['labels'] = $this->labels;
    }

    private function getDefaultLabels(array $labels): array
    {
        $singular = $labels['singular_name'];
        $plural = $labels['name'] ?? Util::pluralize($singular);
        $featured_image = $labels['featured_image'] ?? 'Featured Image';

        return [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'name_admin_bar' => $singular,
            'add_new' => 'Add New',
            'add_new_item' => sprintf('Add New %s', $singular),
            'edit_item' => sprintf('Edit %s', $singular),
            'new_item' => sprintf('New %s', $singular),
            'view_item' => sprintf('View %s', $singular),
            'search_items' => sprintf('Search %s', $plural),
            'not_found' => sprintf('No %s found.', strtolower($plural)),
            'not_found_in_trash' => sprintf('No %s found in trash.', strtolower($plural)),
            'parent_item_colon' => sprintf('Parent %s:', $singular),
            'all_items' => sprintf('All %s', $plural),
            'archives' => sprintf('%s Archives', $singular),
            'insert_into_item' => sprintf('Insert into %s', strtolower($singular)),
            'uploaded_to_this_item' => sprintf('Uploaded to this %s', strtolower($singular)),
            'filter_items_list' => sprintf('Filter %s list', strtolower($plural)),
            'items_list_navigation' => sprintf('%s list navigation', $plural),
            'items_list' => sprintf('%s list', $plural),
            "featured_image" => $featured_image,
            "set_featured_image" => sprintf('Set %s', strtolower($featured_image)),
            "remove_featured_image" => sprintf('Remove %s', strtolower($featured_image)),
            "use_featured_image" => sprintf('Use %s', strtolower($featured_image))
        ];
    }
}
