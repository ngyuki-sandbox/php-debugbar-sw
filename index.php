<?php
require __DIR__ . '/vendor/autoload.php';

$debugbar = (require __DIR__ . '/debugbar.php')();
$debugbar['messages']->addMessage('hello world!');

$renderer = $debugbar->getJavascriptRenderer();
?>
<html lang="ja">
<head>
    <?php echo $renderer->renderHead() ?>
    <title>php debugbar service-worker</title>
</head>
<body>
<a href="download.php">download.php</a>
<a href="ajax.php" data-ajax>ajax.php</a>
<?php echo $renderer->render() ?>

<script>
    navigator.serviceWorker.register('/php-debugbar-sw.js', {scope: '/'})
    navigator.serviceWorker.addEventListener('message', ev => {
        if (!ev.data.phpdebugbar) {
            return;
        }
        const response = new Response();
        for (const [name, value] of Object.entries(ev.data.phpdebugbar)) {
            response.headers.append(name, value);
            console.log(`[phpdebugbar-sw] data`, ev.data.phpdebugbar);
        }
        if (!phpdebugbar.ajaxHandler.loadFromId(response)) {
            phpdebugbar.ajaxHandler.loadFromData(response);
        }
    });

    for (const el of document.querySelectorAll('[data-ajax]')) {
        el.addEventListener('click', async (ev) => {
            ev.preventDefault();
            await fetch(ev.target.getAttribute('href'));
        })
    }
</script>

</body>
</html>
