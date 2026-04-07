<template>
  <div class="stat-card">
    <p class="stat-label">{{ label }}</p>
    <h3 class="stat-value">{{ value }}</h3>
  </div>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number],
    required: true,
  },
})
</script>
