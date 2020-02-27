export const actions = {
    ADD_TO_CART: 'ADD_TO_CART',
    REMOVE_FROM_CART: 'REMOVE_FROM_CART',
    DECREMENT: 'DECREMENT',
    CLEAR: 'CLEAR'
};

export const addToCart = (cartItem) => ({
    type: actions.ADD_TO_CART, cartItem
});

export const removeFromCart = (cartItem) => ({
    type: actions.REMOVE_FROM_CART, cartItem
});

export const decrement = (cartItem) => ({
    type: actions.DECREMENT, cartItem
});

export const clearCart = () => ({
    type: actions.CLEAR
});
