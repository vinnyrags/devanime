<?php

namespace DevAnime\Model\Term;

/**
 * Class TermBase
 * @package DevAnime\Model\Term
 */
class TermBase
{
    public const TAXONOMY = null;
    protected static array $defaultArgs = [];
    protected array $_fields = [];
    private ?\WP_Term $_term = null;
    private $termInit;
    private ?string $_permalink = null;

    public function __construct($term)
    {
        $this->termInit = $term;
        $this->init();
    }

    protected function init(): void
    {
    }

    public function permalink(): ?string
    {
        if (empty($this->_permalink)) {
            $this->_permalink = get_term_link($this->term(), $this->taxonomy());
        }

        return $this->_permalink;
    }

    public function taxonomy(): string
    {
        return static::TAXONOMY ?: $this->term()->taxonomy;
    }

    public function field(string $selector): mixed
    {
        if (empty($this->_fields[$selector])) {
            $this->_fields[$selector] = get_field($selector, $this->getIdForField());
        }

        return $this->_fields[$selector];
    }

    public function fields(): array
    {
        $this->_fields = get_fields($this->getIdForField());

        return $this->_fields;
    }

    public function term(): \WP_Term
    {
        if (empty($this->_term)) {
            $taxonomy = $this->termInit->taxonomy ?? static::TAXONOMY;
            $this->_term = get_term($this->termInit, $taxonomy);
            unset($this->termInit);
        }

        return $this->_term;
    }

    public function isValid(): bool
    {
        return $this->term() instanceof \WP_Term;
    }

    /**
     * @param \WP_Post $post
     *
     * @return static[]
     */
    public static function getByPost(\WP_Post $post): array
    {
        $terms = get_the_terms($post, static::TAXONOMY);
        $ret = [];
        if (empty($terms) || is_wp_error($terms)) {
            return $ret;
        }
        foreach ($terms as $term) {
            $ret[] = new static($term);
        }

        return $ret;
    }

    /**
     * @param array $args
     *
     * @return static[]
     */
    public static function getTerms(array $args = []): array
    {
        $defaults = [
            'taxonomy' => static::TAXONOMY,
        ];
        $defaults = wp_parse_args(static::$defaultArgs, $defaults);
        $args = wp_parse_args($args, $defaults);
        $terms = get_terms($args);
        $ret = [];
        if (empty($terms) || is_wp_error($terms)) {
            return $ret;
        }

        foreach ($terms as $term) {
            $ret[] = new static($term);
        }

        return $ret;
    }

    protected function getIdForField(): string
    {
        return $this->taxonomy() . '_' . $this->term()->term_id;
    }
}
