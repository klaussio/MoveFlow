/**
 * MoveFlow — Utility Functions
 */
const Utils = {
    /**
     * Format number as BRL currency
     */
    formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    },

    /**
     * Format date to PT-BR
     */
    formatDate(dateStr) {
        if (!dateStr) return '—';
        const date = new Date(dateStr);
        return date.toLocaleDateString('pt-BR', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        });
    },

    /**
     * Format datetime to PT-BR
     */
    formatDateTime(dateStr) {
        if (!dateStr) return '—';
        const date = new Date(dateStr);
        return date.toLocaleDateString('pt-BR', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    },

    /**
     * CPF mask (000.000.000-00)
     */
    maskCPF(input) {
        let v = input.value.replace(/\D/g, '');
        v = v.substring(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        input.value = v;
    },

    /**
     * Phone mask ((00) 00000-0000)
     */
    maskPhone(input) {
        let v = input.value.replace(/\D/g, '');
        v = v.substring(0, 11);
        if (v.length > 6) {
            v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        } else if (v.length > 2) {
            v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
        } else if (v.length > 0) {
            v = v.replace(/(\d{0,2})/, '($1');
        }
        input.value = v;
    },

    /**
     * Vehicle plate mask (ABC-1D23 or ABC-1234)
     */
    maskPlate(input) {
        let v = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        v = v.substring(0, 7);
        if (v.length > 3) {
            v = v.replace(/^([A-Z]{3})(.+)/, '$1-$2');
        }
        input.value = v;
    },

    /**
     * Debounce function
     */
    debounce(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Simple form validation
     */
    validateForm(form) {
        let valid = true;
        form.querySelectorAll('[required]').forEach(field => {
            const group = field.closest('.form-group');
            const existingError = group?.querySelector('.form-error');
            if (existingError) existingError.remove();
            field.classList.remove('error');

            if (!field.value.trim()) {
                valid = false;
                field.classList.add('error');
                const label = group?.querySelector('.form-label')?.textContent?.replace('*', '').trim() || 'Este campo';
                const error = document.createElement('span');
                error.className = 'form-error';
                error.textContent = `${label} é obrigatório.`;
                field.parentElement.appendChild(error);
            }
        });

        // Email validation
        form.querySelectorAll('input[type="email"]').forEach(field => {
            if (field.value && !field.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                valid = false;
                field.classList.add('error');
                const group = field.closest('.form-group');
                if (!group?.querySelector('.form-error')) {
                    const error = document.createElement('span');
                    error.className = 'form-error';
                    error.textContent = 'E-mail inválido.';
                    field.parentElement.appendChild(error);
                }
            }
        });

        return valid;
    }
};

// Apply masks on input
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-mask="cpf"]').forEach(input => {
        input.addEventListener('input', () => Utils.maskCPF(input));
    });
    document.querySelectorAll('[data-mask="phone"]').forEach(input => {
        input.addEventListener('input', () => Utils.maskPhone(input));
    });
    document.querySelectorAll('[data-mask="plate"]').forEach(input => {
        input.addEventListener('input', () => Utils.maskPlate(input));
    });
});
