<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

/**
 * Class InfoBox
 *
 * An info box is basically a message box. However, in this case,
 * whiptail will exit immediately after displaying the message to
 * the user. The screen is not cleared when whiptail exits, so
 * that the message will remain on the screen until the calling
 * shell script clears it later. This is useful when you want to
 * inform the user that some operations are carrying on that may
 * require some time to finish.
 *
 * @package PBergman\WhipTail\Options
 */
class InfoBox extends BaseOption
{
    protected $name = 'infobox';
}