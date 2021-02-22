if (typeof window === 'undefined') {
    self.addEventListener('install', (event) => {
        console.log('[phpdebugbar-sw] Install debugbar service worker');
        event.waitUntil(self.skipWaiting());
    });

    self.addEventListener('activate', (event) => {
        console.log('[phpdebugbar-sw] Activate debugbar service worker');
        event.waitUntil(self.clients.claim());
    });

    self.addEventListener('fetch', (event) => {
        event.respondWith((async () => {
            const response = await fetch(event.request);
            if (!response.headers.has('phpdebugbar-sw')) {
                // Ajax で二重に処理しないように `phpdebugbar-sw` があるときだけ処理
                return response;
            }
            let data = {};
            for (const [name, value] of response.headers.entries()) {
                // phpdebugbar phpdebugbar-id だけでなく phpdebugbar-1 のような連番のヘッダも含める
                if (name.startsWith('phpdebugbar')) {
                    data[name] = value;
                }
            }
            if (Object.keys(data).length === 0) {
                return response;
            }
            // ダウンロードは通常のページ遷移と同じ扱いになるので clientId は空になる
            // resultingClientId に遷移先の id が入っているものの実際には遷移しないのでそれを使う意味はない
            // replacesClientId には遷移元の id が入っているらしいけど Chrome では未サポート
            // つまりダウンロードの場合は元のページの id が得られない
            // ので `self.clients.matchAll({type: 'window'})` ですべてのページを取得してブロードキャストする
            const clients = event.clientId ?
                [await self.clients.get(event.clientId)] :
                await self.clients.matchAll({type: 'window'});
            for (const client of clients) {
                client.postMessage({phpdebugbar: data});
                console.log(`[phpdebugbar-sw] post`, {id: client.id, url: event.request.url});
            }
            return response;
        })());
    });
}
