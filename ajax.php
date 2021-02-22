<?php
require __DIR__ . '/vendor/autoload.php';

/** @var DebugBar\DebugBar $debugbar */
$debugbar = (require __DIR__ . '/debugbar.php')();
$debugbar['messages']->addMessage('ajax!');
$debugbar->sendDataInHeaders(true);
echo 'hello';
