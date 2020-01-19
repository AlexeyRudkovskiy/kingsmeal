import React, {Component} from 'react'
import store from './store'

export default class Component2 extends Component {

    render() {
        console.log(store);

        return <span>Component 2</span>
    }

}

