<?php

namespace DevAnime\Model\Post;

use DevAnime\Support\DateTime;
use DevAnime\Support\Util;
//use WP_Image;
use WP_Post;
use WP_Query;
use WP_Term;
use WP_User;

/**
 * Class PostBase
 * @package DevAnime\Model\Post
 */
abstract class PostBase
{
    protected const POST_TYPE = null;

    private ?int $_post_init = null;
    private ?WP_Post $_post = null;
    private ?WP_User $_author = null;
    private array $_fields = [];
    private array $_terms = [];
    protected ?string $_permalink;
//    protected ?WP_Image $_featured_image;
    protected array $_date_field_names = [];
    protected static array $default_query = [];
    protected ?string $_default_date_format;

    public function __construct($post = null)
    {
        $this->_post_init = $post;
        $this->init();
    }

    protected function init(): void
    {
    }

    public function reset($reload = false): void
    {
        $post_id = $this->post()->ID;
        $this->_post = $this->_author = $this->_permalink = $this->_featured_image = null;
        $this->_fields = $this->_terms = [];
        $this->_post_init = $post_id;
        Util::acfClearPostStore($post_id);
        if ($reload) {
            $this->post();
            $this->fields(true);
            $this->allTermIdsByTaxonomy();
            $this->permalink();
//            $this->featuredImage();
        }
        $this->init();
    }

    public function permalink(): string
    {
        if (empty($this->_permalink)) {
            $this->_permalink = get_permalink($this->post());
        }

        return $this->_permalink;
    }

    public function publishedDate(?string $default_format = null): DateTime
    {
        $format = $default_format ?: $this->_default_date_format;
        return new DateTime($this->post()->post_date, $format);
    }

    public function modifiedDate(?string $default_format = null): DateTime
    {
        $format = $default_format ?: $this->_default_date_format;
        return new DateTime($this->post()->post_modified, $format);
    }

//    public function featuredImage(): ?WP_Image
//    {
//        if (!isset($this->_featured_image) && class_exists(WP_Image::class)) {
//            $this->_featured_image = WP_Image::get_featured($this->post());
//        }
//        return $this->_featured_image;
//    }
//
//    public function setFeaturedImage(WP_Image $image): void
//    {
//        $this->_featured_image = $image;
//    }

    public function field(string $selector)
    {
        if (empty($this->_fields[$selector])) {
            $field = get_field($selector, $this->post()->ID);
            if (in_array($selector, $this->_date_field_names)) {
                $field = $field ? new DateTime($field, $this->_default_date_format) : null;
            }
            $this->_fields[$selector] = $field;
        }

        return $this->_fields[$selector];
    }

    public function fields(bool $fetch = true): array
    {
        if ($fetch) {
            $fields = get_fields($this->post()->ID);
            foreach ((array)$fields as $key => $value) {
                $this->$key;
            }
        }

        return $this->_fields;
    }

