/**
 * MoveFlow — API / Fetch Wrapper
 */
const API = {
    baseUrl: document.querySelector('meta[name="base-url"]')?.content || '',

    async get(endpoint) {
        try {
            const response = await fetch(this.baseUrl + '/' + endpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('API GET Error:', error);
            showToast('error', 'Erro ao carregar dados. Tente novamente.');
            throw error;
        }
    },

    async post(endpoint, data = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const formData = new FormData();
        formData.append('_csrf_token', csrfToken);

        for (const [key, value] of Object.entries(data)) {
            formData.append(key, value);
        }

        try {
            const response = await fetch(this.baseUrl + '/' + endpoint, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('API POST Error:', error);
            showToast('error', 'Erro ao processar a requisição.');
            throw error;
        }
    }
};
