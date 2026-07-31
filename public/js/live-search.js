document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const suggestionsDropdown = document.getElementById('suggestionsDropdown');

    if (!liveSearch) return;

    let searchTimeout;
    let currentFocus = -1;

    liveSearch.addEventListener('input', function() {
        const query = this.value.trim();
        document.getElementById('filterSearch').value = query;

        clearTimeout(searchTimeout);
        suggestionsDropdown.innerHTML = '';

        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach((item, index) => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item';
                                div.innerHTML = `<strong>${item.name}</strong> - ${item.category} - ₹${item.price}`;
                                div.addEventListener('click', () => {
                                    window.location.href = item.url;
                                });
                                div.dataset.index = index;
                                suggestionsDropdown.appendChild(div);
                            });
                            suggestionsDropdown.classList.add('show');
                        } else {
                            suggestionsDropdown.classList.remove('show');
                        }
                    });
            }, 300);
        } else {
            suggestionsDropdown.classList.remove('show');
        }
    });

    liveSearch.addEventListener('keydown', function(e) {
        const items = suggestionsDropdown.querySelectorAll('.suggestion-item');
        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1 && items[currentFocus]) {
                items[currentFocus].click();
            } else {
                applySearch();
            }
        }
    });

    function addActive(items) {
        if (!items || items.length === 0) return;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;
        items[currentFocus].classList.add('active');
        items[currentFocus].style.background = '#f8f9fa';
    }

    function removeActive(items) {
        items.forEach(item => {
            item.classList.remove('active');
            item.style.background = '';
        });
    }

    document.addEventListener('click', function(e) {
        if (!liveSearch.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
            suggestionsDropdown.classList.remove('show');
        }
    });
});

function applySearch() {
    const query = document.getElementById('liveSearch').value;
    if (query.trim()) {
        const form = document.getElementById('filterForm');
        const url = new URL(form.action);
        url.searchParams.set('search', query);
        window.location.href = url.toString();
    }
}
