<template>
  <div class="form-group">
    <label v-if="label" class="form-label">{{ label }}</label>

    <div class="editor-toolbar">
      <button type="button" class="btn btn-outline" @click="exec('bold')">Bold</button>
      <button type="button" class="btn btn-outline" @click="exec('italic')">Italic</button>
      <button type="button" class="btn btn-outline" @click="exec('insertUnorderedList')">List</button>
      <button type="button" class="btn btn-outline" @click="insertLink">Link</button>
    </div>

    <div
      ref="editor"
      class="rich-editor"
      contenteditable="true"
      @input="emitValue"
    />

    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  label: {
    type: String,
    default: '',
  },
  error: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])
const editor = ref(null)

const emitValue = () => {
  emit('update:modelValue', editor.value?.innerHTML || '')
}

const exec = (command) => {
  document.execCommand(command, false, null)
  emitValue()
}

const insertLink = () => {
  const url = window.prompt('Enter URL')
  if (!url) return
  document.execCommand('createLink', false, url)
  emitValue()
}

onMounted(() => {
  if (editor.value) {
    editor.value.innerHTML = props.modelValue || ''
  }
})

watch(
  () => props.modelValue,
  (value) => {
    if (editor.value && editor.value.innerHTML !== value) {
      editor.value.innerHTML = value || ''
    }
  }
)
</script>
