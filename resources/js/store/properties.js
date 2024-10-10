import { defineStore } from 'pinia'
import PropertyService from '../services/PropertyService'

export const usePropertiesStore = defineStore('properties', {
    state: () => ({
        properties: [],
        propertiesCount: 0,
        error: null,
        loadStatus: 0,
    }),
    actions: {
        async getAll(page = 1) {
            this.error = null
            this.loadStatus = 1

            try {
                const response = await PropertyService.getAll(page)
                this.properties = response.data.data
                this.propertiesCount = response.data.meta.total
                this.loadStatus = 2
            } catch (error) {
                this.error = error.response
                this.loadStatus = 3
            }
        },
    },
    getters: {
        getProperties: state => state.categories,
    }
})
