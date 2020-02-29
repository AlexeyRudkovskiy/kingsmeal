export function forosPromoWidget() {

    const container = document.querySelector('.foros-promo');
    const close = container.querySelector('.promo-close');

    close.addEventListener('click', () => {
        container.parentElement.removeChild(container);
    });

}
