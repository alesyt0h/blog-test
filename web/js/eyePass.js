function passwordShow() {
    const passInput = document.querySelector("input[name='password']");
    const showPass = document.querySelector('#show-pass');
    const hidePass = document.querySelector('#hide-pass');

    passInput.type = (passInput.type === 'password') ? 'text' : 'password';

    hidePass.classList.toggle('hidden');
    showPass.classList.toggle('hidden');
}