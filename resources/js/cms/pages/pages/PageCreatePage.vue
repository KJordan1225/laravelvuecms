<template>
  <div>
    <PageHeader
      title="Create Page"
      subtitle="Add a new static page."
    />

    <BaseCard>
      <PageForm
        v-model="form"
        :saving="pages.saving"
        :errors="pages.errors"
        submit-label="Create Page"
        @submit="submit"
      />
    </BaseCard>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { usePagesStore } from '@/cms/stores/pages'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import PageForm from '@/cms/components/pages/PageForm.vue'

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

const submit = async (payload) => {
  await pages.create(payload)
  router.push({ name: 'admin.pages.index' })
}
</script>

