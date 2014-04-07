<?php
/**
 * @author    Philip Bergman <pbergman@live.nl>
 * @copyright Philip Bergman
 */

require_once dirname(__FILE__).'/vendor/autoload.php';

use PBergman\WhipTail\Controller as WhipTail;

$text = <<<EOF
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tincidunt semper nisl et pulvinar. Vestibulum vel ante vel sem rhoncus gravida. Donec ornare tristique felis, ac varius justo interdum quis. Duis iaculis eget nulla vel lobortis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec hendrerit erat non dui commodo, ut vehicula lectus hendrerit. Donec facilisis libero sapien, nec scelerisque dolor volutpat in. Cras molestie lorem in lectus venenatis mattis. Ut malesuada at elit nec blandit. Ut euismod, est sed ullamcorper luctus, ipsum arcu bibendum lorem, nec elementum lectus metus sit amet purus. Nam vel nulla purus. Pellentesque lacinia lacus ac ligula ultricies, non luctus massa ornare.

Duis viverra, sapien at porttitor tincidunt, ante risus porttitor eros, ac scelerisque dui leo vel orci. Suspendisse ac arcu sed metus semper faucibus blandit vel odio. Ut quis elit fermentum, sagittis metus eget, pretium elit. Vestibulum pulvinar nunc augue, a pretium orci rhoncus sit amet. Curabitur a odio et justo posuere tempor quis a urna. Nullam porta enim ac sem pretium euismod. Aenean est metus, porta eget diam quis, bibendum pulvinar nisi.

Nunc est ante, euismod id ultricies ac, fringilla dignissim tellus. Nullam id nulla sit amet leo egestas aliquam. Nunc consectetur, lorem in sagittis venenatis, magna eros gravida magna, et tempor felis lectus volutpat tortor. Donec nec accumsan tortor. Sed justo nibh, scelerisque id lacus blandit, fermentum malesuada mauris. Nunc at iaculis elit. Aliquam euismod ligula luctus dolor convallis pretium.

Sed bibendum condimentum sem. Sed venenatis in metus sit amet pharetra. In luctus, velit non blandit pellentesque, risus velit cursus urna, id dictum nunc quam eu lectus. Proin lacinia tempus enim, vitae luctus dolor hendrerit vel. Vivamus varius mi vitae eros posuere, nec aliquam lacus ornare. Curabitur vel condimentum nisi, sit amet porta libero. Nunc mollis sodales congue.

Proin ac elit vel sapien blandit consectetur. Curabitur placerat sagittis erat non consequat. Duis lectus felis, molestie in velit non, accumsan adipiscing dolor. Phasellus ut neque imperdiet, viverra neque ut, molestie tortor. Vestibulum sed porta lorem, eu blandit augue. Donec eget adipiscing arcu. In fringilla pharetra tellus, at pulvinar erat pretium vel.
EOF;

$list1 = array(
    array('ITEM1', "item 1", "ON"),
    array('ITEM2', "item 2", "OFF"),
    array('ITEM3', "item 3", "OFF"),
    array('ITEM4', "item 4", "OFF"),
    array('ITEM5', "item 5", "OFF"),
    array('ITEM6', "item 6", "OFF"),
    array('ITEM7', "item 7", "OFF"),
    array('ITEM8', "item 8", "OFF"),
);

$list2 = array(
    array('ITEM1', "item 1"),
    array('ITEM2', "item 2"),
    array('ITEM3', "item 3"),
    array('ITEM4', "item 4"),
    array('ITEM5', "item 5"),
    array('ITEM6', "item 6"),
    array('ITEM7', "item 7"),
    array('ITEM8', "item 8"),
);

if (WhipTail::isAvailable()) {

    /**
     * Demo password box
     */
    $whipTail =  new WhipTail();
    $whipTail
        ->setOption($whipTail::OPTION_PASSWORD_BOX)
        ->setBoxOption('title', 'Password')
        ->setMessage('new password');

    $whipTail->run();

    echo sprintf("[passwordbox] Your new password is: %s\n", $whipTail->getResult());

    /**
     * Demo info box
     */
    $whipTail
        ->setOption($whipTail::OPTION_INFO_BOX)
        ->setBoxOption('title', 'Info Box')
        ->setMessage('Some info');

    $whipTail->run();

    /**
     * Demo Gauge
     */
    $whipTail
        ->setOption($whipTail::OPTION_GAUGE)
        ->setBoxOption('title', 'Testing gauge')
        ->addCallBack(function(){
            sleep(1);
        })
        ->addCallBack(function(PBergman\WhipTail\Helpers\Progress $progress){

            $progress->setParts(5);

            for($i = 0; $i < 5; $i++){
                sleep(1);
                $progress->advance();
            }

        })
        ->addCallBack(function(){
            sleep(1);
        })
        ->setMessage('doing....');

    $whipTail->run();


    /**
     * Demo yes/no
     */
    $whipTail
        ->setOption($whipTail::OPTION_YES_NO)
        ->setBoxOption('title', 'Yes or No')
        ->setMessage('Is it working?');

    $whipTail->run();

    echo sprintf("[yesno] you choose: %s\n", ($whipTail->getExitCode() === 1) ? "no" : "yes" );

    /**
     * Demo text box
     */
    file_put_contents('./tmp.txt', $text);

    $whipTail
        ->setOption($whipTail::OPTION_TEXT_BOX)
        ->setBoxOption('title', 'Text Box')
        ->setBoxOption('scrolltext')
        ->setFile('./tmp.txt');

    $whipTail->run();

    unlink('./tmp.txt');

    /**
     * Demo message box
     */
    $whipTail
        ->setOption($whipTail::OPTION_MSG_BOX)
        ->setBoxOption('title', 'Message Box')
        ->setBoxOption('scrolltext')
        ->setMessage($text);

    $whipTail->run();

    /**
     * Demo input box
     */
    $whipTail
        ->setOption($whipTail::OPTION_INPUT_BOX)
        ->setBoxOption('title', 'Input Box')
        ->setMessage('Some Input');

    $whipTail->run();

    echo sprintf("[inputbox] give input: %s\n", $whipTail->getResult());

    /**
     * Demo Menu
     */

    $whipTail
        ->setOption($whipTail::OPTION_MENU)
        ->setBoxOption('title', 'Menu')
        ->setMessage('Select item')
        ->setList($list2);

    $whipTail->run();

    $result = $whipTail->getResult();

    echo sprintf("[menu] You selected: %s\n", $result);


    /**
     * Demo radio list
     */
    $whipTail
        ->setOption($whipTail::OPTION_RADIO_LIST)
        ->setBoxOption('title', 'RadioList')
        ->setMessage('Select item')
        ->setList($list1);

    $whipTail->run();

    $result = $whipTail->getResult();

    echo sprintf("[radiolist] You selected: %s\n", $result);

    /**
     * Demo check list
     *
     * will set item that was selected
     * with radio option to on
     *
     */
    if (isset($result)) {
        foreach($list1 as &$value) {
            if ($value[0] === $result) {
                $value[2] = "ON";
            } else {
                $value[2] = "OFF";
            }
        }
    }

    $whipTail
        ->setOption($whipTail::OPTION_CHECK_LIST)
        ->setBoxOption('title', 'CheckList')
        ->setBoxOption('separate-output')
        ->setMessage('Select item(s)')
        ->setList($list1);

    $whipTail->run();

    echo sprintf("[checklist] You selected: %s\n", print_r(array_filter(explode("\n", $whipTail->getResult())), true));
}




