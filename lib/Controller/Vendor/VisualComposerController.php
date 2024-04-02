<?php

namespace DevAnime\Controller\Vendor;

use DevAnime\Vendor\VisualComposer\Controller;
use DevAnime\Vendor\VisualComposer\Register;

/**
 * class VisualComposerController
 * @package DevAnime\Controller\Vendor
 */
class VisualComposerController
{
    protected array $controllers = [
        Controller\BootstrapVersionController::class,
        Controller\ComponentAttributesController::class,
    ];

    public function __construct()
    {
        if (function_exists('vc_map')) {
            add_action('vc_before_init', function () {
                vc_set_as_theme();
                vc_disable_frontend();
                new Register\UnregisterComponents();
            });

            new Register\RegisterComponents();

            foreach ($this->controllers as $Controller) {
                new $Controller();
            }
        }
    }
}