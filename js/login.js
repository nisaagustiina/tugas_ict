function showForm(form) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginToggle = document.getElementById('login-toggle');
    const registerToggle = document.getElementById('register-toggle');

    if (form === 'login') {
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

function togglePassword(element) {
    const input = element.previousElementSibling;
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
}

document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("toast");
    const error = toast.dataset.error;
    const success = toast.dataset.success;

    if (error || success) {
        // Tentukan isi pesan (error atau sukses)
        toast.textContent = error || success;

        // Tampilkan toast
        toast.classList.add("show");

        // Hilangkan toast setelah 3 detik
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }
});



function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(function () {
        toast.classList.remove('show');
    }, 3000); // Toast auto ilang dalam waktu 3 detik
}

