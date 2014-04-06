<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail\Options;

use WhipTail\Helpers\Progress;

/**
 * Class gauge
 *
 * A gauge box displays a meter along the bottom of the box. The meter indicates a percentage.
 * New percentages are read from standard input, one integer per line. The meter is updated to
 * reflect each new percentage. If stdin is XXX, then subsequent lines up to another XXX are
 * used for a new prompt. The gauge exits when EOF is reached on stdin.
 *
 * @package WhipTail\Options
 */
class gauge extends BaseOption
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
        if (!is_resource($stdin)) {
            throw new \Exception('Given argument needs to be a valid resource');
        }

        $advance  = round(100/count($this->callBacks));
        $current  = 0;
        $progress = new Progress();
        $progress->setStdin($stdin);

        foreach ($this->callBacks as $id => $callBack) {

            $progress->reset()
                     ->setCurrent($current)
                     ->setLimit($current + $advance);

            array_unshift($callBack[1], $progress);

            if( false !== call_user_func_array($callBack[0], $callBack[1])){

                $current += $advance;

                if ($id == (count($this->callBacks) - 1)){

                    fwrite($stdin, "100\n");

                    if (is_resource($stdin)) {
                        fclose($stdin);
                    }

                } else {
                    fwrite($stdin, "$current\n");
                }

            } else {

                if (is_resource($stdin)) {
                    fclose($stdin);
                }

                throw new \Exception('Failed to run callback');

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
     * @param callable  $callBack
     * @param mixed     $arguments
     *
     * @return $this
     */
    public function addCallBack(callable $callBack, $arguments = array())
    {
        $this->callBacks[] = array($callBack, $arguments);

        return $this;
    }

}