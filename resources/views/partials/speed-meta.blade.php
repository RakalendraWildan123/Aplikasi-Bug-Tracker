{{-- Optimasi Kecepatan Navigasi & Perpindahan Halaman (< 0.8 Detik) --}}
<script>
    (function () {
        // 1. Mencegah tampilan halaman ter-cache saat tombol Back browser ditekan setelah logout (bfcache protection)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // 2. Animasi bar progres instan di bagian atas saat berpindah halaman (respon visual instan 0ms)
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
                    bar.style.cssText = 'position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#4f46e5,#06b6d4);z-index:99999;transition:width 0.2s ease;width:0%;pointer-events:none;box-shadow:0 0 10px rgba(79,70,229,0.7);';
                    document.body.appendChild(bar);
                }
                bar.style.width = '90%';
            } catch (_) {}
        });
    })();
</script>
