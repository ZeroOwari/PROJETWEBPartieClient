function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidName(name) {
    if (name.length === 0) return false;
    return name[0] === name[0].toUpperCase() && name[0] !== name[0].toLowerCase();
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return phone === '' || phoneRegex.test(phone);
}

function isValidMessage(message) {
    return message.length >= 10;
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

    // Ajout d'une flèche
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

    // Gestion des événements avec debounce pour les performances
    const updatePosition = debounce(() => positionErrorBubble(inputElement, bubble), 100);
    window.addEventListener('resize', updatePosition);
    window.addEventListener('scroll', updatePosition, { passive: true });

    inputElement.setAttribute('data-has-error', 'true');
    inputElement.classList.add('input-error');

    // Supprimer la bulle après 10 secondes ou quand l'utilisateur commence à taper
    const timeoutId = setTimeout(() => {
        removeErrorBubble(inputElement);
    }, 10000);

    // Stocker l'ID du timeout pour pouvoir l'annuler si besoin
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
    const lastNameInput = document.querySelector('input[placeholder="Nom"]');
    const firstNameInput = document.querySelector('input[placeholder="Prénom"]');
    const emailInput = document.querySelector('input[placeholder="E-mail"]');
    const phoneInput = document.querySelector('input[placeholder="Téléphone"]');
    const messageInput = document.querySelector('textarea[placeholder="Message"]');
    const submitButton = document.querySelector('.btn-submit');

    function validateFields() {
        const lastName = lastNameInput.value.trim();
        const firstName = firstNameInput.value.trim();
        const email = emailInput.value.trim();
        const phone = phoneInput.value.trim();
        const message = messageInput.value.trim();

        let isValid = true;

        if (lastName === '') {
            removeErrorBubble(lastNameInput);
        } else if (!isValidName(lastName)) {
            showErrorBubble(lastNameInput, 'Le nom doit commencer par une majuscule');
            isValid = false;
        } else {
            removeErrorBubble(lastNameInput);
        }

        if (firstName === '') {
            removeErrorBubble(firstNameInput);
        } else if (!isValidName(firstName)) {
            showErrorBubble(firstNameInput, 'Le prénom doit commencer par une majuscule');
            isValid = false;
        } else {
            removeErrorBubble(firstNameInput);
        }

        if (email === '') {
            removeErrorBubble(emailInput);
        } else if (!isValidEmail(email)) {
            showErrorBubble(emailInput, 'Veuillez entrer une adresse email valide (ex: exemple@domaine.com)');
            isValid = false;
        } else {
            removeErrorBubble(emailInput);
        }

        if (phone !== '' && !isValidPhone(phone)) {
            showErrorBubble(phoneInput, 'Veuillez entrer un numéro de téléphone valide');
            isValid = false;
        } else {
            removeErrorBubble(phoneInput);
        }

        if (message === '') {
            removeErrorBubble(messageInput);
        } else if (!isValidMessage(message)) {
            showErrorBubble(messageInput, 'Le message doit contenir au moins 10 caractères');
            isValid = false;
        } else {
            removeErrorBubble(messageInput);
        }

        const allRequiredFieldsFilled = lastName && firstName && email && message;
        submitButton.disabled = !isValid || !allRequiredFieldsFilled;
        submitButton.style.opacity = submitButton.disabled ? '0.7' : '1';
        submitButton.style.cursor = submitButton.disabled ? 'not-allowed' : 'pointer';

        return isValid && allRequiredFieldsFilled;
    }

    const debouncedValidate = debounce(validateFields, 300);
    lastNameInput.addEventListener('input', debouncedValidate);
    firstNameInput.addEventListener('input', debouncedValidate);
    emailInput.addEventListener('input', debouncedValidate);
    phoneInput.addEventListener('input', debouncedValidate);
    messageInput.addEventListener('input', debouncedValidate);

    document.querySelector('form').addEventListener('submit', function(event) {
        if (!validateFields()) {
            event.preventDefault();
            validateFields();
        } else {
            alert('Votre message a été envoyé avec succès !');
        }
    });
    validateFields();
}

document.addEventListener('DOMContentLoaded', validateForm);