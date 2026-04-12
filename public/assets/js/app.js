/**
 * MoveFlow — Global Application Init
 */
document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initToasts();
    initModals();
    initDeleteForms();
});

/* === Sidebar === */
function initSidebar() {
    const toggle = document.getElementById('sidebar-toggle');
    const app = document.querySelector('.app');
    const overlay = document.querySelector('.sidebar-overlay');
    const sidebar = document.querySelector('.sidebar');

    if (toggle) {
        toggle.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-open');
                overlay?.classList.toggle('active');
            } else {
                app.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', app.classList.contains('sidebar-collapsed'));
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        });
    }

    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
        app?.classList.add('sidebar-collapsed');
    }
}

/* === Flash Toasts === */
function initToasts() {
    const container = document.querySelector('.toast-container');
    if (!container) return;

    container.querySelectorAll('.toast').forEach(toast => {
        // Auto-dismiss after 5s
        setTimeout(() => dismissToast(toast), 5000);

        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => dismissToast(toast));
        }
    });
}

function dismissToast(toast) {
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 300);
}

function showToast(type, message) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const icons = {
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
    };

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <span class="toast-icon">${icons[type] || icons.info}</span>
        <div class="toast-content">
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="dismissToast(this.parentElement)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    `;

    container.appendChild(toast);
    setTimeout(() => dismissToast(toast), 5000);
}

/* === Modal System === */
function initModals() {
    document.querySelectorAll('[data-modal]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-modal');
            openModal(modalId);
        });
    });
}

function openModal(id) {
    const backdrop = document.getElementById(id + '-backdrop');
    const modal = document.getElementById(id);
    if (backdrop) backdrop.classList.add('active');
    if (modal) modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const backdrop = document.getElementById(id + '-backdrop');
    const modal = document.getElementById(id);
    if (backdrop) backdrop.classList.remove('active');
    if (modal) modal.classList.remove('active');
    document.body.style.overflow = '';
}

/* === Delete Confirmation === */
function initDeleteForms() {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = form.dataset.name || 'este registro';
            if (confirm(`Tem certeza que deseja excluir ${name}? Esta ação não pode ser desfeita.`)) {
                form.submit();
            }
        });
    });
}

/* === Animated Counter === */
function animateCounter(element, target, duration = 1000) {
    const start = parseInt(element.textContent) || 0;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(start + (target - start) * eased);
        element.textContent = current.toLocaleString('pt-BR');

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}
