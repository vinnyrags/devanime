<?php

namespace DevAnime\Rest\Controller;

/**
 * Class PageRestController
 * @package DevAnime\Rest\Controller
 */
class RestPageController
{
    public const REST_FIELD_TYPE = 'page';
    public const REST_FIELD_NAME = 'frontend';

    /**
     * Constructor method to initialize the class and register the REST field.
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerFrontendField']);
    }

    /**
     * Registers the REST field for the specified post type.
     */
    public function registerFrontendField(): void
    {
        register_rest_field(
            self::REST_FIELD_TYPE,
            self::REST_FIELD_NAME,
            [
                'get_callback' => [$this, 'convertShortcodesToJson'],
                'update_callback' => null,
                'schema' => null,
            ]
        );
    }

    /**
     * Retrieves the frontend field data for the REST API response.
     *
     * @param array $object The REST API response data.
     * @param string $field_name The name of the field.
     * @param \WP_REST_Request $request The REST API request.
     *
     * @return mixed The formatted frontend field data.
     */
    public function convertShortcodesToJson($object, $field_name, $request)
    {
        $data = $object['content']['raw'];
        return $this->parseNestedShortcodes($data);
    }

    function parseNestedShortcodes($content)
    {
        // Remove opening and closing <p> tags
        $content = preg_replace('/^<p>/', '', $content);
        $content = preg_replace('/<\/p>$/', '', $content);

        $pattern = '/\[(\w+)(.*?)\](.*?)\[\/\1\]/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $tag = $match[1];
            $attributes_str = $match[2];
            $content_str = $match[3];

            // Parse attributes into an associative array
            preg_match_all('/(\w+)="([^"]*)"/', $attributes_str, $attribute_matches, PREG_SET_ORDER);
            $attributes = [];
            foreach ($attribute_matches as $attribute_match) {
                $attributes[$attribute_match[1]] = $attribute_match[2];
            }

            // Recursively parse nested shortcodes
            $parsed_content = $this->parseNestedShortcodes($content_str);

            // Add shortcode details to result
            $shortcode_details = [
                "tag" => $tag,
                "attributes" => $attributes,
                "content" => $parsed_content ?: $content_str // If no nested shortcodes, use the content string
            ];
            $result[] = $shortcode_details;
        }

        return $result;
    }
}
