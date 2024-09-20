import { apiClient } from './api'
import { useAuthStore } from "../store/auth"

export default {
    get: async () => {
        const authStore = useAuthStore()
        return await apiClient.get(`users/${authStore.user.uuid}/credentials`)
    },

    store: async payload => {
        const authStore = useAuthStore()
        console.log(payload)
        return await apiClient.post(`users/${authStore.user.uuid}/credentials`, payload)
    },

    update: async (uuid, payload) => {
        const authStore = useAuthStore()
        return await apiClient.patch(`users/${authStore.user.uuid}/credentials/${uuid}`, payload)
    }
}
