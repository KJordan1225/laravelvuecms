<template>
  <header class="cms-topbar">
    <div>
      <h2 class="cms-topbar-title">Admin Panel</h2>
      <p class="cms-topbar-subtitle">Manage content, media, and settings.</p>
    </div>

    <div class="cms-topbar-actions">
      <div class="cms-user-chip" v-if="auth.user">
        <span>{{ auth.user.name }}</span>
      </div>

      <button class="btn btn-outline" @click="handleLogout" :disabled="auth.loading">
        Logout
      </button>
    </div>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/cms/stores/auth'

const router = useRouter()
const auth = useAuthStore()

const handleLogout = async () => {
  await auth.logout()
  router.push({ name: 'admin.login' })
}
</script>
