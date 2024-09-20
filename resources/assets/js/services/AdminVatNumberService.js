import { apiClient } from './api'
import {useAuthStore} from "../store/auth";

export default {
    get: async uuid => {
        return await apiClient.get(`admin/users/${uuid}/vat-numbers`)
    },
}
