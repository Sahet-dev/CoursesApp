import axiosClient from "../api/axios.js";
import state from "./state.js";


export function getUser({commit}) {
    return axiosClient.get('/user')
        .then((response) => {
            commit('setUser', response.data)
            return response
        })
}

export function login({commit}, data) {
    return axiosClient.post('/login', data)
        .then(({data}) => {
            commit('setUser', data.user)
            commit('setToken', data.token)
            return data
        })
}

export function logout({commit}) {
    return axiosClient.post('/logout')
        .then((response) => {
            commit('setToken', null)
            return response
        })
        .catch((error) => {
            commit('setToken', null)
        })
}

export function getProducts({commit}, {url = null, search = '' , sort_field, sort_direction} = {}) {
    commit('setProducts', [true])
    url = url || '/products';

    return axiosClient.get(url, {
        params: {
            search,
            // per_page: perPage ,
            sort_field,
            sort_direction

        }

    })
        .then((response) => {
            commit('setProducts', [false, response.data])
            console.log(';crap')

        })
        .catch(() => {
            commit('setProducts', [false])
        })
}


export function getProduct({}, id) {
    console.log('Fetching product with ID:', id);
    return  axiosClient.get(`/products/${id}`)
}



export function createProduct({commit}, product) {
    if (product.image instanceof File){
        const  form = new FormData();
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('description', product.description);
        form.append('price', product.price);
        product = form;
    }

    return axiosClient.post('/products', product)

}

export function updateProduct({commit}, product) {
    const id = product.id
    if (product.image instanceof File){
        const form = new FormData();
        form.append('id', product.id);
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('description', product.description);
        form.append('price', product.price);
        form.append('_method', 'PUT');
        product = form
    }else {
        product._method = 'PUT'
    }
    return axiosClient.post(`/products/${id}`, product)
}

export function deleteProduct({ commit }, id) {
    return axiosClient.delete(`/products/${id}`)
        .then(() => {
            commit('removeProduct', id);  // Assuming you have a mutation to handle this
            return true;  // Indicate success
        })
        .catch(error => {
            console.error("Failed to delete product:", error);
            return false;  // Indicate failure
        });
}
