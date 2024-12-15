document.addEventListener("DOMContentLoaded", () => {
    setupFormToggles();
    setupToast();
    setupPasswordToggles();
});

function setupFormToggles() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginToggle = document.getElementById('login-toggle');
    const registerToggle = document.getElementById('register-toggle');

    loginToggle.addEventListener('click', () => toggleForm('login', loginForm, registerForm, loginToggle, registerToggle));
    registerToggle.addEventListener('click', () => toggleForm('register', loginForm, registerForm, loginToggle, registerToggle));
}

function toggleForm(formType, loginForm, registerForm, loginToggle, registerToggle) {
    if (formType === 'login') {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        loginToggle.classList.add('active');
        registerToggle.classList.remove('active');
    } else {
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
        registerToggle.classList.add('active');
        loginToggle.classList.remove('active');
    }
}

function setupPasswordToggles() {
    const toggleIcons = document.querySelectorAll('.toggle-password');

    toggleIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        });
    });
}

function setupToast() {
    const toast = document.getElementById('toast');
    const errorMessage = toast.dataset.error;
    const successMessage = toast.dataset.success;

    if (errorMessage || successMessage) {
        showToast(errorMessage || successMessage, errorMessage ? 'error' : 'success');
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast show ${type}`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}