<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail\Options;

/**
 * Class YesNo
 *
 * A yes/no dialog box of size height rows by width columns will be displayed. The string specified
 * by text is displayed inside the dialog box. If this string is too long to be fit in one line,
 * it will be automatically divided into multiple lines at appropriate places. The text string may
 * also contain the sub-string "\n" or newline characters '\n' to control line breaking explicitly.
 * This dialog box is useful for asking questions that require the user to answer either yes or no.
 * The dialog box has a Yes button and a No button, in which the user can switch between by pressing
 * the TAB key
 *
 * @package WhipTail\Options
 */
class YesNo extends BaseOption
{
    protected $name = 'yesno';
}