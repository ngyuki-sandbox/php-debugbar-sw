<?php

use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DebugBar;
use DebugBar\Storage\FileStorage;

return function () {
    $debugbar = new DebugBar();
    $debugbar->addCollector(new MessagesCollector());
    $debugbar->setStorage(new FileStorage(__DIR__ . '/data/debugbar'));
    $renderer = $debugbar->getJavascriptRenderer();
    $renderer->setOpenHandlerUrl('open.php');
    $renderer->setBindAjaxHandlerToFetch(true);
    return $debugbar;
};
