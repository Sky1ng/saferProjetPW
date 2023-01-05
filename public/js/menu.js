document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('.buttonBiens');
    const menu = document.querySelector('.menu');
    button.addEventListener('click', () => {
        menu.classList.toggle('open');
    });
});