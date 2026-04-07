<template>
  <div>
    <PageHeader
      title="Create Post"
      subtitle="Add a new post to your CMS."
    />

    <BaseCard>
      <PostForm
        v-model="form"
        :saving="posts.saving"
        :errors="posts.errors"
        submit-label="Create Post"
        @submit="submit"
      />
    </BaseCard>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { usePostsStore } from '@/cms/stores/posts'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import PostForm from '@/cms/components/posts/PostForm.vue'

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

const submit = async (payload) => {
  await posts.create(payload)
  router.push({ name: 'admin.posts.index' })
}
</script>
