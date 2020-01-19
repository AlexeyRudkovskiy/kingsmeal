import React, {Component} from 'react'
import store from './store'

export default class Component1 extends Component {

    render() {
        console.log(store);

        return <span>Component 1</span>
    }

}
