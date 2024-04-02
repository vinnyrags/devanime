<?php

namespace DevAnime\Model;

use DevAnime\Support\Singleton;
use DevAnime\Support\Util;

/**
 * Class OptionsBase
 * @package DevAnime\Model
 *
 * Usage (ex. for options key 'event_date_format'):
 *
 * All method class are static proxies passed to singleton's magic __call()
 *
 * OptionsBase::get('event_date_format') // direct
 * or
 * OptionsBase::get('event_date_format', 'Y-m-d') // with default value
 * or
 * OptionsBase::eventDateFormat('Y-m-d'); //magic method
 *
 * Implement specific accessor methods for custom computed behavior, using the "get" prefix,
 * set as protected to continue passing through __get()'s internal cache, and add static call to PHPDoc
 * Ex:
 *
 * <code>
 * /**
 *  * {@method static eventDateFormat()}
 * {@*}
 * class EventOptions extends OptionsBase
 * {
 *     protected function getEventDateFormat($default_value = 'Y-m-d')
 *     {
 *         $value = $this->get('event_date_format', $default_value);
 *         //do something with $value
 *         return $value;
 *     }
 * }
 * </code>
 */
class OptionsBase
{
    protected array $options;

    /**
     * @var array Preset default values (option key => default value)
     */
    protected array $defaultValues = [];

    use Singleton;

    /**
     * Retrieves an option value by key.
     *
     * @param string $name The name of the option.
     * @param mixed $defaultValue The default value to return if the option does not exist.
     * @return mixed The value of the option.
     */
    protected function get(string $name, $defaultValue = null)
    {
        $value = get_field($name, 'option');

        if (isset($defaultValue) && empty($value)) {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * Converts to underscored option key.
     *
     * @param string $name The name of the method.
     * @param array $arguments The method arguments.
     * @return string The key derived from the method name and arguments.
     * @throws \InvalidArgumentException If an invalid option name is specified.
     */
    protected function getKeyFromCalledMethod(string $name, array $arguments): string
    {
        if ($name !== 'get') { // normal accessor method called
            $key = Util::toSnakeCase($name);
        } elseif (isset($arguments[0])) { // direct "get" call
            $key = array_shift($arguments);
        } else {
            throw new \InvalidArgumentException('Invalid option name specified');
        }

        return empty($arguments) ? $key : sprintf('%s-%s', $key, md5(serialize($arguments)));
    }

    /**
     * Checks if the method call is valid.
     *
     * @param string $name The name of the method.
     * @return bool True if the method call is valid, false otherwise.
     */
    protected function isValidMethodCall(string $name): bool
    {
        return method_exists($this, $name) && strpos($name, 'get') === 0;
    }

    /**
     * Magic method to handle calls to inaccessible methods.
     *
     * @param string $name The name of the method.
     * @param array $arguments The method arguments.
     * @return mixed The result of the method call.
     */
    public function __call(string $name, array $arguments)
    {
        $key = $this->getKeyFromCalledMethod($name, $arguments);
        if (!isset($this->options[$key])) {
            // transform accessor method call into direct "get" call
            if ($name !== 'get') {
                $getterName = 'get' . ucfirst($name);
                if (method_exists($this, $getterName)) {
                    $name = $getterName;
                } else {
                    $name = 'get';
                    array_unshift($arguments, $key);
                }
            }
            $this->options[$key] = call_user_func_array([$this, $name], $arguments);
        }
        return $this->options[$key];
    }

    /**
     * Magic method to handle static calls to inaccessible methods.
     *
     * @param string $name The name of the method.
     * @param array $arguments The method arguments.
     * @return mixed The result of the static method call.
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return call_user_func_array([self::getInstance(), $name], $arguments);
    }
}
