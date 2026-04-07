<template>
  <div>
    <PageHeader
      title="Edit Page"
      subtitle="Update page content and settings."
    />

    <BaseCard>
      <div v-if="pages.loading">Loading page...</div>

      <PageForm
        v-else
        v-model="form"
        :saving="pages.saving"
        :errors="pages.errors"
        submit-label="Update Page"
        @submit="submit"
      />
    </BaseCard>
  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { usePagesStore } from '@/cms/stores/pages'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import PageForm from '@/cms/components/pages/PageForm.vue'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
})

const router = useRouter()
const pages = usePagesStore()

const form = reactive({
  title: '',
  slug: '',
  body: '',
  template: 'default',
  status: 'draft',
  published_at: '',
  seo_meta: {
    meta_title: '',
    meta_description: '',
  },
})

const load = async () => {
  const data = await pages.fetchOne(props.id)

  form.title = data.title || ''
  form.slug = data.slug || ''
  form.body = data.body || ''
  form.template = data.template || 'default'
  form.status = data.status || 'draft'
  form.published_at = data.published_at || ''
  form.seo_meta = {
    meta_title: data.seo_meta?.meta_title || '',
    meta_description: data.seo_meta?.meta_description || '',
  }
}

const submit = async (payload) => {
  await pages.update(props.id, payload)
  router.push({ name: 'admin.pages.index' })
}

onMounted(load)
</script>
