<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail\Options;

/**
 * Class CheckList
 *
 * A checklist box is similar to a menu box in that there are multiple entries presented
 * in the form of a menu. You can select and deselect items using the SPACE key. The
 * initial on/off state of each entry is specified by status. On exit, a list of the tag
 * strings of those entries that are turned on will be printed on stderr
 *
 * @package WhipTail\Options
 */
class CheckList extends BaseOption
{
    /** @var array  */
    private $list       = array();
    protected $name     = 'checklist';

    const LIST_STATUS_OFF = "OFF";
    const LIST_STATUS_ON  = "ON";

    /**
     * add item to list
     *
     * @param  string $id
     * @param  string $description
     * @param  string $status
     *
     * @throws \InvalidArgumentException
     */
    public function addToList($id, $description, $status  = self::LIST_STATUS_OFF )
    {
        if (!in_array($status, array(self::LIST_STATUS_OFF, self::LIST_STATUS_ON))) {
            throw new \InvalidArgumentException(sprintf('Invalid list status: %s', $status));
        }

        $this->list[$id] = array($description, $status);
    }

    /**
     * give al whole list at once
     *
     * should be like
     *
     * array(
     *  array("ITEM1", "Some desc", "ON"),
     *  array("ITEM2", "Some other desc", "OFF"),
     *  array("ITEM3", "Some other desc", "OFF"),
     * )
     *
     * @param array $stack
     *
     * @throws \InvalidArgumentException
     */
    public function setList(array $stack)
    {
        foreach($stack as $item) {

            if (count($item) == 3) {
                $this->addToList($item[0], $item[1], $item[2]);
            } else {
                throw new \InvalidArgumentException(sprintf("Given list item is invalid. Expected format: %s given: %s\n", print_r(array("ITEM1", "Some desc", "ON"), true), print_r($item, true)));
            }

        }
    }


    /**
     * @return string
     */
    public function getListString()
    {
        $string = '';

        foreach ($this->list as $id => $data) {
            $string .= sprintf(' "%s" "%s" %s', $id, $data[0], $data[1]);
        }

        return $string;
    }


    /**
     * Method is called to get the full command back
     */
    public function getArguments()
    {
        return sprintf(
            "--%s %s \"%s\" %s %s %s %s",
            $this->name,
            $this->getBoxOptionToString(),
            $this->message,
            $this->height,
            $this->width,
            $this->height - 8,
            $this->getListString()
        );
    }

}