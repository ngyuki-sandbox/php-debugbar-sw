<?php
require __DIR__ . '/vendor/autoload.php';

use DebugBar\OpenHandler;

$debugbar = (require __DIR__ . '/debugbar.php')();
$openHandler = new OpenHandler($debugbar);
// Deprecated: usort(): Returning bool from comparison function is deprecated, return an integer less than, equal to, or greater than zero in FileStorage
@$openHandler->handle();
