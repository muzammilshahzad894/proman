import { defineStore } from 'pinia'
import router from '../router'
import ProductsService from '../services/ProductsService'

export const useProductStore = defineStore('product', {
    state: () => ({
        products: null,
        error: null,
        loadStatus: 0,
    }),
    actions: {
        async get(uuid) {
             this.error = null
            this.loadStatus = 1
            try {
                const response = await ProductsService.get(uuid)
                this.products = response.data
                this.loadStatus = 2
            } catch (error) {
                console.log('error caught',error)
                this.error = error.response
                this.loadStatus = 3
                console.error('products  error ', error.response)
            }
        }
    },
    getters: {
        getProducts: state => state.products,
    }
})
