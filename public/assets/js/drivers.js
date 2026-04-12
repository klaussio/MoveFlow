/**
 * MoveFlow — Drivers page logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const driversTable = document.getElementById('drivers-table');
    if (!driversTable) return;

    initDriverSearch();
    initStatusToggle();
    initDriverForm();
});

function initDriverSearch() {
    const searchInput = document.getElementById('driver-search');
    const statusFilter = document.getElementById('driver-status-filter');

    if (searchInput) {
        searchInput.addEventListener('input', Utils.debounce(() => {
            applyDriverFilters();
        }, 400));
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', () => applyDriverFilters());
    }
}

function applyDriverFilters() {
    const search = document.getElementById('driver-search')?.value || '';
    const status = document.getElementById('driver-status-filter')?.value || '';
    const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
    const params = new URLSearchParams();

    if (search) params.set('search', search);
    if (status) params.set('status', status);

    window.location.href = baseUrl + '/drivers' + (params.toString() ? '?' + params.toString() : '');
}

function initStatusToggle() {
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const driverId = btn.dataset.id;
            try {
                const result = await API.post(`drivers/toggle-status/${driverId}`);
                if (result.success) {
                    const badge = btn.closest('tr')?.querySelector('.driver-status-badge');
                    if (badge) {
                        badge.className = `badge badge-dot driver-status-badge ${result.data.class}`;
                        badge.querySelector('span')?.remove();
                        badge.insertAdjacentHTML('beforeend', `<span>${result.data.label}</span>`);
                    }
                    showToast('success', 'Status atualizado!');
                }
            } catch (error) {
                showToast('error', 'Erro ao atualizar status.');
            }
        });
    });
}

function initDriverForm() {
    const form = document.getElementById('driver-form');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        if (!Utils.validateForm(form)) {
            e.preventDefault();
        }
    });
}
