import { apiClient } from './api'

export default {
    getAll: async (
        page = 1,
    ) => {
        const baseUrl = `/properties?page=${page}`;

        return await apiClient.get(baseUrl);
    },
}