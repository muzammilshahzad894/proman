import { defineStore } from 'pinia'
import AdminVatNumberService from "../services/AdminVatNumberService"

export const useAdminVatNumberStore = defineStore('adminVatNumber', {
    state: () => ({
        vatNumbers: null,
        error: null,
        vatLoadStatus: 0,
    }),
    actions: {
        async getVatNumbersForUser(uuid) {
            this.error = null
            this.vatLoadStatus = 1
            try {
                const response = await AdminVatNumberService.get(uuid)

                this.vatNumbers = response.data.data
                this.vatLoadStatus = 2
            } catch (error) {
                console.log('error caught')
                this.vatNumbers = null
                this.error = error.response
                this.vatLoadStatus = 3
                console.error('vat numbers error ', error.response)
            }
        },
    },
    getters: {
        getVatNumbers: state => state.vatNumbers,
        getErrors: state => state.error,
        getLoadStatus: state => state.vatLoadStatus,
    }
})
