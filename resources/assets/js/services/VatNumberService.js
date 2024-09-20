import { apiClient } from './api'
import {useAuthStore} from "../store/auth";

export default {
    get: async uuid => {
        return await apiClient.get(`users/${uuid}/vat-numbers`)
    },

    store: async (uuid, payload) => {
        return await apiClient.post(`users/${uuid}/vat-numbers`, payload)
    },

    update: async (uuid, payload) => {
        const authStore = useAuthStore()
        const userUuid = authStore.user.uuid
        alert(uuid)
        return await apiClient.patch(`users/${userUuid}/vat-numbers/${uuid}`, payload)
    },
    
    toggleOss: async (uuid, payload) => {
        const authStore = useAuthStore()
        const userUuid = authStore.user.uuid
        console.log(apiClient);
        return await apiClient.patch(`users/${userUuid}/vat-numbers/toggle-oss`, payload)
    },

    
}
