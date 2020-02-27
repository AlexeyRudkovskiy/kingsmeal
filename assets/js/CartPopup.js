import React, {Component} from 'react'
import PopupComponent from "./PopupComponent";
import {connect} from 'react-redux'
import {cartIcon} from './helpers/show-cart'
import {removeFromCart, addToCart, decrement, clearCart} from './redux/actions'
import InputField from "./InputField";

import axios from 'axios'

class CartPopup extends Component {

    constructor(props) {
        super(props);

        this.state = {
            step: 0,
            focus: {},
            values: {},
            errors: {},
            paymentMethod: 'card'
        };

        this.fields = [
            { label: 'Имя', name: 'firstName', placeholder: 'Введите Ваше имя' },
            { label: 'Фамилия', name: 'lastName', placeholder: 'Введите Вашу фамилию' },
            { label: 'Номер телефона', name: 'phoneNumber', placeholder: 'Введите Ваш номер телефона' },
            { label: 'Адрес доставки', name: 'deliveryAddress', placeholder: 'Введите адрес доставки' },
            { label: 'Номер дома', name: 'houseNumber', placeholder: 'Введите номер дома' },
            { label: 'Квартира', name: 'apartment', placeholder: 'Введите номер квартиры' },
        ];
    }

    removeFromCart(item) {
        return () => {
            this.props.removeFromCart(item);
        };
    }

    getName(item) {
        const parts = [ item.name ];
        if (item.size !== null) {
            parts.push(item.size.name);
        }

        return parts.join(' - ');
    }

    increment(item) {
        return () => { this.props.addToCart(item) };
    }

    decrement(item) {
        return () => {
            if (item.quantity > 1) {
                this.props.decrement(item);
            }
        }
    }

    getTotalPrice() {
        return this.props.cart
            .map(item => item.price * item.quantity)
            .reduce((acc, value, index) => acc + value, 0);
    }

    handleClose() {
        this.setState({ step: 0 });
    }

    showCheckout() {
        this.setState({ step: 1 });
    }

    handleChange(key) {
        return (e) => {
            const {values} = this.state;
            values[key] = e.target.value;
            this.setState({ values });
        };
    }

    handleFocusOrBlur(key, isFocus) {
        return (e) => {
            const {focus} = this.state;
            focus[key] = isFocus;
            this.setState({ focus });
        };
    }

    isFocus(key) {
        const {focus} = this.state;
        return typeof focus[key] !== "undefined" && focus[key];
    }

    confirmOrder() {
        const {cart} = this.props;
        const formData = new FormData();

        cart.forEach((item, index) => {
            formData.append(`products[${index}][id]`, item.id);
            formData.append(`products[${index}][sizeId]`, item.sizeId);
            formData.append(`products[${index}][quantity]`, item.quantity);
        });

        for (const key in this.state.values) {
            formData.append(key, this.state.values[key]);
        }

        formData.append('paymentMethod', this.state.paymentMethod);

        axios.post('/api/place-an-order', formData, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
            .then(response => response.data)
            .then(response => alert(response.message))
            .then(response => this.setState({ errors: {} }))
            .then(response => this.props.clearCart())
            .catch(err => this.setState({ errors: err.response.data }));
    }

    updatePaymentMethod(type) {
        return () => {
            this.setState({ paymentMethod: type });
        }
    }

    render() {
        const {cart} = this.props;
        let cartLength = cart.length;

        for (let i = 0; i < cartIcon.length; i++) {
            if (cartLength > 0) {
                cartIcon[i].setAttribute('data-badge', cartLength);
            } else {
                cartIcon[i].removeAttribute('data-badge');
            }
        }

        if (this.state.step === 0) {
            return <PopupComponent title="Корзина" onClose={this.handleClose.bind(this)}>
                {cart.length > 0 && <React.Fragment>
                    {cart.map((item, index) => <div key={index} className="product-item">
                        <div className="product-photo" style={{backgroundImage: "url(" + item.image + ")"}} />
                        <div className="product-description">
                            <span>{this.getName(item)}</span>
                            <a onClick={this.removeFromCart(item)} className="product-remove-from-cart">Удалить из корзины</a>
                        </div>
                        <div className="product-quantity">
                            <a onClick={this.decrement(item)} className="product-quantity-decrement" />
                            <div className="product-quantity-value">{item.quantity}</div>
                            <a onClick={this.increment(item)} className="product-quantity-increment" />
                            <div className="product-price">{item.quantity * item.price} грн</div>
                        </div>
                    </div>)}
                    <div className="cart-footer">
                        <div className="button button-large" onClick={this.showCheckout.bind(this)}>Перейти к оформлению заказа ({this.getTotalPrice()} грн)</div>
                    </div>
                </React.Fragment>}
                {cart.length < 1 && <span>Корзина пустая</span>}
            </PopupComponent>
        }

        if (this.state.step === 1) {
            return <PopupComponent title={'Подтверждение заказа'} onClose={this.handleClose.bind(this)} isCompact={true}>
                {this.fields.map((item, index) => <React.Fragment key={item.name}>
                    <InputField label={item.label}
                                error={this.state.errors[item.name]}
                                placeholder={item.placeholder}
                                value={this.state.values[item.name] || ''}
                                onChange={this.handleChange(item.name)}
                                onFocus={this.handleFocusOrBlur(item.name, true)}
                                onBlur={this.handleFocusOrBlur(item.name, false)}
                                isActive={this.isFocus(item.name)} />
                </React.Fragment>)}

                <div className="form-control form-control-radio-group">
                    <label className="radio-group-item">
                        <input type="radio" name="payment-type" value="card" onChange={this.updatePaymentMethod('card')} checked={this.state.paymentMethod === 'card'} />
                        <span>Картой</span>
                    </label>
                    <label className="radio-group-item">
                        <input type="radio" name="payment-type" value="courier" onChange={this.updatePaymentMethod('courier')} checked={this.state.paymentMethod === 'courier'} />
                        <span>Курьеру</span>
                    </label>
                </div>

                {this.state.paymentMethod === 'card' && <div style={{marginTop: '16px'}}>
                    {(window.cardInstructions || '').split('\n').map((line, index) => <p key={index}>{line}</p>)}
                </div>}

                <div className="button button-large button-full-width offset-top" onClick={this.confirmOrder.bind(this)}>Подтвердить заказ</div>
            </PopupComponent>
        }
    }

}

export default connect(state => ({ cart: state.cartItemsReducer }), {
    removeFromCart, addToCart, decrement, clearCart
})(CartPopup);
