<?php

namespace DevAnime\Register;

use DevAnime\Register\Taxonomy\TaxonomyArguments;

/**
 * Class RegisterTaxonomy
 * @package DevAnime\Register
 */
class RegisterTaxonomy
{
    protected string $slug;
    protected array $post_types;
    protected TaxonomyArguments $register;

    public function __construct(string $slug, array $data)
    {
        $this->slug = $slug;
        $this->post_types = $data['post_types'] ?? [];

        if (is_string($this->post_types)) {
            $this->post_types = [$this->post_types];
        }

        $this->register = new TaxonomyArguments($data['labels'], $data['args']);
        add_action('init', [$this, 'registerTaxonomy'], 8);
    }

    public function registerTaxonomy(): void
    {
        if (!taxonomy_exists($this->slug)) {
            register_taxonomy($this->slug, $this->post_types, $this->register->args);

            foreach ($this->post_types as $post_type) {
                register_taxonomy_for_object_type($this->slug, $post_type);
            }
        }
    }
}
