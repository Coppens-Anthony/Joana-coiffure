const eyes = document.querySelectorAll('.toggle_password');

eyes.forEach(eye => {
    eye.addEventListener('click', () => {
        const input = eye.parentElement.querySelector('input');
        const img = eye.querySelector('img');
        if (input.type === 'password') {
            input.type = 'text';
            img.src = '/assets/svg/close_eye.svg';
            img.alt = 'Cacher le mot de passe';
        } else {
            input.type = 'password';
            img.src = '/assets/svg/eye.svg';
            img.alt = 'Afficher le mot de passe';
        }
    });
});
