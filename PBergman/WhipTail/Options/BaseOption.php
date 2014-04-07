<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

/**
 * Class BaseOption
 *
 * Helper class with some basic functions
 *
 * @package PBergman\WhipTail\Options
 */
class BaseOption
{
    /** @var int  */
    protected  $width  = 70;
    /** @var int  */
    protected $height  = 20;
    /** @var string  */
    protected $message = '';
    /** @var string  */
    protected $name    = '';
    /** @var array  */
    protected $boxOptions = array(
        'clear'             => null,  //clear screen on exit
        'defaultno'         => null,  //default no button
        'default-item:'     => null,  //set default string
        'fb'                => null,  //use full buttons
        'nocancel'          => null,  //no cancel button
        'yes-button:'       => null,  //set text of yes button
        'no-button:'        => null,  //set text of no button
        'ok-button:'        => null,  //set text of ok button
        'cancel-button:'    => null,  //set text of cancel button
        'noitem'            => null,  //display tags only
        'separate-output'   => null,  //output one line at a time
        'output-fd:'        => null,  //output to fd, not stdout
        'title:'            => null,  //display title
        'backtitle:'        => null,  //display backtitle
        'scrolltext'        => null,  //force vertical scrollbars
        'topleft'           => null,  //put window in top-left corner
    );

    /**
     * will set a option
     *
     * @param string $name
     * @param bool   $value
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function setBoxOption($name, $value = true)
    {
        $optionArgumentName = $name . ':';

        if (array_key_exists($name, $this->boxOptions) || array_key_exists($optionArgumentName, $this->boxOptions)) {

            if (array_key_exists($name, $this->boxOptions)) {
                $this->boxOptions[$name] = true;
            } else {

                if (is_string($value)) {

                    $this->boxOptions[$optionArgumentName] = $value;

                } else {
                    throw new \InvalidArgumentException(sprintf('Option "%s" needs a argument'));
                }
            }

        } else {
            throw new \Exception(sprintf('Unknown option: %s', $name));
        }

        return $this;
    }

    /**
     * will return all (set) options as string
     *
     * @return string
     */
    public function getBoxOptionToString()
    {
        $options = array_filter($this->boxOptions);
        $string  = '';

        foreach ($options as $key => $value) {

            if (empty($value)) {
                break;
            } else {

                if (strrev($key)[0] == ":" ) {
                    $key     = substr($key, 0, -1);
                    $string .= sprintf(' --%s "%s"',$key, $value);
                } else {
                    $string .= sprintf(' --%s', $key);
                }
            }
        }

        return $string;
    }

    /**
     * @return array
     */
    public function getBoxOptions()
    {
        return $this->boxOptions;
    }

    /**
     * will set all option
     *
     * @param  array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Method is called to get the arguments
     */
    public function getArguments()
    {
        if (empty($this->name)) {
            throw new \Exception('Need to set a option name first!');
        }

        return sprintf("--%s %s \"%s\" %s %s", $this->name, $this->getBoxOptionToString(), $this->message, $this->height,  $this->width);
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;

    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}