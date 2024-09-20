import { defineStore } from 'pinia'
import PlatformService from '../services/PlatformService'

export const usePlatformStore = defineStore('platform', {
    state: () => ({
        platforms: null,
        userPlatforms: null,
        error: null,
        loadStatus: 0,
    }),
    actions: {
        async get() {
            this.error = null
            this.loadStatus = 1
            try {
                console.log('getting platforms... 2')
                const response = await PlatformService.get()

                console.log(response)

                this.platforms = response.data.data
                this.loadStatus = 2
            } catch (error) {
                console.log('error caught')
                this.platforms = null
                this.error = error.response
                this.loadStatus = 3
                console.error('platforms error ', error.response)
            }
        },

        async store(name) {
            this.error = null
            this.loadStatus = 1
            try {
                const response = await PlatformService.store({name})

                this.loadStatus = 2
            } catch (error) {
                console.log('error caught on platform store')
                this.error = error.response
                this.loadStatus = 3
                console.error('error while storing platform ', error.response)
            }
        },

        async update(uuid, payload) {
            this.error = null
            this.loadStatus = 1

            try {
                const response = await PlatformService.update(uuid, payload)
                this.loadStatus = 2
            } catch (error) {
                console.log('error caught on platform update')
                this.error = error.response
                this.loadStatus = 3
                console.error('error while updating platform ', error.response)
            }
        },

        async getForUser(uuid) {
            this.error = null
            this.loadStatus = 1
            this.userPlatforms = null

            try {
                console.log('getting platforms for user... 2')
                const response = await PlatformService.getForUser(uuid)

                this.userPlatforms = response.data.data
                this.loadStatus = 2
            } catch (error) {
                console.log('error caught')
                this.userPlatforms = null
                this.error = error.response
                this.loadStatus = 3
                console.error('platforms error ', error.response)
            }
        },
    },
    getters: {
        getPlatforms: state => state.platforms,
        getErrors: state => state.error?.data?.errors,
        getLoadStatus: state => state.loadStatus,
    }
})
