import { apiClient } from './api'

export default {
    get: async (uuid) => {
        console.log("in products service");
        return await apiClient.get(`/products/list`);
    },
}
