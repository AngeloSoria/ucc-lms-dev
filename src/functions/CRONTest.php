<?php

require_once '../../src/config/PathsHandler.php';
require_once FUNCTIONS . 'PHPLogger.php';


$timeNow = new DateTime();
msgLog("TASK SCHEDULER", $timeNow->format('Y-m-d H:i:s'));
