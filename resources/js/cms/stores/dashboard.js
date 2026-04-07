import { defineStore } from 'pinia'
import api from '@/cms/lib/api'

export const useDashboardStore = defineStore('dashboard', {
  state: () => ({
    stats: {
      posts_total: 0,
      posts_published: 0,
      posts_draft: 0,
      pages_total: 0,
      pages_published: 0,
      categories_total: 0,
      tags_total: 0,
    },
    recentPosts: [],
    recentPages: [],
    loading: false,
  }),

  actions: {
    async fetchDashboard() {
      this.loading = true

      try {
        const { data } = await api.get('/dashboard')
        this.stats = data.stats
        this.recentPosts = data.recent_posts || []
        this.recentPages = data.recent_pages || []
      } finally {
        this.loading = false
      }
    },
  },
})
