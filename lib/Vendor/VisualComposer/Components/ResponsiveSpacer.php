<?php

namespace DevAnime\Vendor\VisualComposer\Components;

use DevAnime\Vendor\VisualComposer\Support\Component;

/**
 * Class ResponsiveSpacer
 * @package DevAnime\Vendor\VisualComposer\Components
 */
class ResponsiveSpacer extends Component
{
    const NAME = 'Responsive Spacer';
    const TAG = 'responsive_spacer';

    protected array $componentConfig = [
        'description' => 'Add responsive space above or below a component, row or section.',
        'icon' => 'icon-wpb-toggle-small-expand',
        'wrapper_class' => 'clearfix',
        'is_container' => false,
        'category' => 'Content',
        'params' => [
            'factor' => [
                'type' => 'dropdown',
                'heading' => 'Layout Spacing Factor',
                'param_name' => 'factor',
                'value' => [
                    '-- Select Factor -- ' => '',
                    '0.25x' => '0_25',
                    '0.5x' => '0_5',
                    '1x' => '',
                    '1.5x' => '1_5',
                    '2x' => '2',
                    '3x' => '3',
                    '4x' => '4',
                    '5x' => '5',
                    '6x' => '6',
                    '7x' => '7',
                    '8x' => '8',
                    '9x' => '9',
                    '10x' => '10',
                ],
                'description' => 'Multiplier of default layout spacing (width between columns)',
                'admin_label' => true
            ],
            [
                'type' => 'textfield',
                'heading' => 'Extra class name',
                'param_name' => 'el_class'
            ]
        ]
    ];

    protected function setupConfig(): void
    {
        parent::setupConfig();
        $this->componentConfig['params']['factor']['value'] = apply_filters(
            'visual_composer/responsive-spacer-factors',
            $this->componentConfig['params']['factor']['value']
        );
    }
}
