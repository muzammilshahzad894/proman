import { apiClient } from './api'

export default {
    get: async () => {
        return await apiClient.get(`/platforms`)
    },

    store: async payload => {
        return await apiClient.post(`admin/platforms`, payload)
    },

    update: async (uuid, payload) => {
        return await apiClient.patch(`admin/platforms/${uuid}`, payload)
    },

    getForUser: async (uuid) => {
        return await apiClient.get(`admin/users/${uuid}/platforms`)
    }
}
