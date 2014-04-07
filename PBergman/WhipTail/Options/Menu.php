<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

/**
 * Class Menu
 *
 * As its name suggests, a menu box is a dialog box that can be used to present a
 * list of choices in the form of a menu for the user to choose. Each menu entry
 * consists of a tag string and an item string. The tag gives the entry a name to
 * distinguish it from the other entries in the menu. The item is a short description
 * of the option that the entry represents. The user can move between the menu
 * entries by pressing the UP/DOWN keys, the first letter of the tag as a hot-key.
 * There are menu-height entries displayed in the menu at one time, but the menu will
 * be scrolled if there are more entries than that. When whiptail exits, the tag of
 * the chosen menu entry will be printed on stderr.
 *
 * @package PBergman\WhipTail\Options
 */
class Menu extends BaseOption
{
    /** @var array  */
    private $list       = array();
    protected $name     = 'menu';

    /**
     * add item to list
     *
     * @param  string $id
     * @param  string $description
     *
     */
    public function addToList($id, $description)
    {

        $this->list[$id] = array($description);
    }

    /**
     * give al whole list at once
     *
     * should be like
     *
     * array(
     *  array("ITEM1", "Some desc"),
     *  array("ITEM2", "Some other desc"),
     *  array("ITEM3", "Some other desc"),
     * )
     *
     * @param array $stack
     *
     * @throws \InvalidArgumentException
     */
    public function setList(array $stack)
    {
        foreach($stack as $item) {

            if (count($item) == 2) {
                $this->addToList($item[0], $item[1]);
            } else {
                throw new \InvalidArgumentException(sprintf("Given list item is invalid. Expected format: %s given: %s\n", print_r(array("ITEM1", "Some desc"), true), print_r($item, true)));
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
            $string .= sprintf(' "%s" "%s"', $id, $data[0]);
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