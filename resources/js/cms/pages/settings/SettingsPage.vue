<template>
  <div>
    <PageHeader
      title="Settings"
      subtitle="Manage site-wide CMS configuration."
    />

    <BaseCard title="General Settings">
      <div v-if="settings.loading">Loading settings...</div>

      <form v-else @submit.prevent="saveSettings">
        <div class="grid-2">
          <BaseInput
            v-model="form.site_name"
            label="Site Name"
            placeholder="My Laravel CMS"
          />

          <BaseInput
            v-model="form.site_tagline"
            label="Site Tagline"
            placeholder="A modern CMS"
          />
        </div>

        <BaseInput
          v-model="form.homepage_title"
          label="Homepage Title"
          placeholder="Welcome to the site"
        />

        <BaseInput
          v-model="form.contact_email"
          label="Contact Email"
          placeholder="contact@example.com"
        />

        <div class="form-actions">
          <BaseButton type="submit" :loading="settings.saving">
            Save Settings
          </BaseButton>
        </div>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue'
import { useSettingsStore } from '@/cms/stores/settings'
import PageHeader from '@/cms/components/ui/PageHeader.vue'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'

const settings = useSettingsStore()

const form = reactive({
  site_name: '',
  site_tagline: '',
  homepage_title: '',
  contact_email: '',
})

const applySettingsToForm = () => {
  const map = {}

  settings.items.forEach((item) => {
    map[item.key] = item.value
  })

  form.site_name = map.site_name || ''
  form.site_tagline = map.site_tagline || ''
  form.homepage_title = map.homepage_title || ''
  form.contact_email = map.contact_email || ''
}

const load = async () => {
  await settings.fetchAll()
  applySettingsToForm()
}

const saveSettings = async () => {
  await settings.update([
    {
      group: 'general',
      key: 'site_name',
      value: form.site_name,
      type: 'text',
    },
    {
      group: 'general',
      key: 'site_tagline',
      value: form.site_tagline,
      type: 'text',
    },
    {
      group: 'general',
      key: 'homepage_title',
      value: form.homepage_title,
      type: 'text',
    },
    {
      group: 'general',
      key: 'contact_email',
      value: form.contact_email,
      type: 'text',
    },
  ])

  applySettingsToForm()
  alert('Settings saved successfully.')
}

onMounted(load)
</script>
