import { defineStore } from 'pinia'
import api from '@/cms/lib/api'

export const useMediaStore = defineStore('media', {
  state: () => ({
    items: [],
    meta: null,
    loading: false,
    uploading: false,
    errors: {},
  }),

  actions: {
    async fetchAll(params = {}) {
      this.loading = true

      try {
        const { data } = await api.get('/media', { params })
        this.items = data.data || []
        this.meta = data.meta || null
      } finally {
        this.loading = false
      }
    },

    async upload(payload) {
      this.uploading = true
      this.errors = {}

      try {
        const formData = new FormData()
        formData.append('file', payload.file)

        if (payload.alt_text) {
          formData.append('alt_text', payload.alt_text)
        }

        if (payload.title) {
          formData.append('title', payload.title)
        }

        const { data } = await api.post('/media', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })

        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.uploading = false
      }
    },

    async delete(id) {
      await api.delete(`/media/${id}`)
      this.items = this.items.filter((item) => item.id !== id)
    },
  },
})
