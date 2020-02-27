import React, {Component, Fragment} from 'react'
import {connect} from 'react-redux'
import {addToCart} from './redux/actions'

class AddToCartComponent extends Component {

    constructor(props) {
        super(props);

        const {sizes, price, name, image, id} = props;

        let selectedSize = null;

        if (sizes.length > 0) {
            selectedSize = sizes[0];
        }

        this.state = { sizes, selectedSize, price, name, image, id };
    }

    changeSize(size) {
        return () => {
            const {selectedSize} = this.state;

            if (selectedSize !== size) {
                this.setState({ selectedSize: size });
            }
        };
    }

    addToCart() {
        const {selectedSize, id, name, image} = this.state;
        const defaultPrice = this.state.price;
        const price = selectedSize !== null ? selectedSize.price : defaultPrice;
        const sizeId = selectedSize !== null ? selectedSize.id : -1;

        this.props.addToCart({ id, name, image, sizeId, price, size: selectedSize });

        alert('Товар добавлен в корзину');
    }

    render() {
        const {sizes, selectedSize} = this.state;
        const defaultPrice = this.state.price;
        const price = selectedSize != null ? selectedSize.price : defaultPrice;
        const formattedPrice = price.toFixed(2);

        const {cartItemsReducer} = this.props;

        return <Fragment>
            {sizes.length > 0 && <div className="size-selector">
                {sizes.map((size, index) =>
                    <div className={selectedSize === size ? 'active' : ''} key={index} onClick={this.changeSize(size)}>{size.name}</div>)
                }
            </div>}
            <p className="product-price">Цена: {formattedPrice}грн</p>
            <a href="javascript:" className="button button-add-to-cart" onClick={this.addToCart.bind(this)}>Добавить в корзину</a>
        </Fragment>;
    }

}

export default connect((state) => {
    return state;
}, { addToCart })(AddToCartComponent);
