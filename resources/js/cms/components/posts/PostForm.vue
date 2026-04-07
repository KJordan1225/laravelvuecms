<template>
  <form @submit.prevent="submit">
    <div class="grid-2">
      <BaseInput
        v-model="form.title"
        label="Title"
        placeholder="Enter post title"
        :error="firstError(errors, 'title')"
      />

      <BaseInput
        v-model="form.slug"
        label="Slug"
        placeholder="post-slug"
        :error="firstError(errors, 'slug')"
      />
    </div>

    <div class="grid-2">
      <BaseSelect
        v-model="form.category_id"
        label="Category"
        placeholder="Choose category"
        :options="categoryOptions"
        :error="firstError(errors, 'category_id')"
      />

      <BaseSelect
        v-model="form.status"
        label="Status"
        placeholder="Choose status"
        :options="postStatuses"
        :error="firstError(errors, 'status')"
      />
    </div>

    <BaseTextarea
      v-model="form.excerpt"
      label="Excerpt"
      placeholder="Short excerpt"
      :rows="3"
      :error="firstError(errors, 'excerpt')"
    />

    <RichEditor
      v-model="form.body"
      label="Body"
      :error="firstError(errors, 'body')"
    />

    <div class="grid-2">
      <BaseInput
        v-model="form.featured_image"
        label="Featured Image URL"
        placeholder="/storage/uploads/example.jpg"
        :error="firstError(errors, 'featured_image')"
      />

      <BaseInput
        v-model="form.published_at"
        type="datetime-local"
        label="Publish At"
        :error="firstError(errors, 'published_at')"
      />
    </div>

    <div class="grid-2">
      <BaseInput
        v-model="form.seo_meta.meta_title"
        label="SEO Meta Title"
        placeholder="Meta title"
        :error="firstError(errors, 'seo_meta.meta_title')"
      />

      <BaseInput
        v-model="form.seo_meta.meta_description"
        label="SEO Meta Description"
        placeholder="Meta description"
        :error="firstError(errors, 'seo_meta.meta_description')"
      />
    </div>

    <div class="grid-2">
      <BaseCheckbox
        v-model="form.is_featured"
        label="Featured post"
      />

      <BaseCheckbox
        v-model="form.allow_comments"
        label="Allow comments"
      />
    </div>

    <div class="form-group">
      <label class="form-label">Tags</label>
      <div class="tag-grid">
        <label
          v-for="tag in tags"
          :key="tag.id"
          class="tag-check"
        >
          <input
            type="checkbox"
            :value="tag.id"
            :checked="form.tag_ids.includes(tag.id)"
            @change="toggleTag(tag.id)"
          >
          <span>{{ tag.name }}</span>
        </label>
      </div>
      <div v-if="firstError(errors, 'tag_ids')" class="form-error">
        {{ firstError(errors, 'tag_ids') }}
      </div>
    </div>

    <div class="form-actions">
      <BaseButton type="submit" :loading="saving">
        {{ submitLabel }}
      </BaseButton>
    </div>
  </form>
</template>

<script setup>
import { computed, onMounted, reactive } from 'vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseTextarea from '@/cms/components/ui/BaseTextarea.vue'
import BaseSelect from '@/cms/components/ui/BaseSelect.vue'
import BaseCheckbox from '@/cms/components/ui/BaseCheckbox.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'
import { postStatuses } from '@/cms/constants/postStatuses'
import { useCategoriesStore } from '@/cms/stores/categories'
import { useTagsStore } from '@/cms/stores/tags'
import { useErrorMessage } from '@/cms/composables/useErrorMessage'
import RichEditor from '@/cms/components/ui/RichEditor.vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  saving: {
    type: Boolean,
    default: false,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
  submitLabel: {
    type: String,
    default: 'Save Post',
  },
})

const emit = defineEmits(['submit', 'update:modelValue'])

const categoriesStore = useCategoriesStore()
const tagsStore = useTagsStore()
const { firstError } = useErrorMessage()

const form = reactive({
  title: props.modelValue.title || '',
  slug: props.modelValue.slug || '',
  category_id: props.modelValue.category_id || '',
  excerpt: props.modelValue.excerpt || '',
  body: props.modelValue.body || '',
  featured_image: props.modelValue.featured_image || '',
  status: props.modelValue.status || 'draft',
  is_featured: !!props.modelValue.is_featured,
  allow_comments: props.modelValue.allow_comments ?? true,
  published_at: props.modelValue.published_at || '',
  seo_meta: {
    meta_title: props.modelValue.seo_meta?.meta_title || '',
    meta_description: props.modelValue.seo_meta?.meta_description || '',
  },
  tag_ids: props.modelValue.tag_ids || [],
})

const categoryOptions = computed(() =>
  categoriesStore.items.map((item) => ({
    label: item.name,
    value: item.id,
  }))
)

const tags = computed(() => tagsStore.items)

const submit = () => {
  emit('update:modelValue', { ...form })
  emit('submit', { ...form })
}

const toggleTag = (tagId) => {
  if (form.tag_ids.includes(tagId)) {
    form.tag_ids = form.tag_ids.filter((id) => id !== tagId)
  } else {
    form.tag_ids = [...form.tag_ids, tagId]
  }
}

onMounted(async () => {
  await Promise.all([
    categoriesStore.fetchAll({ per_page: 100 }),
    tagsStore.fetchAll({ per_page: 100 }),
  ])
})
</script>
