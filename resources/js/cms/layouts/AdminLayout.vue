<template>
  <div class="cms-shell">
    <aside class="cms-sidebar-wrap">
      <SidebarNav />
    </aside>

    <div class="cms-main-wrap">
      <TopbarNav />
      <main class="cms-main-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import SidebarNav from '@/cms/components/layout/SidebarNav.vue'
import TopbarNav from '@/cms/components/layout/TopbarNav.vue'
</script>
