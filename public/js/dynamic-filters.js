document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (!filterForm) return;

    const debounce = (func, delay) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const applyFilters = debounce(() => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();

        fetch(`{{ route('customer.products') }}?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newProducts = doc.getElementById('productsContainer');
            if (newProducts) {
                document.getElementById('productsContainer').innerHTML = newProducts.innerHTML;
                window.history.pushState({}, '', `?${params}`);
            }
        });
    }, 400);

    filterForm.querySelectorAll('select, input').forEach(el => {
        el.addEventListener('change', applyFilters);
        el.addEventListener('keyup', (e) => {
            if (e.target.tagName === 'INPUT') applyFilters();
        });
    });
});
