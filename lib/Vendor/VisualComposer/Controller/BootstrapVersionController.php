<?php

namespace DevAnime\Vendor\VisualComposer\Controller;

use Closure;

/**
 * Class BootstrapVersionController
 * @package DevAnime\Vendor\VisualComposer\Controller
 */
class BootstrapVersionController
{
    private const V3_BREAKPOINTS = ['-lg', '-md', '-sm', '-xs'];
    private const V4_BREAKPOINTS = ['-xl', '-lg', '-md', ''];

    public function __construct()
    {
        add_action('init', fn() => add_filter('vc_shortcodes_css_class', [$this, 'filterClasses'], 10, 2));
    }

    public function filterClasses(string $class_string, string $tag): string
    {
        if ($tag === 'vc_column') {
            $class_string = preg_replace('/vc_(col|hidden-[xsmdlg]{2})/', '$1', $class_string);
            $class_string = str_replace('/', '_', $class_string);
            $class_string = preg_replace_callback('/col(-[xsmdlg]{2})-(\d{1,2})/', fn($matches) => $this->replaceV3ColumnClass('col%s-%d', $matches), $class_string);
            $class_string = preg_replace_callback('/hidden(-[xsmdlg]{2})/', $this->replaceV3DisplayClass(), $class_string);
            $class_string = preg_replace_callback('/col(-[xsmdlg]{2})-offset-/', fn($matches) => $this->replaceV3ColumnClass('offset%s-', $matches), $class_string);
        }
        $class_string = implode(' ', array_unique(explode(' ', $class_string)));
        return $class_string;
    }

    private function replaceV3DisplayClass(): Closure
    {
        return function (array $matches): string {
            static $already_hidden = [];
            $hidden = $this->replaceV3Breakpoint($matches[1]);
            array_push($already_hidden, $hidden);
            $classes = sprintf('d%s-none', $hidden);
            $index = array_search($hidden, self::V4_BREAKPOINTS);
            $next_visible = $index ? self::V4_BREAKPOINTS[$index - 1] : false;
            if ($next_visible && !in_array($next_visible, $already_hidden)) {
                $classes .= sprintf(' d%s-flex', $next_visible);
            }
            return $classes;
        };
    }

    private function replaceV3ColumnClass(string $format, array $matches): string
    {
        $matches[1] = $this->replaceV3Breakpoint($matches[1]);
        array_shift($matches);
        return vsprintf($format, $matches);
    }

    private function replaceV3Breakpoint(string $breakpoint): string
    {
        return str_replace(self::V3_BREAKPOINTS, self::V4_BREAKPOINTS, $breakpoint);
    }
}
