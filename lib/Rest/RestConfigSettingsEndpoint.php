<?php

namespace DevAnime\Rest;

use DevAnime\Option\ConfigSettingsOptions;
use DevAnime\Rest\Support\RestEndpoint;

/**
 * Class RestConfigSettingsEndpoint
 * @package DevAnime\Rest
 */
class RestConfigSettingsEndpoint extends RestEndpoint
{
    protected string $namespace = 'wp/v2';

    public function registerRoutes()
    {
        $this->addReadRoute('/config', [$this, 'getConfigSettings']);
    }

    public function getConfigSettings(): array
    {
        return array_filter(array_merge(
            $this->getColors(),
            $this->getSpacing(),
            $this->getContainer(),
            $this->getBody(),
            $this->getHeading(),
            $this->getButton(),
            $this->getText(),
            $this->getVendor()
        ));
    }

    protected function getColors(): array
    {
        $colors = [];
        if (!empty($primary = ConfigSettingsOptions::colorPrimary())) {
            $colors['primary'] = $primary;
        }
        if (!empty($primaryLight = ConfigSettingsOptions::colorPrimaryLight())) {
            $colors['primary-light'] = $primaryLight;
        }
        if (!empty($primaryDark = ConfigSettingsOptions::colorPrimaryDark())) {
            $colors['primary-dark'] = $primaryDark;
        }
        if (!empty($secondary = ConfigSettingsOptions::colorSecondary())) {
            $colors['secondary'] = $secondary;
        }
        if (!empty($secondaryLight = ConfigSettingsOptions::colorSecondaryLight())) {
            $colors['secondary-light'] = $secondaryLight;
        }
        if (!empty($secondaryDark = ConfigSettingsOptions::colorSecondaryDark())) {
            $colors['secondary-dark'] = $secondaryDark;
        }
        if (!empty($tertiary = ConfigSettingsOptions::colorTertiary())) {
            $colors['tertiary'] = $tertiary;
        }
        if (!empty($tertiaryLight = ConfigSettingsOptions::colorTertiaryLight())) {
            $colors['tertiary-light'] = $tertiaryLight;
        }
        if (!empty($tertiaryDark = ConfigSettingsOptions::colorTertiaryDark())) {
            $colors['tertiary-dark'] = $tertiaryDark;
        }
        if (!empty($gray = ConfigSettingsOptions::colorGray())) {
            $colors['gray'] = $gray;
        }
        if (!empty($grayLight = ConfigSettingsOptions::colorGrayLight())) {
            $colors['gray-light'] = $grayLight;
        }
        if (!empty($grayDark = ConfigSettingsOptions::colorGrayDark())) {
            $colors['gray-dark'] = $grayDark;
        }
        if (!empty($offWhite = ConfigSettingsOptions::colorOffWhite())) {
            $colors['off-white'] = $offWhite;
        }
        if (!empty($white = ConfigSettingsOptions::colorWhite())) {
            $colors['white'] = $white;
        }
        if (!empty($black = ConfigSettingsOptions::colorBlack())) {
            $colors['black'] = $black;
        }
        return $colors;
    }

    protected function getSpacing(): array
    {
        $spacing = [];
        if (!empty($spacingLayout = ConfigSettingsOptions::spacingLayout())) {
            $spacing['spacing-layout'] = $spacingLayout;
        }
        if (!empty($spacingComponent = ConfigSettingsOptions::spacingComponent())) {
            $spacing['spacing-component'] = $spacingComponent;
        }
        return $spacing;
    }

    protected function getContainer(): array
    {
        $container = [];
        if (!empty($maxWidth = ConfigSettingsOptions::containerMaxWidth())) {
            $container['container-max-width'] = $maxWidth;
        }
        if (!empty($wideWidth = ConfigSettingsOptions::containerWideWidth())) {
            $container['container-wide-width'] = $wideWidth;
        }
        if (!empty($narrowWidth = ConfigSettingsOptions::containerNarrowWidth())) {
            $container['container-narrow-width'] = $narrowWidth;
        }
        return $container;
    }

    protected function getBody(): array
    {
        $body = [];
        if (!empty($color = ConfigSettingsOptions::bodyColor())) {
            $body['body-color'] = $color;
        }
        if (!empty($colorInverted = ConfigSettingsOptions::bodyColorInverted())) {
            $body['body-color-inverted'] = $colorInverted;
        }
        if (!empty($bgColor = ConfigSettingsOptions::bodyBgColor())) {
            $body['body-bg-color'] = $bgColor;
        }
        if (!empty($fontSize = ConfigSettingsOptions::bodyFontSize())) {
            $body['body-font-size'] = $fontSize;
        }
        return $body;
    }

    protected function getHeading(): array
    {
        $heading = [];
        if (!empty($headingDefault = ConfigSettingsOptions::headingDefault())) {
            $heading['heading-default'] = $headingDefault;
        }
        if (!empty($headingMedium = ConfigSettingsOptions::headingMedium())) {
            $heading['heading-medium'] = $headingMedium;
        }
        if (!empty($headingLarge = ConfigSettingsOptions::headingLarge())) {
            $heading['heading-large'] = $headingLarge;
        }
        if (!empty($headingXlarge = ConfigSettingsOptions::headingXlarge())) {
            $heading['heading-xlarge'] = $headingXlarge;
        }
        return $heading;
    }

    protected function getButton(): array
    {
        $button = [];
        if (!empty($fontSize = ConfigSettingsOptions::buttonDefault())) {
            $button['button-font-size'] = $fontSize;
        }
        return $button;
    }

    protected function getText(): array
    {
        $text = [];
        if (!empty($textSmall = ConfigSettingsOptions::textSmall())) {
            $text['text-small'] = $textSmall;
        }
        if (!empty($textLarge = ConfigSettingsOptions::textLarge())) {
            $text['text-large'] = $textLarge;
        }
        if (!empty($textXlarge = ConfigSettingsOptions::textXlarge())) {
            $text['text-xlarge'] = $textXlarge;
        }
        return $text;
    }

    protected function getVendor(): array
    {
        $vendor = [];
        if (!empty($vcComponentBgColors = ConfigSettingsOptions::vcComponentBgColors())) {
            $vendor['vc-component-bg-colors'] = $vcComponentBgColors;
        }
        return $vendor;
    }
}
