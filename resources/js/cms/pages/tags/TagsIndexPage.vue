<template>
  <div>
    <PageHeader
      title="Tags"
      subtitle="Manage reusable post tags."
    />

    <div class="grid-2">
      <BaseCard title="Create Tag">
        <form @submit.prevent="createTag">
          <BaseInput
            v-model="createForm.name"
            label="Name"
            placeholder="Tag name"
            :error="firstError(tags.errors, 'name')"
          />

          <BaseInput
            v-model="createForm.slug"
            label="Slug"
            placeholder="tag-slug"
            :error="firstError(tags.errors, 'slug')"
          />

          <div class="form-actions">
            <BaseButton type="submit" :loading="tags.saving">
              Create Tag
            </BaseButton>
          </div>
        </form>
      </BaseCard>

      <BaseCard title="Tag List">
        <div class="toolbar">
          <input
            v-model="filters.search"
            class="form-control"
            placeholder="Search tags..."
            @keyup.enter="load"
          >
          <button class="btn btn-outline" @click="load">Search</button>
        </div>

        <div v-if="tags.loading">Loading tags...</div>
        <div v-else-if="!tags.items.length" class="empty-state">
          No tags found.
        </div>

        <div v-else class="stack-list">
          <div
            v-for="item in tags.items"
            :key="item.id"
            class="list-card"
          >
            <div class="list-card-main">
              <strong>{{ item.name }}</strong>
              <div class="muted">{{ item.slug }}</div>
            </div>

            <div class="list-card-actions">
              <button class="btn btn-secondary" @click="startEdit(item)">Edit</button>
              <button class="btn btn-danger" @click="destroy(item.id)">Delete</button>
            </div>
          </div>
        </div>
      </BaseCard>
    </div>

    <BaseCard v-if="editForm.id" title="Edit Tag">
      <form @submit.prevent="updateTag">
        <div class="grid-2">
          <BaseInput
            v-model="editForm.name"
            label="Name"
            placeholder="Tag name"
            :error="firstError(tags.errors, 'name')"
          />

          <BaseInput
            v-model="editForm.slug"
            label="Slug"
            placeholder="tag-slug"
            :error="firstError(tags.errors, 'slug')"
          />
        </div>

        <div class="form-actions">
          <BaseButton type="submit" :loading="tags.saving">
            Update Tag
          </BaseButton>
          <BaseButton type="button" variant="outline" @click="resetEdit">
            Cancel
          </BaseButton>
        </div>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue'
import { useTagsStore } from '@/cms/stores/tags'
import { useErrorMessage } from '@/cms/composables/useErrorMessage'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'

const tags = useTagsStore()
const { firstError } = useErrorMessage()

const filters = reactive({
  search: '',
})

const createForm = reactive({
  name: '',
  slug: '',
})

const editForm = reactive({
  id: null,
  name: '',
  slug: '',
})

const load = () => {
  tags.fetchAll({ search: filters.search })
}

const createTag = async () => {
  await tags.create({ ...createForm })
  createForm.name = ''
  createForm.slug = ''
  load()
}

const startEdit = (item) => {
  editForm.id = item.id
  editForm.name = item.name
  editForm.slug = item.slug
}

const resetEdit = () => {
  editForm.id = null
  editForm.name = ''
  editForm.slug = ''
}

const updateTag = async () => {
  await tags.update(editForm.id, { ...editForm })
  resetEdit()
  load()
}

const destroy = async (id) => {
  if (!window.confirm('Delete this tag?')) return
  await tags.delete(id)
}

onMounted(load)
</script>
