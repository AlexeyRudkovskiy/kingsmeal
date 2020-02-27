let popupContainer = null;
let isVisible = false;

export function initPopup() {
    popupContainer = document.querySelector('.popup-target');
}

export function showPopup() {
    if (popupContainer !== null) {
        popupContainer.classList.remove('hidden');
        document.body.classList.add('hide-overflow');

        isVisible = true;
    }
}

export function hidePopup() {
    if (popupContainer !== null) {
        popupContainer.classList.add('hidden');
        document.body.classList.remove('hide-overflow');

        isVisible = false;
    }
}

export function togglePopup() {
    isVisible ? hidePopup() : showPopup();
}
