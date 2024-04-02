<?php

namespace DevAnime\Vendor\VisualComposer\Support;

/**
 * Trait BackgroundContainerTrait
 * @package DevAnime\Vendor\VisualComposer\Support
 */
trait BackgroundContainerTrait
{
    protected function appendBackgroundContainerConfig(array $config): array
    {
        return array_merge($config, [
            $this->generatePaddingConfig('top_pad', sprintf('%s Top Padding', static::NAME)),
            $this->generatePaddingConfig('bottom_pad', sprintf('%s Bottom Padding', static::NAME)),
        ]);
    }

    private function generatePaddingConfig(string $paramName, string $heading): array
    {
        return [
            'type' => 'dropdown',
            'heading' => $heading,
            'param_name' => $paramName,
            'edit_field_class' => 'vc_col-xs-6',
            'value' => [
                'Default' => '',
                'Double' => "{$paramName}-double",
                'Half' => "{$paramName}-half",
                'None' => "{$paramName}-none",
            ],
            'description' => 'Set top/bottom inner padding',
            'group' => 'Background'
        ];
    }
}
