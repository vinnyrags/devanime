<?php

namespace DevAnime\Vendor\VisualComposer\Support;

/**
 * Trait ComponentRegistrationTrait
 * @package DevAnime\Vendor\VisualComposer\Support
 */
trait ComponentRegistrationTrait
{
    protected array $componentConfig = [];
    protected int $initPriority = 10;

    protected function populateConfigOptions(): void
    {
    }

    protected function setupConfig(): void
    {
        if (defined('static::NAME')) {
            $this->componentConfig['name'] = static::NAME;
        }
        if (defined('static::TAG')) {
            $this->componentConfig['base'] = static::TAG;
        }
        $this->componentConfig['php_class_name'] = static::class;
    }

    public function register(): void
    {
        add_action('vc_before_init', function () {
            $this->setupConfig();
            if (is_admin() && wp_doing_ajax() && filter_input(INPUT_POST, 'tag') === static::TAG) {
                $this->populateConfigOptions();
            }
            vc_map($this->componentConfig);
        }, $this->initPriority);
    }
}
