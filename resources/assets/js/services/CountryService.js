import { apiClient } from './api'

export default {
    get: async () => {
        return await apiClient.get('/countries')
    },
}