    public function post(): ?WP_Post
    {
        if (empty($this->_post)) {
            $this->_post = $this->hasValidPostInit() ?
                get_post($this->_post_init) :
                (object)['ID' => null, 'post_type' => static::POST_TYPE];
            if (!$this->isValidPostInit()) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid post initialization for post type "%s" with id: %d',
                    static::POST_TYPE,
                    $this->_post->ID
                ));
            }
            $this->_post_init = null;
        }

        return $this->_post;
    }

    protected function isValidPostInit(): bool
    {
        return $this->_post->post_type == static::POST_TYPE;
    }

    public function title(): string
    {
        return get_the_title($this->post());
    }

    public function content(bool $use_global = true): string
    {
        $content = $this->isGlobal() && $use_global ?
            get_the_content() :
            $this->post()->post_content;
        return apply_filters('the_content', $content);
    }

    public function type(): string
    {
        return static::POST_TYPE ?: $this->post()->post_type;
    }

    public function isGlobal(): bool
    {
        return isset($GLOBALS['post']) && $GLOBALS['post'] == $this->post();
    }

    public function excerpt(int $num_words = 0, bool $raw = false): string
    {
        return Util::excerpt($this->post(), $num_words, $raw);
    }

    public function author(): ?WP_User
    {
        if (empty($this->_author)) {
            $post = $this->post();
            if (!$post) return null;
            $this->_author = new WP_User($post->post_author);
        }
        return $this->_author;
    }

    public function setAuthor(WP_User $user): void
    {
        $this->_author = $user;
        $this->post()->post_author = $user->ID;
    }

    public function terms(string $taxonomy): array
    {
        if (!(isset($this->_terms[$taxonomy]) && is_array($this->_terms[$taxonomy]))) {
            $terms = get_the_terms($this->post(), $taxonomy);
            if (!is_array($terms)) $terms = [];
            $this->_terms[$taxonomy] = $terms;
        }

        return $this->_terms[$taxonomy];
    }

    public function allTermIdsByTaxonomy(): array
    {
        $taxonomies = get_object_taxonomies($this->type(), 'objects');
        foreach ($taxonomies as $name => &$term_ids) {
            $term_ids = array_map(function (WP_Term $term) {
                return $term->term_id;
            }, $this->terms($name));
        }
        return $taxonomies;
    }

    public function isValid(): bool
    {
        return $this->post() instanceof WP_Post;
    }

    protected static function getQuery(array $args = []): array
    {
        $defaults = [
            'post_type' => static::POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'offset' => 0,
            'suppress_filters' => true
        ];
        if (current_user_can('read_private_posts')) {
            $defaults['post_status'] = ['publish', 'private'];
        }
        $defaults = wp_parse_args(static::getDefaultQuery(), $defaults);
        return wp_parse_args($args, $defaults);
    }

    public static function getDefaultQuery(): array
    {
        return static::$default_query;
    }

    public static function getPosts(array $args = []): array
    {
        $args = static::getQuery($args);
        $query = new WP_Query();
        $posts = $query->query($args);
        $ret = [];
        foreach ($posts as $post_obj) {
            $ret[] = static::create($post_obj);
        }
        return $ret;
    }

    public static function create($post_obj): self
    {
        return new static($post_obj);
    }

    public static function createFromGlobal(): self
    {
        return static::create($GLOBALS['post']);
    }

    public function __get(string $name)
    {
        if ($method_name = Util::getMethodName($this, $name)) {
            if (!isset($this->_fields[$name])) {
                $value = $this->{$method_name}();
                $this->_fields[$name] = $value;
            }
            return $this->_fields[$name];
        }
        $post = $this->post();
        if (property_exists($post, $name)) return $post->{$name};

        if ($terms = $this->terms($this->getTaxonomyFromProperty($name))) return $terms;

        return $this->field($name);
    }

    public function __set(string $name, $value): void
    {
        if ($method_name = Util::getMethodName($this, $name, 'set')) {
            $this->{$method_name}($value);
            return;
        }
        if (property_exists(WP_Post::class, $name)) {
            $this->post()->{$name} = $value;
            return;
        }

        if ($taxonomy = $this->getTaxonomyFromProperty($name)) {
            $this->_terms[$taxonomy] = array_map(function ($value) use ($taxonomy) {
                return is_string($value) ?
                    get_term_by('slug', $value, $taxonomy) :
                    get_term($value, $taxonomy);
            }, (array)$value);
            return;
        }

        $this->_fields[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        $value = $this->$name;
        return !empty($value);
    }

    private function getTaxonomyFromProperty(string $name): ?string
    {
        if (taxonomy_exists($name)) return $name;

        $singular_name = Util::singularize($name);
        return taxonomy_exists($singular_name) ? $singular_name : null;
    }

    private function hasValidPostInit(): bool
    {
        return (
            is_numeric($this->_post_init) ||
            $this->_post_init instanceof WP_Post
        );
    }
}
