@auth
<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (!meta) return;
        var trackUrl = @json(route('download.model.track'));
        document.querySelectorAll('a.js-model-download').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var path = anchor.getAttribute('data-download-path');
                var fileUrl = anchor.getAttribute('href');
                if (!path || !fileUrl) {
                    window.location.assign(fileUrl);
                    return;
                }
                function syncBadges(count) {
                    var formatted = typeof count === 'number' ? count.toLocaleString() : String(count);
                    document.querySelectorAll('a.js-model-download').forEach(function(a) {
                        if (a.getAttribute('data-download-path') !== path) return;
                        var badge = a.querySelector('.js-download-count');
                        if (badge) {
                            badge.textContent = formatted;
                        }
                    });
                }
                fetch(trackUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': meta.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ path: path }),
                    credentials: 'same-origin',
                })
                    .then(function(r) {
                        if (!r.ok) throw new Error('track failed');
                        return r.json();
                    })
                    .then(function(data) {
                        if (data && typeof data.count === 'number') {
                            syncBadges(data.count);
                        }
                    })
                    .catch(function() { /* keep previous count */ })
                    .finally(function() {
                        window.location.assign(fileUrl);
                    });
            });
        });
    });
})();
</script>
@endauth
