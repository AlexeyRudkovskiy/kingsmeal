import {addToCart, removeFromCart, clearCart, actions} from './actions'

export const cartItemsReducer = (state = [], action) => {
    switch (action.type) {
        case actions.ADD_TO_CART:
            const {cartItem} = action;
            const ifAlreadyInCart = state.filter(item => item.id === cartItem.id && item.sizeId === cartItem.sizeId);
            if (ifAlreadyInCart.length > 0) {
                const newState = state.map(item => {
                    if (item.id === cartItem.id && item.sizeId === cartItem.sizeId) {
                        item.quantity++;
                    }
                    return item;
                });

                return [ ...newState ];
            }

            const newCartItem = { ...cartItem };
            newCartItem.quantity = 1;

            return [ ...state, newCartItem ];
        case actions.REMOVE_FROM_CART:
            const _cartItem = action.cartItem;
            return state.filter(item => {
                return !(item.id === _cartItem.id && item.sizeId === _cartItem.sizeId);
            });
        case actions.DECREMENT:
            const _cartItemToDecrement = action.cartItem;
            const ifInCart = state.filter(item => {
                return item.id === _cartItemToDecrement.id && item.sizeId === _cartItemToDecrement.sizeId;
            });
            if (ifInCart.length > 0) {
                const newState = state.map(item => {
                    if (item.id === _cartItemToDecrement.id && item.sizeId === _cartItemToDecrement.sizeId) {
                        item.quantity--;
                    }
                    return item;
                });

                return [ ...newState ];
            }

            return [...state];
        case actions.CLEAR:
            return [];
        default: return state;
    }
};
