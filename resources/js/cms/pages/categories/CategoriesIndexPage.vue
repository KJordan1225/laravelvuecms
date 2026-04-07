<template>
  <div>
    <PageHeader
      title="Categories"
      subtitle="Organize posts into categories."
    />

    <div class="grid-2">
      <BaseCard title="Create Category">
        <form @submit.prevent="createCategory">
          <BaseInput
            v-model="createForm.name"
            label="Name"
            placeholder="Category name"
            :error="firstError(categories.errors, 'name')"
          />

          <BaseInput
            v-model="createForm.slug"
            label="Slug"
            placeholder="category-slug"
            :error="firstError(categories.errors, 'slug')"
          />

          <BaseTextarea
            v-model="createForm.description"
            label="Description"
            placeholder="Short category description"
            :rows="3"
            :error="firstError(categories.errors, 'description')"
          />

          <div class="grid-2">
            <BaseInput
              v-model="createForm.sort_order"
              type="number"
              label="Sort Order"
              placeholder="0"
              :error="firstError(categories.errors, 'sort_order')"
            />

            <div class="form-group">
              <label class="form-label">&nbsp;</label>
              <BaseCheckbox
                v-model="createForm.is_active"
                label="Active"
              />
            </div>
          </div>

          <div class="form-actions">
            <BaseButton type="submit" :loading="categories.saving">
              Create Category
            </BaseButton>
          </div>
        </form>
      </BaseCard>

      <BaseCard title="Category List">
        <div class="toolbar">
          <input
            v-model="filters.search"
            class="form-control"
            placeholder="Search categories..."
            @keyup.enter="load"
          >
          <button class="btn btn-outline" @click="load">Search</button>
        </div>

        <div v-if="categories.loading">Loading categories...</div>
        <div v-else-if="!categories.items.length" class="empty-state">
          No categories found.
        </div>

        <div v-else class="stack-list">
          <div
            v-for="item in categories.items"
            :key="item.id"
            class="list-card"
          >
            <div class="list-card-main">
              <div class="list-card-title-row">
                <strong>{{ item.name }}</strong>
                <span class="pill">{{ item.is_active ? 'active' : 'inactive' }}</span>
              </div>

              <div class="muted">{{ item.slug }}</div>
              <div class="muted">{{ item.description || 'No description.' }}</div>
              <div class="muted">Posts: {{ item.posts_count || 0 }}</div>
            </div>

            <div class="list-card-actions">
              <button class="btn btn-secondary" @click="startEdit(item)">Edit</button>
              <button class="btn btn-danger" @click="destroy(item.id)">Delete</button>
            </div>
          </div>
        </div>
      </BaseCard>
    </div>

    <BaseCard v-if="editForm.id" title="Edit Category">
      <form @submit.prevent="updateCategory">
        <div class="grid-2">
          <BaseInput
            v-model="editForm.name"
            label="Name"
            placeholder="Category name"
            :error="firstError(categories.errors, 'name')"
          />

          <BaseInput
            v-model="editForm.slug"
            label="Slug"
            placeholder="category-slug"
            :error="firstError(categories.errors, 'slug')"
          />
        </div>

        <BaseTextarea
          v-model="editForm.description"
          label="Description"
          placeholder="Short category description"
          :rows="3"
          :error="firstError(categories.errors, 'description')"
        />

        <div class="grid-2">
          <BaseInput
            v-model="editForm.sort_order"
            type="number"
            label="Sort Order"
            placeholder="0"
            :error="firstError(categories.errors, 'sort_order')"
          />

          <div class="form-group">
            <label class="form-label">&nbsp;</label>
            <BaseCheckbox
              v-model="editForm.is_active"
              label="Active"
            />
          </div>
        </div>

        <div class="form-actions">
          <BaseButton type="submit" :loading="categories.saving">
            Update Category
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
import { useCategoriesStore } from '@/cms/stores/categories'
import { useErrorMessage } from '@/cms/composables/useErrorMessage'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseTextarea from '@/cms/components/ui/BaseTextarea.vue'
import BaseCheckbox from '@/cms/components/ui/BaseCheckbox.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'

const categories = useCategoriesStore()
const { firstError } = useErrorMessage()

const filters = reactive({
  search: '',
})

const createForm = reactive({
  name: '',
  slug: '',
  description: '',
  sort_order: 0,
  is_active: true,
})

const editForm = reactive({
  id: null,
  name: '',
  slug: '',
  description: '',
  sort_order: 0,
  is_active: true,
})

const load = () => {
  categories.fetchAll({ search: filters.search })
}

const createCategory = async () => {
  await categories.create({
    ...createForm,
    sort_order: Number(createForm.sort_order || 0),
  })

  createForm.name = ''
  createForm.slug = ''
  createForm.description = ''
  createForm.sort_order = 0
  createForm.is_active = true

  load()
}

const startEdit = (item) => {
  editForm.id = item.id
  editForm.name = item.name
  editForm.slug = item.slug
  editForm.description = item.description || ''
  editForm.sort_order = item.sort_order || 0
  editForm.is_active = !!item.is_active
}

const resetEdit = () => {
  editForm.id = null
  editForm.name = ''
  editForm.slug = ''
  editForm.description = ''
  editForm.sort_order = 0
  editForm.is_active = true
}

const updateCategory = async () => {
  await categories.update(editForm.id, {
    ...editForm,
    sort_order: Number(editForm.sort_order || 0),
  })

  resetEdit()
  load()
}

const destroy = async (id) => {
  if (!window.confirm('Delete this category?')) return
  await categories.delete(id)
}

onMounted(load)
</script>
