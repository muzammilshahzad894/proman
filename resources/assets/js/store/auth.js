import { defineStore } from 'pinia'
import router from '../router'
import AuthService from '../services/AuthService'

const getUserFromLocalStorage = () => localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null

export const useAuthStore = defineStore('auth', {
    state: () => ({
        // initialize state from local storage to enable user to stay logged in
        user: getUserFromLocalStorage(),
        authenticated: false,
        loadStatus: 0,
        error: null
    }),
    persist: true,
    actions: {
        async login(email, password) {
            console.log('login module...')
            this.authenticated = false
            this.error = null
            this.loadStatus = 1

            try {
                console.log('logging in...')
                await AuthService.login({email, password})
                this.loadStatus = 2
                this.authenticated = true
            } catch (error) {
                console.log('error caught')
                this.error = error.response
                this.loadStatus = 3
                this.authenticated = false
                console.error('login error ', error.response)
            }
        },
        async logout() {
            this.user = null
            this.authenticated = false
            this.error = null
            this.loadStatus = 0

            localStorage.removeItem('user')
            return await AuthService.logout().then(() => router.push({ name: 'login' }))
        },
        async getAuthUser()  {
            console.log('getUser initiated...')

            this.error = null
            this.loadStatus = 1

            try {
                console.log('getting user...')
                const response = await AuthService.getAuthUser()
                const user = response.data.data
                console.log('the user ', user)
                this.user = user
                localStorage.setItem('user', JSON.stringify(user))

                this.loadStatus = 2

                // redirect to previous url or default to home page
                // router.push(this.returnUrl || '/')
            } catch (error) {
                console.log('getUser error found')
                this.error = error
                this.loadStatus = 3
                console.error('getUser error ', error)
            }
        },
        async update(uuid, payload) {
            console.log('updating user...')

            this.error = null
            this.loadStatus = 1

            try {
                const updatedUser = await AuthService.updateUser(uuid, payload)

                this.user = updatedUser.data.data
                localStorage.setItem('user', JSON.stringify(updatedUser.data.data))
                this.loadStatus = 2
            } catch (error) {
                console.log('update user error found')
                this.error = error
                this.loadStatus = 3
                console.error('update error ', error)
            }
        }
    },
    getters: {
        getLoadStatus: state => state.loadStatus,
        getUser: state => state.user,
        isAuthenticated: state => state.authenticated,
        getErrors: state => state.error,
        isAdmin: state => state.user?.roles?.indexOf('admin') >= 0,
        isCustomer: state => state.user?.roles?.indexOf('customer') >= 0
    }
})
