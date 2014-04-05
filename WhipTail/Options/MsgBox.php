<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail\Options;

/**
 * Class MsgBox
 *
 * A message box is very similar to a yes/no box. The only difference between a message
 * box and a yes/no box is that a message box has only a single OK button. You can use
 * this dialog box to display any message you like. After reading the message, the user
 * can press the ENTER key so that whiptail will exit and the calling shell script can
 * continue its operation.
 *
 * @package WhipTail\Options
 */
class MsgBox extends BaseOption
{
    protected $name = 'msgbox';
}