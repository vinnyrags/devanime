<?php

namespace DevAnime\Controller;

use DevAnime\Register;

/**
 * Class RegisterConfigController
 * @package DevAnime\Controller
 */
class RegisterConfigController
{
    protected array $default_config_paths = [
        'config_files' => 'config',
        'acf_paths' => 'acf-json'
    ];
    protected $config_files = [];
    protected $data;

    function __construct() {
        add_action('after_setup_theme', [$this, 'config'], 100);
        add_action('init', [$this, 'initConfig'], 5);
    }

    public function config()
    {
        $config_files = [];
        foreach (apply_filters('devanime/default_config_paths', $this->default_config_paths) as $type => $path) {
            $should_glob = substr($type, -6) == '_files';
            $parent_path = get_template_directory() . '/' . $path;
            $files = $should_glob ? glob($parent_path . '/*.json') : [$parent_path];
            // TODO test this
            if (is_child_theme()) {
                $child_path = get_stylesheet_directory() . '/' . $path;
                $files = array_merge($files, $should_glob ? glob($child_path . '/*.json') : [$child_path]);
            }
            $config_files[$type] = $files;
        }
        new Register\RegisterConfig($config_files);
    }

    public function initConfig() {
        $this->config_files = array_values(array_filter(apply_filters('devanime/register_config', []), 'file_exists'));
        if (!empty($this->config_files)) {
            $this->data = $this->applyFilters(
                $this->compileFileData([
                    'options'           => [],
                    'custom_post_types' => [],
                    'taxonomies'        => []
                ])
            );
        }
    }

    protected function compileFileData($data) {
        foreach ($this->config_files as $file) {
            $import = json_decode(file_get_contents($file), true);
            foreach ($data as $key => $value) {
                if (isset($import[$key])) {
                    $data[$key] = array_merge($data[$key], $import[$key]);
                }
            }
        }

        return $data;
    }

    protected function applyFilters($data) {
        foreach ($data as $type => $list) {
            foreach ($list as $key => $value) {
                $list[$key] = apply_filters('devanime/config', $value, $key, $type);
                switch ($type) {
                    case 'options':
                        new Register\RegisterOption($value);
                        break;
                    case 'custom_post_types':
                        new Register\RegisterPostType($key, $value);
                        break;
                    case 'taxonomies':
                        new Register\RegisterTaxonomy($key, $value);
                        break;
                }
            }
            $data[$type] = array_filter($list);
        }

        return $data;
    }
}