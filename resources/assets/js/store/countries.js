import { defineStore } from 'pinia'
import router from '../router'
import CountryService from '../services/CountryService'

export const useCountryStore = defineStore('country', {
    state: () => ({
        countries: null,
        error: null,
        loadStatus: 0,
    }),
    actions: {
        async get() {
            this.error = null
            this.loadStatus = 1
            try {
                const response = await CountryService.get()

                this.countries = response.data.data
                this.loadStatus = 2
            } catch (error) {
                console.log('error caught')
                this.error = error.response
                this.loadStatus = 3
                console.error('countries error ', error.response)
            }
        }
    },
    getters: {
        getCountries: state => state.countries,
    }
})
