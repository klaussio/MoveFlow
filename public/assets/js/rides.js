/**
 * MoveFlow — Rides page logic
 */
document.addEventListener('DOMContentLoaded', () => {
    initRideForm();
    initFareCalculator();
    initRideFilters();
});

function initRideForm() {
    const form = document.getElementById('ride-form');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        if (!Utils.validateForm(form)) {
            e.preventDefault();
        }
    });
}

function initFareCalculator() {
    const distanceInput = document.getElementById('distance_km');
    const durationInput = document.getElementById('estimated_duration_min');
    const fareDisplay = document.getElementById('fare-preview');

    if (!distanceInput || !durationInput || !fareDisplay) return;

    const calculate = () => {
        const distance = parseFloat(distanceInput.value) || 0;
        const duration = parseInt(durationInput.value) || 0;

        if (distance > 0 && duration > 0) {
            const base = 5.00;
            const distCost = distance * 2.50;
            const timeCost = duration * 0.40;
            const total = base + distCost + timeCost;

            fareDisplay.innerHTML = `
                <div class="fare-label">Tarifa Estimada</div>
                <div class="fare-value">${Utils.formatCurrency(total)}</div>
                <div class="fare-breakdown">
                    <span>Base: R$ 5,00</span>
                    <span>Distância: ${Utils.formatCurrency(distCost)}</span>
                    <span>Tempo: ${Utils.formatCurrency(timeCost)}</span>
                </div>
            `;
            fareDisplay.style.display = 'block';
        } else {
            fareDisplay.style.display = 'none';
        }
    };

    distanceInput.addEventListener('input', Utils.debounce(calculate, 200));
    durationInput.addEventListener('input', Utils.debounce(calculate, 200));
}

function initRideFilters() {
    const statusFilter = document.getElementById('ride-status-filter');
    const dateFrom = document.getElementById('ride-date-from');
    const dateTo = document.getElementById('ride-date-to');
    const searchInput = document.getElementById('ride-search');

    const applyFilters = Utils.debounce(() => {
        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
        const params = new URLSearchParams();

        if (searchInput?.value) params.set('search', searchInput.value);
        if (statusFilter?.value) params.set('status', statusFilter.value);
        if (dateFrom?.value) params.set('date_from', dateFrom.value);
        if (dateTo?.value) params.set('date_to', dateTo.value);

        window.location.href = baseUrl + '/rides' + (params.toString() ? '?' + params.toString() : '');
    }, 400);

    statusFilter?.addEventListener('change', applyFilters);
    dateFrom?.addEventListener('change', applyFilters);
    dateTo?.addEventListener('change', applyFilters);
    searchInput?.addEventListener('input', applyFilters);
}
