/**
 * MoveFlow — Auth page logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;

    loginForm.addEventListener('submit', (e) => {
        const email = loginForm.querySelector('#email');
        const password = loginForm.querySelector('#password');
        let valid = true;

        // Clear previous errors
        loginForm.querySelectorAll('.form-error').forEach(el => el.remove());
        loginForm.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));

        if (!email.value.trim()) {
            valid = false;
            email.classList.add('error');
            email.parentElement.insertAdjacentHTML('beforeend', '<span class="form-error">Informe seu e-mail.</span>');
        } else if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            valid = false;
            email.classList.add('error');
            email.parentElement.insertAdjacentHTML('beforeend', '<span class="form-error">E-mail inválido.</span>');
        }

        if (!password.value) {
            valid = false;
            password.classList.add('error');
            password.parentElement.insertAdjacentHTML('beforeend', '<span class="form-error">Informe sua senha.</span>');
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    // Focus first field
    const emailField = loginForm.querySelector('#email');
    if (emailField && !emailField.value) {
        emailField.focus();
    }
});
