<template>
  <div>
    <PageHeader
      title="Edit Post"
      subtitle="Update post details and content."
    />

    <BaseCard>
      <div v-if="posts.loading">Loading post...</div>

      <PostForm
        v-else
        v-model="form"
        :saving="posts.saving"
        :errors="posts.errors"
        submit-label="Update Post"
        @submit="submit"
      />
    </BaseCard>
  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { usePostsStore } from '@/cms/stores/posts'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import PostForm from '@/cms/components/posts/PostForm.vue'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
})

const router = useRouter()
const posts = usePostsStore()

const form = reactive({
  title: '',
  slug: '',
  category_id: '',
  excerpt: '',
  body: '',
  featured_image: '',
  status: 'draft',
  is_featured: false,
  allow_comments: true,
  published_at: '',
  seo_meta: {
    meta_title: '',
    meta_description: '',
  },
  tag_ids: [],
})

const load = async () => {
  const data = await posts.fetchOne(props.id)

  form.title = data.title || ''
  form.slug = data.slug || ''
  form.category_id = data.category_id || ''
  form.excerpt = data.excerpt || ''
  form.body = data.body || ''
  form.featured_image = data.featured_image || ''
  form.status = data.status || 'draft'
  form.is_featured = !!data.is_featured
  form.allow_comments = data.allow_comments ?? true
  form.published_at = data.published_at || ''
  form.seo_meta = {
    meta_title: data.seo_meta?.meta_title || '',
    meta_description: data.seo_meta?.meta_description || '',
  }
  form.tag_ids = Array.isArray(data.tags) ? data.tags.map((tag) => tag.id) : []
}

const submit = async (payload) => {
  await posts.update(props.id, payload)
  router.push({ name: 'admin.posts.index' })
}

onMounted(load)
</script>
