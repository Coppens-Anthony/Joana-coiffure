const eyes = document.querySelectorAll('.toggle_password');

eyes.forEach(eye => {
    eye.addEventListener('click', () => {
        const input = eye.parentElement.querySelector('input');

        if (input.type === 'password') {
            input.type = 'text';
            eye.src = '/assets/svg/close_eye.svg';
            eye.alt = 'Cacher le mot de passe';
        } else {
            input.type = 'password';
            eye.src = '/assets/svg/eye.svg';
            eye.alt = 'Afficher le mot de passe';
        }
    });
});
