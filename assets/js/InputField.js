import React, {Component} from 'react'
import {connect} from 'react-redux'
import Provider from "react-redux/es/components/Provider";
import PropTypes from 'prop-types';


class InputField extends Component {

    getGroupClassName() {
        const classes = ['form-control'];
        if (this.props.isActive) {
            classes.push('active');
        }

        return classes.join(' ')
    }

    render() {
        return <div className={this.getGroupClassName()}>
            <label className="form-control-label">{this.props.label}</label>
            <input type={'text'}
                   value={this.props.value || ''}
                   onChange={this.props.onChange || (() => {})}
                   onFocus={this.props.onFocus || (() => {})}
                   onBlur={this.props.onBlur || (() => {})}
                   placeholder={this.props.placeholder || ''}
                   className="form-control-input" />
            {(typeof this.props.error !== "undefined" && this.props.error.length > 0) &&
                <div className='form-control-error'>{this.props.error}</div>}
        </div>
    }

}

InputField.propTypes = {
    label: PropTypes.string,
    placeholder: PropTypes.string,
    value: PropTypes.string,
    error: PropTypes.string,
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func
};

export default connect(state => ({ cart: state.cartItemsReducer }))(InputField);
