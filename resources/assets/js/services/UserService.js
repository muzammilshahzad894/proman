import { apiClient } from './api'

export default {
    getAll: async () => {
        return apiClient.get(`/admin/users`)
    },

    show: async id => {
        return apiClient.get(`/admin/users/${id}`)
    },

    store: async payload => {
        return apiClient.post(`/admin/users`, payload)
    }
}
