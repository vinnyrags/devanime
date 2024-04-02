<?php

namespace DevAnime\Option;

use DevAnime\Model\OptionsBase;

/**
 * Class ConfigSettingsOptions
 * @package DevAnimne\Option
 *
 * @method static colorPrimary()
 * @method static colorPrimaryLight()
 * @method static colorPrimaryDark()
 * @method static colorSecondary()
 * @method static colorSecondaryLight()
 * @method static colorSecondaryDark()
 * @method static colorTertiary()
 * @method static colorTertiaryLight()
 * @method static colorTertiaryDark()
 * @method static colorGray()
 * @method static colorGrayLight()
 * @method static colorGrayDark()
 * @method static colorOffWhite()
 * @method static colorWhite()
 * @method static colorBlack()
 * @method static spacingLayout()
 * @method static spacingComponent()
 * @method static containerMaxWidth()
 * @method static containerWideWidth()
 * @method static containerNarrowWidth()
 * @method static bodyColor()
 * @method static bodyColorInverted()
 * @method static bodyBgColor()
 * @method static bodyFontSize()
 * @method static headingDefault()
 * @method static headingMedium()
 * @method static headingLarge()
 * @method static headingXlarge()
 * @method static buttonDefault()
 * @method static textSmall()
 * @method static textLarge()
 * @method static textXlarge()
 * @method static vcComponentBgColors()
 */
class ConfigSettingsOptions extends OptionsBase
{
    protected array $defaultValues = [
        'color_primary' => '',
        'color_primary_light' => '',
        'color_primary_dark' => '',
        'color_secondary' => '',
        'color_secondary_light' => '',
        'color_secondary_dark' => '',
        'color_tertiary' => '',
        'color_tertiary_light' => '',
        'color_tertiary_dark' => '',
        'color_gray' => '',
        'color_gray_light' => '',
        'color_gray_dark' => '',
        'color_off_white' => '',
        'color_white' => '',
        'color_black' => '',
        'spacing_layout' => '',
        'spacing_component' => '',
        'container_max_width' => '',
        'container_wide_width' => '',
        'container_narrow_width' => '',
        'body_color' => '',
        'body_color_inverted' => '',
        'body_bg_color' => '',
        'body_font_size' => [
            'min' => '',
            'max' => ''
        ],
        'heading_default' => [
            'min' => '',
            'max' => ''
        ],
        'heading_medium' => [
            'min' => '',
            'max' => ''
        ],
        'heading_large' => [
            'min' => '',
            'max' => ''
        ],
        'heading_xlarge' => [
            'min' => '',
            'max' => ''
        ],
        'button_default' => [
            'min' => '',
            'max' => ''
        ],
        'text_small' => [
            'min' => '',
            'max' => ''
        ],
        'text_large' => [
            'min' => '',
            'max' => ''
        ],
        'text_xlarge' => [
            'min' => '',
            'max' => ''
        ],
        'vc_component_bg_colors' => []
    ];
}
