<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

/**
 * Class PasswordBox
 *
 * A password box is similar to an input box, except the text the user enters is not displayed.
 * This is useful when prompting for passwords or other sensitive information. Be aware that if
 * anything is passed in "init", it will be visible in the system's process table to casual
 * snoopers. Also, it is very confusing to the user to provide them with a default password they
 * cannot see. For these reasons, using "init" is highly discouraged.
 *
 * @package PBergman\WhipTail\Options
 */
class PasswordBox extends BaseOption
{
    /** @var string  */
    protected $name    = 'passwordbox';
    /** @var null  */
    protected $default = null;

    /**
     * Method is called to get the arguments
     */
    public function getArguments()
    {
        return sprintf(
            "--%s %s \"%s\" %s %s %s",
            $this->name,
            $this->getBoxOptionToString(),
            $this->message,
            $this->height,
            $this->width,
            $this->default
        );
    }


}