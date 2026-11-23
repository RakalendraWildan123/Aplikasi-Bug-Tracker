{{-- Optimasi Kecepatan Navigasi & Perpindahan Halaman (< 0.8 Detik) --}}
{{-- 1. Speculation Rules API untuk Prerender & Prefetch Instan di Browser Chromium (Chrome, Edge) --}}
<script type="speculationrules">
{
  "prerender": [
    {
      "where": {
        "and": [
          { "href_matches": "/*" },
          { "not": { "href_matches": "/logout" } },
          { "not": { "href_matches": "*/delete*" } }
        ]
      },
      "eagerness": "moderate"
    }
  ],
  "prefetch": [
    {
      "where": {
        "and": [
          { "href_matches": "/*" },
          { "not": { "href_matches": "/logout" } }
        ]
      },
      "eagerness": "moderate"
    }
  ]
}
</script>

{{-- 2. Script Instant Link Prefetch & Top Progress Bar --}}
<script>
    (function () {
        const prefetchedUrls = new Set();

        function prefetchTarget(href) {
            if (!href || prefetchedUrls.has(href)) return;
            try {
                const url = new URL(href, window.location.href);
                if (url.origin !== window.location.origin) return;
                if (url.pathname === '/logout' || url.pathname.includes('/delete')) return;
                prefetchedUrls.add(href);

                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = href;
                link.as = 'document';
                document.head.appendChild(link);
            } catch (_) {}
        }

        // 3. Mencegah tampilan halaman ter-cache saat tombol Back browser ditekan setelah logout (bfcache protection)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Prefetch otomatis saat kursor mouse mendekati/menyentuh tautan navigasi
        document.addEventListener('pointerenter', function (e) {
            const a = e.target.closest('a');
            if (a && a.href) prefetchTarget(a.href);
        }, { capture: true, passive: true });

        document.addEventListener('pointerdown', function (e) {
            const a = e.target.closest('a');
            if (a && a.href) prefetchTarget(a.href);
        }, { capture: true, passive: true });

        // Animasi bar progres di bagian atas saat berpindah halaman (respon visual instan 0ms)
        document.addEventListener('click', function (e) {
            const a = e.target.closest('a');
            if (!a || !a.href || a.target === '_blank' || a.hasAttribute('download')) return;
            try {
                const url = new URL(a.href, window.location.href);
                if (url.origin !== window.location.origin) return;
                if (url.pathname === '/logout' || url.href === window.location.href) return;
                if (url.hash && url.pathname === window.location.pathname) return;

                let bar = document.getElementById('instant-nav-bar');
                if (!bar) {
                    bar = document.createElement('div');
                    bar.id = 'instant-nav-bar';
                    bar.style.cssText = 'position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#4f46e5,#06b6d4);z-index:99999;transition:width 0.25s cubic-bezier(0.4,0,0.2,1);width:0%;pointer-events:none;box-shadow:0 0 10px rgba(79,70,229,0.7);';
                    document.body.appendChild(bar);
                }
                bar.style.width = '85%';
            } catch (_) {}
        });
    })();
</script>
