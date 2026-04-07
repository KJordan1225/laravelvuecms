<template>
  <div>
    <PageHeader
      title="Dashboard"
      subtitle="Quick overview of your content system."
    />

    <div class="stats-grid">
      <StatCard label="Total Posts" :value="dashboard.stats.posts_total" />
      <StatCard label="Published Posts" :value="dashboard.stats.posts_published" />
      <StatCard label="Draft Posts" :value="dashboard.stats.posts_draft" />
      <StatCard label="Total Pages" :value="dashboard.stats.pages_total" />
      <StatCard label="Published Pages" :value="dashboard.stats.pages_published" />
      <StatCard label="Categories" :value="dashboard.stats.categories_total" />
      <StatCard label="Tags" :value="dashboard.stats.tags_total" />
    </div>

    <div class="grid-2">
      <BaseCard title="Recent Posts">
        <div v-if="dashboard.loading">Loading...</div>
        <div v-else-if="!dashboard.recentPosts.length" class="empty-state">
          No recent posts found.
        </div>
        <div v-else class="stack-list">
          <div
            v-for="item in dashboard.recentPosts"
            :key="item.id"
            class="list-row"
          >
            <div>
              <strong>{{ item.title }}</strong>
              <div class="muted">{{ item.author }} • {{ item.category || 'No category' }}</div>
            </div>
            <div class="pill">{{ item.status }}</div>
          </div>
        </div>
      </BaseCard>

      <BaseCard title="Recent Pages">
        <div v-if="dashboard.loading">Loading...</div>
        <div v-else-if="!dashboard.recentPages.length" class="empty-state">
          No recent pages found.
        </div>
        <div v-else class="stack-list">
          <div
            v-for="item in dashboard.recentPages"
            :key="item.id"
            class="list-row"
          >
            <div>
              <strong>{{ item.title }}</strong>
              <div class="muted">{{ item.author }}</div>
            </div>
            <div class="pill">{{ item.status }}</div>
          </div>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useDashboardStore } from '@/cms/stores/dashboard'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import StatCard from '@/cms/components/ui/StatCard.vue'
import BaseCard from '@/cms/components/ui/BaseCard.vue'

const dashboard = useDashboardStore()

onMounted(() => {
  dashboard.fetchDashboard()
})
</script>
