# whiptail
a php cli wrapper for linux whiptail

## Installation
The recommended way to install is [through composer](http://getcomposer.org).

```
{
    "require": {
        "pbergman/whiptail": "@stable"

    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:pbergman/whiptail.git"
        }
    ],
    "minimum-stability": "dev"
}
```

## Usage
```php

use WhipTail\Controller as WhipTail;

if (WhipTail::isAvailable()) {

    $whipTail =  new WhipTail();
    $whipTail
        ->setOption($whipTail::OPTION_PASSWORD_BOX)
        ->setBoxOption('title', 'Password')
        ->setMessage('new password');

    $whipTail->run();
}

```

## Available options
```
OPTION_CHECK_LIST
OPTION_GAUGE
OPTION_INFO_BOX
OPTION_INPUT_BOX
OPTION_MENU
OPTION_MSG_BOX
OPTION_PASSWORD_BOX
OPTION_RADIO_LIST
OPTION_TEXT_BOX
OPTION_YES_NO
```
To see demo of all options look @ [Test.php](https://github.com/pbergman/whiptail/blob/master/Test.php).

## Box options

Box options can be set by method setBoxOption, first argument is the option name and second the argument.

options:
```
	clear				            clear screen on exit
	defaultno			            default no button
	default-item    <string>		set default string
	fb				                use full buttons
	nocancel			            no cancel button
	yes-button      <text>		    set text of yes button
	no-button       <text>		    set text of no button
	ok-button       <text>		    set text of ok button
	cancel-button   <text>		    set text of cancel button
	noitem			                display tags only
	separate-output		            output one line at a time
	output-fd       <fd>		    output to fd, not stdout
	title           <title>			display title
	backtitle       <backtitle>		display backtitle
	scrolltext			            force vertical scrollbars
	topleft			                put window in top-left corner
```


