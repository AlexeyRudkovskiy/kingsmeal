let scrollToLeft = null;
let scrollToRight = null;
let promos = null;
let currentItem = 0;

function updateScrollControl() {
    if (currentItem < 1) {
        scrollToLeft.classList.add('hidden');
    } else {
        scrollToLeft.classList.remove('hidden');
    }

    if (currentItem < promos.length - 1) {
        scrollToRight.classList.remove('hidden');
    } else {
        scrollToRight.classList.add('hidden');
    }
}

export function scrollPromos() {

    scrollToLeft = document.querySelector('.scroll-to-left');
    scrollToRight = document.querySelector('.scroll-to-right');
    promos = document.querySelectorAll('.promo-grid .promo-container');

    updateScrollControl();

    scrollToLeft.addEventListener('click', () => {
        if (currentItem > 0) {
            promos[currentItem].classList.remove('active');
            promos[currentItem - 1].classList.add('active');

            currentItem--;
            updateScrollControl();
        }
    });

    scrollToRight.addEventListener('click', () => {
        if (currentItem < promos.length - 1) {
            promos[currentItem].classList.remove('active');
            promos[currentItem + 1].classList.add('active');

            currentItem++;
            updateScrollControl();
        }
    });

}
