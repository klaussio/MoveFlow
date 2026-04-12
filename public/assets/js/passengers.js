/**
 * MoveFlow — Passengers page logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const passengersTable = document.getElementById('passengers-table');
    if (!passengersTable) return;

    initPassengerSearch();
    initPassengerForm();
});

function initPassengerSearch() {
    const searchInput = document.getElementById('passenger-search');
    if (searchInput) {
        searchInput.addEventListener('input', Utils.debounce(() => {
            const search = searchInput.value;
            const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            window.location.href = baseUrl + '/passengers' + (params.toString() ? '?' + params.toString() : '');
        }, 400));
    }
}

function initPassengerForm() {
    const form = document.getElementById('passenger-form');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        if (!Utils.validateForm(form)) {
            e.preventDefault();
        }
    });
}
