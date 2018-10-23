<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/dumpHex.php';

Tracy\Debugger::enable();
Tracy\Debugger::$maxDepth = 5;
Tracy\Debugger::$maxLength = 2048;
