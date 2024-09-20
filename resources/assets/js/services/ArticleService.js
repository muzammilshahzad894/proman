import { apiClient } from './api'

export default {
    store: async payload => {
        return await apiClient.post(`admin/support/articles`, payload)
    },

    update: async payload => {
      return await apiClient.patch(`admin/support/articles/${payload.id}`)
    },

    destroy: async id => {
        return await apiClient.delete(`admin/support/articles/${id}`)
    },

    show: async id => {
        return await apiClient.get(`support/articles/${id}`)
    },

    getAll: async () => {
        return await apiClient.get('support/articles')
    },

    getCategories: async () => {
        return await apiClient.get('support/categories')
    },

    createCategory: async payload => {
      return await apiClient.post('admin/support/categories', payload)
    },

    getByCategoryId: async categoryId => {
        return await apiClient.get(`support/articles?category=${categoryId}`)
    }
}
