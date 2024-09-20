import { defineStore } from 'pinia'
import VatNumberService from '../services/VatNumberService'

export const useVatNumberStore = defineStore('vatNumber', {
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
                const response = await VatNumberService.get(uuid)

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

        async store(uuid, countryCode, vatNumber) {
            this.error = null
            this.vatLoadStatus = 1
            try {
                const response = await VatNumberService.store(uuid, {code: countryCode, number: vatNumber})

                // this.vatNumbers = response.data.data
                this.vatLoadStatus = 2
            } catch (error) {
                console.log('error caught on vat number store')
                // this.vatNumbers = null
                this.error = error.response
                this.vatLoadStatus = 3
                console.error('error while storing vat number ', error.response)
            }
        },

        async update(uuid, payload) {
            this.error = null
            this.vatLoadStatus = 1

            try {
                const response = await VatNumberService.update(uuid, payload)
                this.vatLoadStatus = 2

                const updatedVatNumber = response.data.data

                // Update the credential object in the existing array or push it if it doesn't exist
                const index = this.vatNumbers.findIndex(vatNumber => vatNumber.id === updatedVatNumber.id)
                if (index !== -1) {
                    this.vatNumbers = [
                        ...this.vatNumbers.slice(0, index),
                        { ...updatedVatNumber},
                        ...this.vatNumbers.slice(index + 1)
                    ]
                } else {
                    this.vatNumbers.push(updatedVatNumber)
                }
            } catch (error) {
                console.log('error caught on vat number update')
                // this.vatNumbers = null
                this.error = error.response
                this.vatLoadStatus = 3
                console.error('error while updating vat number ', error.response)
            }
        },

        async toggleOss(uuid, payload) {
            this.error = null
            try {
                const response = await VatNumberService.toggleOssEnabled(uuid, payload)
                console.log(response);
            } catch (error) {
                console.log('error caught on OSS toggle')
                this.error = error.response
                console.error('error while on OSS toggle ', error.response)
            }
        }
    },
    getters: {
        getVatNumbers: state => state.vatNumbers,
        getErrors: state => state.error,
        getLoadStatus: state => state.vatLoadStatus,
    }
})
