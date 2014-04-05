<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

namespace WhipTail;

class Controller
{
    /** @var  Options\BaseOption */
    private $command;
    /** @var string|null  */
    private $output = null;
    /** @var int  */
    private $exitCode = 0;

    /**
     * options deceleration
     */
    const OPTION_CHECK_LIST     = 0;
    const OPTION_GAUGE          = 1;
    const OPTION_INFO_BOX       = 2;
    const OPTION_INPUT_BOX      = 3;
    const OPTION_MENU           = 4;
    const OPTION_MSG_BOX        = 5;
    const OPTION_PASSWORD_BOX   = 6;
    const OPTION_RADIO_LIST     = 7;
    const OPTION_TEXT_BOX       = 8;
    const OPTION_YES_NO         = 9;

    private $options = array(
       self::OPTION_CHECK_LIST    => 'WhipTail\Options\CheckList',
       self::OPTION_GAUGE         => 'WhipTail\Options\Gauge',
       self::OPTION_INFO_BOX      => 'WhipTail\Options\InfoBox',
       self::OPTION_INPUT_BOX     => 'WhipTail\Options\InputBox',
       self::OPTION_MENU          => 'WhipTail\Options\Menu',
       self::OPTION_MSG_BOX       => 'WhipTail\Options\MsgBox',
       self::OPTION_PASSWORD_BOX  => 'WhipTail\Options\PasswordBox',
       self::OPTION_RADIO_LIST    => 'WhipTail\Options\RadioList',
       self::OPTION_TEXT_BOX      => 'WhipTail\Options\TextBox',
       self::OPTION_YES_NO        => 'WhipTail\Options\YesNo',
    );


    /**
     * @var null
     */
    private static $version = null;

    /**
     * will return dialog version, if installed
     *
     * @return null
     */
    public static function getVersion()
    {
        if (is_null(static::$version)) {
            static::$version = shell_exec('dpkg-query -W --showformat=\'${Version}\' whiptail 2> /dev/null');
        }

        return static::$version;
    }

    /**
     * will return true is dialog is available
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return (bool) Controller::getVersion();
    }

    /**
     * Check if can use dialog
     */
    public function __construct()
    {
        if (Controller::isAvailable() === false){
            throw new \ErrorException('Could not find package "whiptail"');
        }

    }

    /**
     * will set base command/option and return the command class
     *
     * @param   int $option
     *
     * @return  Options\BaseOption
     *
     * @throws  \InvalidArgumentException
     */
    public function setOption($option)
    {

        if (array_key_exists($option, $this->options)) {

            if (class_exists($this->options[$option])) {
                $this->command = new $this->options[$option];
            }

        } else {
            throw new \InvalidArgumentException(sprintf('Unknown option: %s', $option));
        }

        return $this->command;
    }

    /**
     * runs the build command, STDOUT is used to passthru the
     * dialog display, STDIN to passthru input and STDOUT set
     * as pipe here, will contain the error message and in
     * case with whiptail will also contain the return message
     *
     * @throws \Exception
     */
    public function run()
    {

        $isGauge =  ($this->command->getName() === 'gauge');


        if (empty($this->command)) {
            throw new \Exception('Need to define a option first');
        }

       if ($isGauge) {
           $descriptorSpec = array(
               array("pipe", "r"),
               STDOUT,
               array("pipe", "w"),
           );
       } else {
           $descriptorSpec = array(
               STDIN,
               STDOUT,
               array("pipe", "w"),
           );
       }

        $fullCommand = sprintf("whiptail %s", $this->command->getArguments());

        // open the child
        $proc = proc_open($fullCommand, $descriptorSpec, $pipes, getcwd());

        // set all streams to non blocking mode
        foreach ($descriptorSpec as $key => $ds) {
            if (is_array($ds)) {
                stream_set_blocking($pipes[$key], 0);
            } else {
                stream_set_blocking($ds, 0);
            }
        };

        if (is_resource($proc)) {

            // Loop while running
            while(true) {

                if (false !== $status = proc_get_status($proc)){

                    if($status['running'] === false) {

                        $this->output = stream_get_contents($pipes[2]);
                        fclose($pipes[2]);
                        $this->exitCode = $status['exitcode'];
                        break;

                    } else {
                        if ($this->command->getName() === 'gauge') {
                            $this->command->process($pipes[0]);
                        }
                    }
                } else {
                    throw new \Exception('Could not get proc status');
                }
            }
        } else {
            throw new \Exception('Cannot execute child process');
        }

    }


    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Exit status is 0 if whiptail is exited by pressing the Yes or OK button,
     * and 1 if the No or Cancel button is pressed. Otherwise, if errors occur
     * inside whiptail or whiptail is exited by pressing the ESC key, the exit
     * status is -1.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return ($this->exitCode == 1 || $this->exitCode == 0);
    }

    /**
     * whiptail prints default to stderr
     */
    public function getResult()
    {
        return $this->output;
    }
}
