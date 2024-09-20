import { defineStore } from 'pinia'
import UserService from "../services/UserService";

export const useUsersStore = defineStore('users', {
    state: () => ({
        user: null,
        users: [],
        error: null,
    }),
    actions: {
        async getAll() {
            this.error = null
            const response = await UserService.getAll()
            this.users = response.data.data
        },

        async show(id) {
            this.error = null
            this.user = null

            try {
                const response = await UserService.show(id)
                this.user = response.data.data
            } catch (error) {
                this.error = error.response
            }

        },

        async store(payload) {
            try {
                this.error = null
                const response = await UserService.store(payload)
                return response.data.data
            } catch (error) {
                this.error = error.response
            }
        },
    },

    getters: {
        fullName: state => {
            if (!state.user) {
                return ''
            }

            return `${state.user.name_first} ${state.user.name_last}`
        },
        getErrors: state => state.error?.data?.errors
    }
})
