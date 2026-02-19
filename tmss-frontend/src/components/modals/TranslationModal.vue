<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  show: Boolean,
  translation: Object,
  locales: Array,
  tags: Array,
  isEdit: Boolean
})

const emit = defineEmits(['close', 'save'])

const form = ref({
  key: '',
  en: '',
  fr: '',
  es: '',
  de: '',
  tag_ids: []
})

watch(() => props.translation, (t) => {
  if (t) {
    form.value = {
      key: t.key || '',
      en: t.en || '',
      fr: t.fr || '',
      es: t.es || '',
      de: t.de || '',
      tag_ids: t.tags || []
    }
  }
}, { immediate: true })

const handleSubmit = () => {
  emit('save', { ...form.value })
}

const toggleTag = (tagId) => {
  const index = form.value.tag_ids.indexOf(tagId)
  if (index === -1) {
    form.value.tag_ids.push(tagId)
  } else {
    form.value.tag_ids.splice(index, 1)
  }
}
</script>

<template>
  <div v-if="show" class="modal-overlay" @click.self="emit('close')">
    <div class="modal">
      <div class="modal-header">
        <h3>{{ isEdit ? 'Edit Translation' : 'Add Translation' }}</h3>
        <button class="close-btn" @click="emit('close')">&times;</button>
      </div>

      <form @submit.prevent="handleSubmit" class="modal-body">
        <div class="form-group">
          <label>Key</label>
          <input v-model="form.key" type="text" required placeholder="e.g., common.welcome">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>English</label>
            <input v-model="form.en" type="text" placeholder="English value">
          </div>
          <div class="form-group">
            <label>French</label>
            <input v-model="form.fr" type="text" placeholder="French value">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Spanish</label>
            <input v-model="form.es" type="text" placeholder="Spanish value">
          </div>
          <div class="form-group">
            <label>German</label>
            <input v-model="form.de" type="text" placeholder="German value">
          </div>
        </div>

        <div class="form-group">
          <label>Tags</label>
          <div class="tags-selector">
            <button
              v-for="tag in tags"
              :key="tag.id"
              type="button"
              class="tag-btn"
              :class="{ selected: form.tag_ids.includes(tag.name) }"
              :style="{ 
                '--tag-color': tag.color,
                backgroundColor: form.tag_ids.includes(tag.name) ? tag.color : 'transparent',
                borderColor: tag.color,
                color: form.tag_ids.includes(tag.name) ? 'white' : tag.color
              }"
              @click="toggleTag(tag.name)"
            >
              {{ tag.name }}
            </button>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="emit('close')">Cancel</button>
          <button type="submit" class="btn btn-primary">{{ isEdit ? 'Update' : 'Create' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: white;
  border-radius: 16px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
  font-size: 18px;
  font-weight: 600;
  color: #1e293b;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  color: #64748b;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}

.close-btn:hover {
  color: #1e293b;
}

.modal-body {
  padding: 24px;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  color: #374151;
  margin-bottom: 6px;
}

.form-group input {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.2s;
}

.form-group input:focus {
  outline: none;
  border-color: #3b82f6;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.tags-selector {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.tag-btn {
  padding: 6px 14px;
  border: 2px solid;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.tag-btn.selected {
  transform: scale(1.05);
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
  padding-top: 16px;
  border-top: 1px solid #e2e8f0;
}

.btn {
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary {
  background: white;
  border: 1px solid #e2e8f0;
  color: #475569;
}

.btn-secondary:hover {
  background: #f8fafc;
}

.btn-primary {
  background: #3b82f6;
  border: none;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}

@media (max-width: 480px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>
