import { defineStore } from 'pinia'
import api from '@/cms/lib/api'

export const useTagsStore = defineStore('tags', {
  state: () => ({
    items: [],
    item: null,
    meta: null,
    loading: false,
    saving: false,
    errors: {},
  }),

  actions: {
    async fetchAll(params = {}) {
      this.loading = true

      try {
        const { data } = await api.get('/tags', { params })
        this.items = data.data || []
        this.meta = data.meta || null
      } finally {
        this.loading = false
      }
    },

    async fetchOne(id) {
      this.loading = true

      try {
        const { data } = await api.get(`/tags/${id}`)
        this.item = data
        return data
      } finally {
        this.loading = false
      }
    },

    async create(payload) {
      this.saving = true
      this.errors = {}

      try {
        const { data } = await api.post('/tags', payload)
        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.saving = false
      }
    },

    async update(id, payload) {
      this.saving = true
      this.errors = {}

      try {
        const { data } = await api.put(`/tags/${id}`, payload)
        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.saving = false
      }
    },

    async delete(id) {
      await api.delete(`/tags/${id}`)
      this.items = this.items.filter((item) => item.id !== id)
    },
  },
})
