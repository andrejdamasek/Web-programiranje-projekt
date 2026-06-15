document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');

    if (navToggle && nav) {
        navToggle.addEventListener('click', () => {
            nav.classList.toggle('open');
        });
    }

    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', (event) => {
            const password = registerForm.querySelector('input[name="password"]');
            const confirm  = registerForm.querySelector('input[name="confirm_password"]');
            if (password.value !== confirm.value) {
                event.preventDefault();
                alert('Lozinke se moraju podudarati.');
            }
        });
    }
});