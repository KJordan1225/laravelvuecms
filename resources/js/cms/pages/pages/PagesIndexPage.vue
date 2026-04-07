<template>
  <div>
    <PageHeader
      title="Pages"
      subtitle="Manage static and marketing pages."
    >
      <template #actions>
        <RouterLink :to="{ name: 'admin.pages.create' }" class="btn btn-primary">
          New Page
        </RouterLink>
      </template>
    </PageHeader>

    <BaseCard>
      <div class="toolbar">
        <input
          v-model="filters.search"
          class="form-control"
          placeholder="Search pages..."
          @keyup.enter="load"
        >
        <button class="btn btn-outline" @click="load">Search</button>
      </div>

      <div v-if="pages.loading">Loading pages...</div>
      <div v-else-if="!pages.items.length" class="empty-state">No pages found.</div>

      <div v-else class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Author</th>
              <th>Published</th>
              <th width="180">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in pages.items" :key="item.id">
              <td>
                <div class="table-title">{{ item.title }}</div>
                <div class="muted">{{ item.slug }}</div>
              </td>
              <td><span class="pill">{{ item.status }}</span></td>
              <td>{{ item.author?.name || '—' }}</td>
              <td>{{ item.published_at || '—' }}</td>
              <td>
                <div class="table-actions">
                  <RouterLink
                    :to="{ name: 'admin.pages.edit', params: { id: item.id } }"
                    class="btn btn-secondary"
                  >
                    Edit
                  </RouterLink>
                  <button class="btn btn-danger" @click="destroy(item.id)">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue'
import { usePagesStore } from '@/cms/stores/pages'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import PageHeader from '@/cms/components/ui/PageHeader.vue'

const pages = usePagesStore()

const filters = reactive({
  search: '',
})

const load = () => {
  pages.fetchAll({ search: filters.search })
}

const destroy = async (id) => {
  if (!window.confirm('Delete this page?')) return
  await pages.delete(id)
}

onMounted(load)
</script>
