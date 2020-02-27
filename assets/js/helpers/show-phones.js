export default function showPhones() {
    const activators = document.querySelectorAll('.show-phones');
    const targets = document.querySelectorAll('.phones-popup');

    for (let i = 0; i < activators.length; i++) {
        activators[i].addEventListener('click', () => {
            for (let j = 0; j < targets.length; j++) {
                targets[j].classList.toggle('hidden');
            }
        });
    }
}
