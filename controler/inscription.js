
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPassword(password) {
    if (password.length < 10) return false;
    if (!/[A-Z]/.test(password)) return false;
    if (!/[0-9]/.test(password)) return false;
    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) return false;
    return true;
}

function isValidName(name) {
    if (name.length === 0) return false;
    return name[0] === name[0].toUpperCase() && name[0] !== name[0].toLowerCase();
}

function showErrorBubble(inputElement, message) {
    removeErrorBubble(inputElement);

    const bubble = document.createElement('div');
    bubble.className = 'error-bubble';
    bubble.textContent = message;

    bubble.style.backgroundColor = '#ff4444';
    bubble.style.color = 'white';
    bubble.style.padding = '12px 16px';
    bubble.style.borderRadius = '6px';
    bubble.style.position = 'absolute';
    bubble.style.zIndex = '1000';
    bubble.style.fontSize = '16px';
    bubble.style.fontWeight = '500';
    bubble.style.maxWidth = '700px';
    bubble.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
    bubble.style.animation = 'fadeIn 0.3s ease-out';

    bubble.style.marginTop = '8px';
    bubble.style.setProperty('--arrow-size', '8px');
    bubble.style.setProperty('--arrow-color', '#ff4444');
    bubble.innerHTML = `
    <style>
      .error-bubble::before {
        content: '';
        position: absolute;
        top: calc(-2 * var(--arrow-size));
        left: 20px;
        border-width: var(--arrow-size);
        border-style: solid;
        border-color: transparent transparent var(--arrow-color) transparent;
      }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
    ${message}
  `;

    positionErrorBubble(inputElement, bubble);
    document.body.appendChild(bubble);

    const updatePosition = debounce(() => positionErrorBubble(inputElement, bubble), 100);
    window.addEventListener('resize', updatePosition);
    window.addEventListener('scroll', updatePosition, { passive: true });

    inputElement.setAttribute('data-has-error', 'true');
    inputElement.classList.add('input-error');

    const timeoutId = setTimeout(() => {
        removeErrorBubble(inputElement);
    }, 10000);

    inputElement.setAttribute('data-error-timeout', timeoutId);
}

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

function positionErrorBubble(inputElement, bubble) {
    const rect = inputElement.getBoundingClientRect();
    bubble.style.left = `${window.scrollX + rect.left}px`;
    bubble.style.top = `${window.scrollY + rect.bottom + 5}px`;
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}

function removeErrorBubble(inputElement) {
    if (inputElement.getAttribute('data-has-error') === 'true') {
        const timeoutId = inputElement.getAttribute('data-error-timeout');
        if (timeoutId) clearTimeout(parseInt(timeoutId));

        const bubbles = document.querySelectorAll('.error-bubble');
        bubbles.forEach(bubble => {
            const bubbleRect = bubble.getBoundingClientRect();
            const inputRect = inputElement.getBoundingClientRect();

            if (Math.abs(bubbleRect.left - inputRect.left) < 20 &&
                Math.abs(bubbleRect.top - (inputRect.bottom + 5)) < 20) {
                bubble.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => bubble.remove(), 300);
            }
        });

        inputElement.removeAttribute('data-has-error');
        inputElement.removeAttribute('data-error-timeout');
        inputElement.classList.remove('input-error');
    }
}

function validateForm() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const nameInput = document.getElementById('name');
    const loginButton = document.getElementById('login-button');

    function validateFields() {
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const name = nameInput.value.trim();

        let isValid = true;

        if (email === '') {
            removeErrorBubble(emailInput);
        } else if (!isValidEmail(email)) {
            showErrorBubble(emailInput, 'Veuillez entrer une adresse email valide (ex: exemple@domaine.com)');
            isValid = false;
        } else {
            removeErrorBubble(emailInput);
        }

        if (password === '') {
            removeErrorBubble(passwordInput);
        } else if (!isValidPassword(password)) {
            let errorMsg = 'Le mot de passe doit contenir:';
            errorMsg += password.length < 10 ? '\n- Au moins 10 caractères' : '';
            errorMsg += !/[A-Z]/.test(password) ? '\n- Au moins une majuscule' : '';
            errorMsg += !/[0-9]/.test(password) ? '\n- Au moins un chiffre' : '';
            errorMsg += !/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password) ? '\n- Au moins un caractère spécial' : '';

            showErrorBubble(passwordInput, errorMsg);
            isValid = false;
        } else {
            removeErrorBubble(passwordInput);
        }

        if (confirmPassword === '') {
            removeErrorBubble(confirmPasswordInput);
        } else if (password !== confirmPassword) {
            showErrorBubble(confirmPasswordInput, 'Les mots de passe ne correspondent pas. Veuillez vérifier.');
            isValid = false;
        } else {
            removeErrorBubble(confirmPasswordInput);
        }

        if (name === '') {
            removeErrorBubble(nameInput);
        } else if (!isValidName(name)) {
            showErrorBubble(nameInput, 'Le nom doit commencer par une majuscule et ne doit pas être vide');
            isValid = false;
        } else {
            removeErrorBubble(nameInput);
        }

        const allFieldsFilled = email && password && confirmPassword;
        loginButton.disabled = !isValid || !allFieldsFilled;
        loginButton.style.opacity = loginButton.disabled ? '0.7' : '1';
        loginButton.style.cursor = loginButton.disabled ? 'not-allowed' : 'pointer';

        return isValid;
    }

    const debouncedValidate = debounce(validateFields, 300);
    emailInput.addEventListener('input', debouncedValidate);
    passwordInput.addEventListener('input', debouncedValidate);
    confirmPasswordInput.addEventListener('input', debouncedValidate);
    nameInput.addEventListener('input', debouncedValidate);

    loginButton.addEventListener('click', function(event) {
        event.preventDefault();
        if (validateFields()) {
            alert('Inscription réussie !');
        } else {
            validateFields();
        }
    });

    validateFields();
}

document.addEventListener('DOMContentLoaded', validateForm);
