<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace PBergman\WhipTail\Options;

use PBergman\WhipTail\Helpers\Progress;

/**
 * Class gauge
 *
 * A gauge box displays a meter along the bottom of the box. The meter indicates a percentage.
 * New percentages are read from standard input, one integer per line. The meter is updated to
 * reflect each new percentage. If stdin is XXX, then subsequent lines up to another XXX are
 * used for a new prompt. The gauge exits when EOF is reached on stdin.
 *
 * @package PBergman\WhipTail\Options
 */
class Gauge extends BaseOption
{
    /** @var array  */
    protected $name       = 'gauge';
    /** @var int  */
    protected $percentage = 0;
    /** @var callable|null  */
    protected $callBacks  = null;

    /**
     * Method is called to get the full command back
     */
    public function getArguments()
    {
        if (empty($this->callBacks)) {
            throw new \Exception("Nothing to do, define some callback first!");
        }

        return sprintf(
            "--%s %s \"%s\" %s %s %s",
            $this->name,
            $this->getBoxOptionToString(),
            $this->message,
            $this->height,
            $this->width,
            $this->percentage
        );
    }

    /**
     * @param resource $stdin
     *
     * @throws \Exception
     */
    public function process($stdin)
    {

        if (is_resource($stdin)) {

            $advance  = round(100/count($this->callBacks));
            $current  = 0;
            $progress = new Progress();
            $progress->setStdin($stdin);

            foreach ($this->callBacks as $id => $callBackObject) {

                list ($callBack, $arguments, $message) = $callBackObject;

                $progress->reset()
                         ->setCurrent($current)
                         ->setLimit($current + $advance);

                array_unshift($arguments, $progress);

                if (!empty($message)) {
                    $this->updateProgressStatus($current, $stdin, $message);
                }

                if( false !== call_user_func_array($callBack, $arguments)){

                    $current += $advance;

                    if ($id == (count($this->callBacks) - 1)){
                        $this->updateProgressStatus(100, $stdin, $message);
                        fclose($stdin);
                    } else {
                        $this->updateProgressStatus($current, $stdin, $message);
                    }

                } else {
                    fclose($stdin);
                    throw new \Exception('Failed to run callback');
                }
            }
        }
    }

    protected function updateProgressStatus($percentage, $stdin, $message = null)
    {
        if (is_resource($stdin)) {
            if (is_null($message)) {
                fwrite($stdin, sprintf("%d\n", $percentage));
            } else {
                fwrite($stdin, "XXX\n");
                fwrite($stdin, sprintf("%d\n", $percentage));
                fwrite($stdin, sprintf("%s\n", $message));
                fwrite($stdin, "XXX\n");
            }
        }
    }


    /**
     * @return int
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param int $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return callable|null
     */
    public function getCallBacks()
    {
        return $this->callBacks;
    }

    /**
     * @param callable      $callBack
     * @param mixed         $arguments
     * @param string|null   $message
     *
     * @return $this
     */
    public function addCallBack(callable $callBack, $arguments = array(), $message = null)
    {
        $this->callBacks[] = array($callBack, $arguments, $message);

        return $this;
    }

}