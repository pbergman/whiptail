<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

/**
 * Class RadioList
 *
 * A radiolist box is similar to a menu box. The only difference is that you can indicate
 * which entry is currently selected, by setting its status to on.
 *
 * @package PBergman\WhipTail\Options
 */
class RadioList extends BaseOption
{
    /** @var array  */
    private $list       = array();
    protected $name     = 'radiolist';

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
                throw new \InvalidArgumentException(sprintf("Given list item is invalid. Expected format: %s given: %s\n", print_r(array("ITEM1", "Some desc", "ON"), true), print_r($item, true)));            }

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