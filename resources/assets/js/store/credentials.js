import { defineStore } from 'pinia'
import CredentialService from '../services/CredentialService'

export const useCredentialStore = defineStore('credential', {
    state: () => ({
        credentials: null,
        error: null,
        loadStatus: 0,
    }),
    actions: {
        async get() {
            this.error = null
            this.loadStatus = 1
            try {
                console.log('getting credentials... 2')
                const response = await CredentialService.get()

                this.credentials = response.data.data
                this.loadStatus = 2
            } catch (error) {
                console.log('error caught')
                this.credentials = null
                this.error = error.response
                this.loadStatus = 3
                console.error('credentials error ', error.response)
            }
        },

        async store(platform, clientId, clientSecret) {
            this.error = null
            this.loadStatus = 1
            try {
                const response = await CredentialService.store({
                    platform: platform,
                    client_id: clientId,
                    client_secret: clientSecret
                })

                this.loadStatus = 2
            } catch (error) {
                this.error = error.response
                this.loadStatus = 3
                console.error('error while storing credential ', error.response)
            }
        },

        async update(uuid, payload) {
            this.error = null
            this.loadStatus = 1

            try {
                const response = await CredentialService.update(uuid, payload)
                this.loadStatus = 2

                const updatedCredential = response.data.data

                // Update the credential object in the existing array or push it if it doesn't exist
                const index = this.credentials.findIndex(credential => credential.id === updatedCredential.id)
                if (index !== -1) {
                    this.credentials = [
                        ...this.credentials.slice(0, index),
                        { ...updatedCredential},
                        ...this.credentials.slice(index + 1)
                    ]
                } else {
                    this.credentials.push(updatedCredential)
                }
            } catch (error) {
                console.log('error caught on credential update')
                this.error = error.response
                this.loadStatus = 3
                console.error('error while updating credential ', error.response)
            }
        }
    },
    getters: {
        getCredentials: state => state.credentials,
        getErrors: state => state.error,
        getLoadStatus: state => state.loadStatus,
    }
})
