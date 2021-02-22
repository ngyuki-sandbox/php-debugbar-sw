# PHP Debug Bar で ServiceWorker を使ってダウンロードの情報も表示

Ajax や Post/Redirect/Get の POST は設定さえすればデバッグバーに表示できる。

- http://phpdebugbar.com/docs/ajax-and-stack.html#ajax
- http://phpdebugbar.com/docs/ajax-and-stack.html#stacked-data

ただ `Content-Disposition: attachment` によるファイルのダウンロードは素のままではデバッグバーに表示させられない。

ServiceWorker を使えばダウンロードのレスポンスも js で処理できる。ダウンロードのレスポンスに `$debugbar->sendDataInHeaders(true);` でデバッグバー情報を入れて、ServiceWorker で取り出して ページ側の js に通知する。ページ側の js は ServiceWorker からの通知を元に Ajax を表示するのと同じ用法でデバッグバーにデータを追加する。

ただし、ServiceWorker の中で [FetchEvent](https://developer.mozilla.org/ja/docs/Web/API/FetchEvent) でダウンロードの呼び出し元ページを得ることができなさそうなので（Firefox ならできるかもしれない・・未確認）、ServiceWorker 管理下の、同時に開いているすべてのページのデバッグバーに表示される。通常あまり問題にはならないと思う。

なお Ajax なら ServiceWorker の中で呼び出し元のページが得られるので、Ajax も jQuery や window.fetch をラップするようなダーティな方法ではなく ServiceWorker で処理すると良いかもしれない。

むしろデバッグバーのようなページ下部に無理やり突っ込むような方法ではなく、別タブでデバッグコンソールのようなものを開いて、ServiceWorker なりあるいはページ間の postMessage なりで、その別タブでデバッグ情報を表示するようにした方がいいかも・・元ページとのレイアウトの競合とか気にしなくてよくなるし。
