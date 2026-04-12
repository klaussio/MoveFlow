/**
 * MoveFlow — Dashboard Charts & Counters
 */
document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('revenue-chart')) return;

    initRevenueChart();
    initStatusChart();
    animateStatValues();
});

function initRevenueChart() {
    const canvas = document.getElementById('revenue-chart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const dataEl = document.getElementById('revenue-data');
    if (!dataEl) return;

    let chartData;
    try { chartData = JSON.parse(dataEl.textContent); } catch(e) { return; }
    if (!chartData || !chartData.length) {
        ctx.font = '14px Inter'; ctx.fillStyle = '#94a3b8'; ctx.textAlign = 'center';
        ctx.fillText('Sem dados disponíveis', canvas.width / 2, canvas.height / 2);
        return;
    }

    drawBarChart(ctx, canvas, chartData);
}

function drawBarChart(ctx, canvas, data) {
    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    const W = rect.width;
    const H = rect.height;

    const padding = { top: 20, right: 20, bottom: 40, left: 60 };
    const chartW = W - padding.left - padding.right;
    const chartH = H - padding.top - padding.bottom;

    const values = data.map(d => parseFloat(d.revenue) || 0);
    const max = Math.max(...values, 1) * 1.15;
    const barWidth = Math.min(40, (chartW / data.length) * 0.6);
    const barGap = (chartW - barWidth * data.length) / (data.length + 1);

    // Grid lines
    ctx.strokeStyle = '#e2e8f0'; ctx.lineWidth = 1;
    for (let i = 0; i <= 4; i++) {
        const y = padding.top + (chartH / 4) * i;
        ctx.beginPath(); ctx.moveTo(padding.left, y); ctx.lineTo(W - padding.right, y); ctx.stroke();
        const val = max - (max / 4) * i;
        ctx.fillStyle = '#94a3b8'; ctx.font = '11px Inter'; ctx.textAlign = 'right';
        ctx.fillText('R$ ' + val.toFixed(0), padding.left - 8, y + 4);
    }

    // Bars with animation
    data.forEach((d, i) => {
        const x = padding.left + barGap * (i + 1) + barWidth * i;
        const barH = (values[i] / max) * chartH;
        const y = padding.top + chartH - barH;

        // Bar gradient
        const grad = ctx.createLinearGradient(x, y, x, padding.top + chartH);
        grad.addColorStop(0, '#7c3aed');
        grad.addColorStop(1, '#a78bfa');
        ctx.fillStyle = grad;
        ctx.beginPath();
        const r = 4;
        ctx.moveTo(x + r, y); ctx.lineTo(x + barWidth - r, y);
        ctx.quadraticCurveTo(x + barWidth, y, x + barWidth, y + r);
        ctx.lineTo(x + barWidth, padding.top + chartH);
        ctx.lineTo(x, padding.top + chartH);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.fill();

        // Label
        const label = d.date ? new Date(d.date + 'T00:00:00').toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' }) : '';
        ctx.fillStyle = '#64748b'; ctx.font = '11px Inter'; ctx.textAlign = 'center';
        ctx.fillText(label, x + barWidth / 2, H - padding.bottom + 20);

        // Value on top
        ctx.fillStyle = '#334155'; ctx.font = '600 11px Inter';
        ctx.fillText('R$' + values[i].toFixed(0), x + barWidth / 2, y - 6);
    });
}

function initStatusChart() {
    const canvas = document.getElementById('status-chart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const dataEl = document.getElementById('status-data');
    if (!dataEl) return;

    let chartData;
    try { chartData = JSON.parse(dataEl.textContent); } catch(e) { return; }
    if (!chartData || !chartData.length) return;

    drawDonutChart(ctx, canvas, chartData);
}

function drawDonutChart(ctx, canvas, data) {
    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    const W = rect.width;
    const H = rect.height;

    const colors = {
        requested: '#f59e0b', accepted: '#3b82f6',
        in_progress: '#7c3aed', completed: '#22c55e', cancelled: '#ef4444'
    };

    const labels = {
        requested: 'Solicitada', accepted: 'Aceita',
        in_progress: 'Em Andamento', completed: 'Finalizada', cancelled: 'Cancelada'
    };

    const total = data.reduce((s, d) => s + parseInt(d.total), 0);
    const cx = W * 0.35;
    const cy = H / 2;
    const radius = Math.min(cx, cy) - 10;
    const inner = radius * 0.6;

    let startAngle = -Math.PI / 2;
    data.forEach(d => {
        const slice = (parseInt(d.total) / total) * Math.PI * 2;
        ctx.beginPath();
        ctx.arc(cx, cy, radius, startAngle, startAngle + slice);
        ctx.arc(cx, cy, inner, startAngle + slice, startAngle, true);
        ctx.closePath();
        ctx.fillStyle = colors[d.status] || '#94a3b8';
        ctx.fill();
        startAngle += slice;
    });

    // Center text
    ctx.fillStyle = '#0f172a'; ctx.font = '700 24px Outfit'; ctx.textAlign = 'center';
    ctx.fillText(total, cx, cy + 4);
    ctx.fillStyle = '#94a3b8'; ctx.font = '12px Inter';
    ctx.fillText('total', cx, cy + 20);

    // Legend
    const legendX = W * 0.65;
    let legendY = 20;
    data.forEach(d => {
        ctx.fillStyle = colors[d.status] || '#94a3b8';
        ctx.beginPath();
        ctx.arc(legendX, legendY + 6, 5, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = '#334155'; ctx.font = '13px Inter'; ctx.textAlign = 'left';
        ctx.fillText(`${labels[d.status] || d.status} (${d.total})`, legendX + 14, legendY + 10);
        legendY += 28;
    });
}

function animateStatValues() {
    document.querySelectorAll('.stat-animate').forEach(el => {
        const target = parseInt(el.dataset.target) || 0;
        animateCounter(el, target, 1200);
    });
}
