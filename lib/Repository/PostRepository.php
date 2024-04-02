<?php

namespace DevAnime\Repository;

use DevAnime\Model\Field\Field;
use DevAnime\Model\Post\PostBase;
use DevAnime\Model\Post\PostCollection;

/**
 * Class PostRepository
 * @package DevAnime\Repository
 */
class PostRepository implements Repository
{
    protected string $modelClass = PostBase::class;
    protected array $fieldIds = [];
    protected bool $excludeCurrentSingularPost = true;
    protected string $collectionClass = PostCollection::class;

    public function findById(int $id): ?PostBase
    {
        return call_user_func([$this->modelClass, 'create'], $id);
    }

    public function findOne(array $query): ?PostBase
    {
        $query['posts_per_page'] = 1;
        $posts = $this->find($query);
        return $posts[0] ?? null;
    }

    public function findOneBySlug(string $name): ?PostBase
    {
        return $this->findOne(compact('name'));
    }

    public function findOneByAuthor($author): ?PostBase
    {
        if (is_object($author)) $author = $author->ID;
        return $this->findOne(compact('author'));
    }

    public function findAllByAuthor($author): array
    {
        if (is_object($author)) $author = $author->ID;
        return $this->find(compact('author'));
    }

    public function findAll(bool $anyStatus = false): array
    {
        $status = $anyStatus ? 'any' : 'publish';
        return $this->find(['posts_per_page' => -1, 'post_status' => $status]);
    }

    public function findAllDrafts(): array
    {
        return $this->find(['posts_per_page' => -1, 'post_status' => 'draft']);
    }

    public function find(array $query): array
    {
        $modelClass = $this->modelClass;
        $query = $this->maybeExcludeCurrentSingularPost($query);
        $posts = array_filter($modelClass::getPosts($query));
        $collectionClass = $this->collectionClass;
        return new $collectionClass($posts);
    }

    public function findWithIds(array $postIds, int $count = 10): array
    {
        if (empty($postIds)) {
            return [];
        }
        return $this->find([
            'posts_per_page' => $count,
            'post__in' => $postIds,
            'orderby' => 'post__in'
        ]);
    }

    /**
     * @param array $termIds
     * @param string $taxonomy
     * @param int $count
     * @param array $excludedPostIds
     * @return array
     */
    public function findWithTermIds(array $termIds, string $taxonomy = 'category', int $count = 10, array $excludedPostIds = []): array
    {
        if (empty($termIds)) {
            return [];
        }
        return $this->find([
            'posts_per_page' => $count,
            'post__not_in' => $excludedPostIds,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'terms' => $termIds,
                    'field' => 'term_id',
                    'compare' => 'IN'
                ]
            ]
        ]);
    }

    public function add(PostBase $Post): bool|\WP_Error
    {
        $this->checkBoundModelType($Post);
        $postArr = (array) $Post->post();
        $postId = $Post->ID ? wp_update_post($postArr, true) : wp_insert_post($postArr, true);
        if (is_wp_error($postId)) {
            return $postId;
        }
        $Post->ID = $postId;
        foreach ($Post->allTermIdsByTaxonomy() as $taxonomy => $term_ids) {
            wp_set_object_terms($Post->ID, $term_ids, $taxonomy);
        }
//        $this->addFeaturedImage($Post);
        $this->addFields($Post);
        $Post->reset(true);
        return true;
    }

    public function remove(PostBase $Post): bool
    {
        $this->checkBoundModelType($Post);
        $result = wp_delete_post($Post->ID);
        return !empty($result);
    }

//    protected function addFeaturedImage(PostBase $post): void
//    {
//        $featured_image = $post->featuredImage();
//        if ($featured_image) {
//            set_post_thumbnail($post->ID, $featured_image->ID);
//        } else {
//            delete_post_thumbnail($post->ID);
//        }
//    }

    protected function addFields(PostBase $Post): void
    {
        foreach ($Post->fields(false) as $key => $value) {
            $fieldIds = $this->getFieldIds($Post);
            $value = $this->prepareFieldValue($key, $value);
            if (isset($fieldIds[$key])) {
                update_field($fieldIds[$key], $value, $Post->ID);
            } else {
                update_post_meta($Post->ID, $key, $value);
            }
        }
    }

    protected function getFieldIds(PostBase $Post): array
    {
        if (empty($this->fieldIds)) {
            foreach (acf_get_field_groups(['post_type' => $Post::POST_TYPE]) as $group) {
                foreach (acf_get_fields($group) as $field) {
                    $this->fieldIds[$field['name']] = $field['key'];
                }
            }
        }
        return $this->fieldIds;
    }

    protected function checkBoundModelType(PostBase $Post): void
    {
        if (!is_a($Post, $this->modelClass)) {
            throw new \InvalidArgumentException('PostBase parameter is not a :' . $this->modelClass);
        }
    }

    protected function prepareFieldValue(string $key, mixed $value): mixed
    {
        if (method_exists($this, "prepare_$key")) {
            $value = $this->{"prepare_$key"}($value);
        }
        if ($value instanceof Field) {
            $value = $value->getValue();
        }
        return $value;
    }

    protected function maybeExcludeCurrentSingularPost(array $query): array
    {
        global $wp_query;
        if (!$wp_query) {
            return $query;
        }
        $modelClass = $this->modelClass;
        $PostArr = $wp_query->get_queried_object();
        if (
            $this->excludeCurrentSingularPost &&
            $wp_query->is_singular &&
            $PostArr && $PostArr->post_type == $modelClass::POST_TYPE
        ) {
            if (empty($query['post__not_in'])) {
                $query['post__not_in'] = [];
            }
            if (!is_array($query['post__not_in'])) {
                $query['post__not_in'] = [$query['post__not_in']];
            }
            array_push($query['post__not_in'], get_the_ID());
        }
        return $query;
    }
}
