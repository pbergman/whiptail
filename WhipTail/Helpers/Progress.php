<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail\Helpers;

class Progress
{
    /** @var resource */
    protected $stdin = null;
    /** @var  int */
    protected $limit = 0;
    /** @var null|int  */
    protected $start = null;
    /** @var int  */
    protected $current = 0;

    /**
     * @return resource
     */
    public function getStdin()
    {
        return $this->stdin;
    }

    /**
     * @param resource $stdin
     *
     * @return $this
     */
    public function setStdin($stdin)
    {
        $this->stdin = $stdin;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param int $current
     *
     * @return $this
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * will reset internal counters
     *
     * @return $this
     */
    public function reset()
    {
        $this->start   = null;
        $this->limit   = 0;
        $this->current = 0;

        return $this;
    }

    /**
     * will advance the progress bar by 1/100 between
     * start of this current and $limit
     *
     * @throws \Exception
     */
    public function advance()
    {
        if (!is_resource($this->stdin)) {
            throw new \Exception('need to set resource first');
        }

        if (is_null($this->start)) {
            $this->start =  $this->current;
        }

        $this->current += ($this->limit-$this->start)/100;

        fwrite($this->stdin, sprintf("%d\n", round($this->current)));

    }

}