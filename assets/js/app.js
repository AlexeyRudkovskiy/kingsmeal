/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import React from "react";
import {render} from 'react-dom'
import Component1 from "./component1.js";
import Component2 from "./component2.js";

require('../css/app.css');

const component1Target = document.querySelector('.component1');
const component2Target = document.querySelector('.component2');

if (component1Target !== null) {
    render(<Component1 />, component1Target);
}

if (component2Target !== null) {
    render(<Component2 />, component2Target);
}

