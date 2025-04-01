document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        userType: 'etudiant', // Fixé à étudiant pour votre cas
        remember: document.getElementById('remember').checked
    };

    try {
        // 1. Vérification des identifiants
        const response = await fetch('/vue/assets/data/users.json');
        const data = await response.json();
        const user = data.etudiants.find(u =>
            u.email === formData.email &&
            u.password === formData.password
        );

        if (!user) {
            throw new Error('Identifiants incorrects');
        }

        // 2. Envoi au serveur
        const authResponse = await fetch('/?page=process-login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ...user, ...formData })
        });

        if (!authResponse.ok) throw new Error('Erreur serveur');

        // 3. Redirection
        window.location.href = '?page=infos-compte';

    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message);
    }
});