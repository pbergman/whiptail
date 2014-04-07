<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

/**
 * Class InputBox
 *
 * An input box is useful when you want to ask questions that require the user
 * to input a string as the answer. If init is supplied it is used to initialize
 * the input string. When inputing the string, the BACKSPACE key can be used to
 * correct typing errors. If the input string is longer than the width of the
 * dialog box, the input field will be scrolled. On exit, the input string will
 * be printed on stderr.
 *
 * @package PBergman\WhipTail\Options
 */
class InputBox extends BaseOption
{
    /** @var string  */
    protected $name    = 'inputbox';
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