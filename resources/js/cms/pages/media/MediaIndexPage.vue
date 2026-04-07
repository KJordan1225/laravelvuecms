<template>
  <div>
    <PageHeader
      title="Media Library"
      subtitle="Upload and manage files."
    />

    <BaseCard title="Upload Media">
      <form @submit.prevent="uploadFile">
        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">File</label>
            <input
              type="file"
              class="form-control"
              @change="onFileChange"
            >
            <div v-if="firstError(media.errors, 'file')" class="form-error">
              {{ firstError(media.errors, 'file') }}
            </div>
          </div>

          <BaseInput
            v-model="uploadForm.title"
            label="Title"
            placeholder="Optional media title"
            :error="firstError(media.errors, 'title')"
          />
        </div>

        <BaseInput
          v-model="uploadForm.alt_text"
          label="Alt Text"
          placeholder="Optional alt text"
          :error="firstError(media.errors, 'alt_text')"
        />

        <div class="form-actions">
          <BaseButton type="submit" :loading="media.uploading">
            Upload File
          </BaseButton>
        </div>
      </form>
    </BaseCard>

    <BaseCard title="Media Files">
      <div class="toolbar">
        <input
          v-model="filters.search"
          class="form-control"
          placeholder="Search media..."
          @keyup.enter="load"
        >
        <button class="btn btn-outline" @click="load">Search</button>
      </div>

      <div v-if="media.loading">Loading media...</div>
      <div v-else-if="!media.items.length" class="empty-state">
        No media files found.
      </div>

      <div v-else class="media-grid">
        <div
          v-for="item in media.items"
          :key="item.id"
          class="media-card"
        >
          <div class="media-preview">
            <img
              v-if="item.mime_type && item.mime_type.startsWith('image/')"
              :src="item.url"
              :alt="item.alt_text || item.filename"
            >
            <div v-else class="media-file-icon">
              FILE
            </div>
          </div>

          <div class="media-body">
            <div class="media-title">
              {{ item.title || item.filename }}
            </div>
            <div class="muted small-text">{{ item.mime_type || 'Unknown type' }}</div>
            <div class="muted small-text">{{ item.url }}</div>

            <div class="form-actions">
              <a :href="item.url" target="_blank" class="btn btn-secondary">Open</a>
              <button class="btn btn-danger" @click="destroy(item.id)">
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue'
import { useMediaStore } from '@/cms/stores/media'
import { useErrorMessage } from '@/cms/composables/useErrorMessage'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'

const media = useMediaStore()
const { firstError } = useErrorMessage()

const filters = reactive({
  search: '',
})

const uploadForm = reactive({
  file: null,
  title: '',
  alt_text: '',
})

const load = () => {
  media.fetchAll({ search: filters.search })
}

const onFileChange = (event) => {
  uploadForm.file = event.target.files?.[0] || null
}

const uploadFile = async () => {
  if (!uploadForm.file) {
    alert('Please choose a file first.')
    return
  }

  await media.upload({
    file: uploadForm.file,
    title: uploadForm.title,
    alt_text: uploadForm.alt_text,
  })

  uploadForm.file = null
  uploadForm.title = ''
  uploadForm.alt_text = ''
  document.querySelector('input[type="file"]').value = ''
  load()
}

const destroy = async (id) => {
  if (!window.confirm('Delete this media file?')) return
  await media.delete(id)
}

onMounted(load)
</script>
