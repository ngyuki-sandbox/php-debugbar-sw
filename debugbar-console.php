<?php
require __DIR__ . '/vendor/autoload.php';

$debugbar = (require __DIR__ . '/debugbar.php')();
$renderer = $debugbar->getJavascriptRenderer();
?>
<html lang="ja">
<head>
    <?php echo $renderer->renderHead() ?>
    <title>php debugbar</title>
    <style>
        body {
            overflow-y: hidden;
        }
        .phpdebugbar {
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
        }
        .phpdebugbar-body {
            height: auto !important;
        }
        .phpdebugbar-close-btn {
            display: none !important;
        }
    </style>
</head>
<body>
<script>
    PhpDebugBar.DebugBar.prototype.setHeight = () => {};
    PhpDebugBar.DebugBar.prototype.restoreState = () => {};
</script>
<?php echo $renderer->render() ?>
<script>
    window.phpdebugbar.showTab();
    navigator.serviceWorker.register('/php-debugbar-sw.js', {scope: '/'})
    navigator.serviceWorker.addEventListener('message', ev => {
        const data = ev.data['phpdebugbar-console'];
        if (!data) {
            return;
        }
        const response = new Response();
        for (const [name, value] of Object.entries(data)) {
            response.headers.append(name, value);
            console.log(`[phpdebugbar-sw] data`, ev.data.phpdebugbar);
        }
        if (!phpdebugbar.ajaxHandler.loadFromId(response)) {
            phpdebugbar.ajaxHandler.loadFromData(response);
        }
    });
</script>
</body>
</html>
