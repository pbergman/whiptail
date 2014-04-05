<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail\Options;

/**
 * Class TextBox
 *
 * A text box lets you display the contents of a text file in a dialog box. It is like a simple text file viewer.
 * The user can move through the file by using the UP/DOWN, PGUP/PGDN and HOME/END keys available on most keyboards.
 * If the lines are too long to be displayed in the box, the LEFT/RIGHT keys can be used to scroll the text region
 * horizontally. For more convenience, forward and backward searching functions are also provided.
 *
 * @package WhipTail\Options
 */
class TextBox extends BaseOption
{
    protected $file = null;
    protected $name = 'textbox';
    /**
     * @return null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param null $file
     *
     * @throws \InvalidArgumentException
     */
    public function setFile($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('Could not find file: %s', $file));
        }

        $this->file = $file;
    }

    /**
     * Method is called to get the arguments
     *
     * @throws \Exception
     */
    public function getArguments()
    {
        if (empty($this->file)) {
            throw new \Exception('No file defined, use setFile() first.');
        }

        return sprintf(
            "--%s %s \"%s\" %s %s",
            $this->name,
            $this->getBoxOptionToString(),
            $this->file,
            $this->height,
            $this->width
        );
    }
}