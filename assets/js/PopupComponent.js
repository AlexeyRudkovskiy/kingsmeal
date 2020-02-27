import React, {Component} from 'react'
import {hidePopup} from './helpers/show-popup'

class PopupComponent extends Component {

    constructor(props) {
        super(props);

        this.state = {
            isVisible: false
        };
    }

    hidePopup() {
        hidePopup();

        if (typeof this.props.onClose !== "undefined") {
            this.props.onClose.call(window);
        }
    }

    getClasses() {
        const classes = ["popup-container"].filter(item => item.length > 0);

        if (typeof this.props.isCompact !== "undefined" && this.props.isCompact) {
            classes.push('popup-container-compact');
        }

        return classes.join(' ');
    }

    render() {
        return <div className={this.getClasses()}>
            <div className="popup-content-container">
                <div className="popup-header">
                    <span className="popup-header-label">{this.props.title}</span>
                    <a onClick={this.hidePopup.bind(this)} className="popup-close"><img src="/assets/icons/close.png"/></a>
                </div>
                <div className="popup-content">
                    {this.props.children}
                </div>
            </div>
        </div>
    }

}

export default PopupComponent;
