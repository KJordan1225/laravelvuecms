<template>
  <button
    :type="type"
    class="btn"
    :class="variantClass"
    :disabled="disabled || loading"
  >
    <span v-if="loading">Working...</span>
    <span v-else><slot /></span>
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'button',
  },
  variant: {
    type: String,
    default: 'primary',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const variantClass = computed(() => {
  return {
    'btn-primary': props.variant === 'primary',
    'btn-secondary': props.variant === 'secondary',
    'btn-danger': props.variant === 'danger',
    'btn-outline': props.variant === 'outline',
  }
})
</script>
