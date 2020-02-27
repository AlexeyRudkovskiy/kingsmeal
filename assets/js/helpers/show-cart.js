import {showPopup, initPopup} from "./show-popup";
import {render} from "react-dom";
import {Provider} from "react-redux";
import CartPopup from "../CartPopup";
import React from "react";
import store from '../redux/store'

export let cartIcon = null;

export function initCart() {
    let element = document.querySelector('.popup-target');
    cartIcon = document.querySelectorAll('.cart-icon');

    initPopup();

    if (element !== null) {
        render(<Provider store={store}><CartPopup /></Provider>, element);
    }

    for (let i = 0; i < cartIcon.length; i++) {
        cartIcon[i].addEventListener('click', () => {
            showCart();
        });
    }
}

export function showCart() {
    showPopup();
}
