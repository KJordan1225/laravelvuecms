<template>
  <form @submit.prevent="submit">
    <div class="grid-2">
      <BaseInput
        v-model="form.title"
        label="Title"
        placeholder="Enter page title"
        :error="firstError(errors, 'title')"
      />

      <BaseInput
        v-model="form.slug"
        label="Slug"
        placeholder="page-slug"
        :error="firstError(errors, 'slug')"
      />
    </div>

    <div class="grid-2">
      <BaseInput
        v-model="form.template"
        label="Template"
        placeholder="default"
        :error="firstError(errors, 'template')"
      />

      <BaseSelect
        v-model="form.status"
        label="Status"
        placeholder="Choose status"
        :options="pageStatuses"
        :error="firstError(errors, 'status')"
      />
    </div>

    <BaseTextarea
      v-model="form.body"
      label="Body"
      placeholder="Write page content"
      :rows="12"
      :error="firstError(errors, 'body')"
    />

    <div class="grid-2">
      <BaseInput
        v-model="form.published_at"
        type="datetime-local"
        label="Publish At"
        :error="firstError(errors, 'published_at')"
      />

      <div></div>
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

    <div class="form-actions">
      <BaseButton type="submit" :loading="saving">
        {{ submitLabel }}
      </BaseButton>
    </div>
  </form>
</template>

<script setup>
import { reactive } from 'vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseTextarea from '@/cms/components/ui/BaseTextarea.vue'
import BaseSelect from '@/cms/components/ui/BaseSelect.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'
import { pageStatuses } from '@/cms/constants/pageStatuses'
import { useErrorMessage } from '@/cms/composables/useErrorMessage'

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
    default: 'Save Page',
  },
})

const emit = defineEmits(['submit', 'update:modelValue'])
const { firstError } = useErrorMessage()

const form = reactive({
  title: props.modelValue.title || '',
  slug: props.modelValue.slug || '',
  body: props.modelValue.body || '',
  template: props.modelValue.template || 'default',
  status: props.modelValue.status || 'draft',
  published_at: props.modelValue.published_at || '',
  seo_meta: {
    meta_title: props.modelValue.seo_meta?.meta_title || '',
    meta_description: props.modelValue.seo_meta?.meta_description || '',
  },
})

const submit = () => {
  emit('update:modelValue', { ...form })
  emit('submit', { ...form })
}
</script>
