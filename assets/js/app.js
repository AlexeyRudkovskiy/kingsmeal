/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import React from "react";
import {render} from 'react-dom'
import AddToCartComponent from "./AddToCartComponent";
import {Provider} from "react-redux";
import {initCart} from "./helpers/show-cart";
import store from './redux/store'

import tabsWidget from './widgets/tabs'
import mapWidget from './widgets/map'
import {scrollPromos} from "./helpers/scroll-promos";
import showMenu from "./helpers/show-menu";
import showPhones from "./helpers/show-phones";
import {forosPromoWidget} from "./helpers/foros-promo";

require('../css/app.scss');

(function () {
    const addToCartWidgets = document.querySelectorAll('.add-to-cart-widget');

    for (const addToCartWidget of addToCartWidgets) {
        const props = JSON.parse(addToCartWidget.getAttribute('data-props'));

        render(<Provider store={store}><AddToCartComponent {...props} /></Provider>, addToCartWidget);
    }

    initCart();
    tabsWidget();
    mapWidget();
    scrollPromos();
    showMenu();
    showPhones();
    forosPromoWidget();

})();

// const component1Target = document.querySelector('.component1');
// const component2Target = document.querySelector('.component2');
//
// if (component1Target !== null) {
//     render(<Component1 />, component1Target);
// }
//
// if (component2Target !== null) {
//     render(<Component2 />, component2Target);
// }

