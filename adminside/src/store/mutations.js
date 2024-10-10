export function setUser(state, user) {
    state.user.data = user
}

export function setToken(state, token) {
    state.user.token = token
    if (token) {
        sessionStorage.setItem('TOKEN', token)
    } else {
        sessionStorage.removeItem('TOKEN')
    }
}

export function setProductLoading(state, loading) {
    state.products.loading = loading;
}

export function setProductError(state, error) {
    state.products.error = error;
}




export function setProducts(state, [loading, response = null]) {
    if (response) {
        state.products = {
            data: response.data,
            links: response.meta.links,
            from: response.meta.from,
            to: response.meta.to,
            page: response.meta.current_page,
            limit: response.meta.per_page,
            total: response.meta.total
        }
    }
    state.products.loading = loading;
}



export function removeProduct(state, productId) {
    state.products.data = state.products.data.filter(product => product.id !== productId);
}
