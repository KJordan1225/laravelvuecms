<template>
  <div v-if="meta && (meta.links || meta.current_page)" class="pagination-wrap">
    <button
      class="btn btn-outline"
      :disabled="!meta.prev_page_url"
      @click="$emit('change', meta.current_page - 1)"
    >
      Previous
    </button>

    <span class="pagination-text">
      Page {{ meta.current_page }} of {{ meta.last_page }}
    </span>

    <button
      class="btn btn-outline"
      :disabled="!meta.next_page_url"
      @click="$emit('change', meta.current_page + 1)"
    >
      Next
    </button>
  </div>
</template>

<script setup>
defineProps({
  meta: {
    type: Object,
    default: null,
  },
})

defineEmits(['change'])
</script>
