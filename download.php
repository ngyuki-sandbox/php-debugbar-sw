<?php
require __DIR__ . '/vendor/autoload.php';

/** @var DebugBar\DebugBar $debugbar */
$debugbar = (require __DIR__ . '/debugbar.php')();
$debugbar['messages']->addMessage('download!');
$debugbar->sendDataInHeaders(true);
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="download.txt"');
header('phpdebugbar-sw: on');
echo 'hello';
