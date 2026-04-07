<template>
  <div class="cms-sidebar">
    <div class="cms-brand">
      <div class="cms-brand-badge">CMS</div>
      <div>
        <h1>Laravel CMS</h1>
        <p>Vue 3 + Pinia Admin</p>
      </div>
    </div>

    <nav class="cms-nav">
      <RouterLink
        v-for="item in sidebarLinks"
        :key="item.route"
        :to="{ name: item.route }"
        class="cms-nav-link"
        active-class="is-active"
      >
        {{ item.label }}
      </RouterLink>
    </nav>
  </div>
</template>

<script setup>
import { sidebarLinks } from '@/cms/constants/sidebar'
</script>
