export default function showMenu() {

    const expandMenu = document.querySelector('.expand-menu-button');
    const toToggle = document.querySelector('.mobile-menu .menu-items');
    expandMenu.addEventListener('click', () => {
        toToggle.classList.toggle('hidden');
    });

}
