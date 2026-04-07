import { defineStore } from 'pinia'
import api from '@/cms/lib/api'

export const useSettingsStore = defineStore('settings', {
  state: () => ({
    items: [],
    loading: false,
    saving: false,
    errors: {},
  }),

  getters: {
    grouped: (state) => {
      return state.items.reduce((groups, item) => {
        if (!groups[item.group]) {
          groups[item.group] = []
        }

        groups[item.group].push(item)
        return groups
      }, {})
    },
  },

  actions: {
    async fetchAll() {
      this.loading = true

      try {
        const { data } = await api.get('/settings')
        this.items = data.data || []
      } finally {
        this.loading = false
      }
    },

    async update(settings) {
      this.saving = true
      this.errors = {}

      try {
        const { data } = await api.put('/settings', { settings })
        this.items = data.data?.data || []
        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.saving = false
      }
    },
  },
})
