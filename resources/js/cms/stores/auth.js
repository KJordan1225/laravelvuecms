import { defineStore } from 'pinia'
import api from '@/cms/lib/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('cms_admin_token') || null,
    user: null,
    loading: false,
    errors: {},
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    setToken(token) {
      this.token = token
      localStorage.setItem('cms_admin_token', token)
    },

    clearAuth() {
      this.token = null
      this.user = null
      this.errors = {}
      localStorage.removeItem('cms_admin_token')
    },

    async login(payload) {
      this.loading = true
      this.errors = {}

      try {
        const { data } = await api.post('/login', payload)
        this.setToken(data.token)
        this.user = data.user
        return data
      } catch (error) {
        this.errors = error.response?.data?.errors || {}
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchMe() {
      const { data } = await api.get('/me')
      this.user = data.user
      return data.user
    },

    async logout() {
      try {
        await api.post('/logout')
      } finally {
        this.clearAuth()
      }
    },
  },
})
