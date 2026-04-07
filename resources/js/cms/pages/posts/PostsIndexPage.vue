<template>
  <div>
    <PageHeader
      title="Posts"
      subtitle="Create and manage your blog posts."
    >
      <template #actions>
        <RouterLink :to="{ name: 'admin.posts.create' }" class="btn btn-primary">
          New Post
        </RouterLink>
      </template>
    </PageHeader>

    <BaseCard>
      <div class="toolbar">
        <input
          v-model="filters.search"
          class="form-control"
          placeholder="Search posts..."
          @keyup.enter="load"
        >
        <button class="btn btn-outline" @click="load">Search</button>
      </div>

      <div v-if="posts.loading">Loading posts...</div>
      <div v-else-if="!posts.items.length" class="empty-state">No posts found.</div>

      <div v-else class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Category</th>
              <th>Author</th>
              <th>Published</th>
              <th width="180">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in posts.items" :key="item.id">
              <td>
                <div class="table-title">{{ item.title }}</div>
                <div class="muted">{{ item.slug }}</div>
              </td>
              <td><span class="pill">{{ item.status }}</span></td>
              <td>{{ item.category?.name || '—' }}</td>
              <td>{{ item.author?.name || '—' }}</td>
              <td>{{ item.published_at || '—' }}</td>
              <td>
                <div class="table-actions">
                  <RouterLink
                    :to="{ name: 'admin.posts.edit', params: { id: item.id } }"
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
import { usePostsStore } from '@/cms/stores/posts'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import PageHeader from '@/cms/components/ui/PageHeader.vue'

const posts = usePostsStore()

const filters = reactive({
  search: '',
})

const load = () => {
  posts.fetchAll({ search: filters.search })
}

const destroy = async (id) => {
  if (!window.confirm('Delete this post?')) return
  await posts.delete(id)
}

onMounted(load)
</script>
