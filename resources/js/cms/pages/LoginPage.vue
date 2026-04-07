<template>
  <div class="auth-wrap">
    <BaseCard title="Admin Login">
      <form @submit.prevent="submit">
        <BaseInput
          v-model="form.email"
          label="Email"
          type="email"
          placeholder="admin@example.com"
          :error="firstError(auth.errors, 'email')"
        />

        <BaseInput
          v-model="form.password"
          label="Password"
          type="password"
          placeholder="Enter password"
          :error="firstError(auth.errors, 'password')"
        />

        <BaseInput
          v-model="form.device_name"
          label="Device Name"
          placeholder="Chrome on Desktop"
        />

        <div class="form-actions">
          <BaseButton type="submit" :loading="auth.loading">
            Sign In
          </BaseButton>
        </div>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/cms/stores/auth'
import { useErrorMessage } from '@/cms/composables/useErrorMessage'
import BaseCard from '@/cms/components/ui/BaseCard.vue'
import BaseInput from '@/cms/components/ui/BaseInput.vue'
import BaseButton from '@/cms/components/ui/BaseButton.vue'

const router = useRouter()
const auth = useAuthStore()
const { firstError } = useErrorMessage()

const form = reactive({
  email: 'admin@example.com',
  password: 'password',
  device_name: 'cms-admin-browser',
})

const submit = async () => {
  try {
    await auth.login(form)
    router.push({ name: 'admin.dashboard' })
  } catch (error) {
    console.error(error)
  }
}
</script>
