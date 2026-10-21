// Show/hide passwords
document.querySelectorAll('[data-toggle-password]').forEach((box) => {
    box.addEventListener('change', () => {
        document.querySelectorAll('input[type="password"], input[data-was-password]').forEach((input) => {
            if (box.checked) { input.dataset.wasPassword = '1'; input.type = 'text'; }
            else if (input.dataset.wasPassword) { input.type = 'password'; }
        });
    });
});

// Digits only for phone numbers
document.querySelectorAll('[data-digits]').forEach((input) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/g, '').slice(0, 11);
    });
});

// Birthdate cannot be in the future
document.querySelectorAll('[data-max-today]').forEach((input) => {
    input.max = new Date().toISOString().split('T')[0];
});

// Prevent double submits
document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
        const btn = form.querySelector('[data-submit]');
        if (btn) { btn.disabled = true; btn.textContent = 'Please wait...'; }
    });
});
