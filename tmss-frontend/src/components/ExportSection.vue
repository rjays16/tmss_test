<script setup>
import { ref } from 'vue'

defineProps({
  tags: {
    type: Array,
    default: () => []
  }
})

const selectedLocales = ref(['en', 'fr', 'es'])
const selectedTag = ref('')

const emit = defineEmits(['export'])

const exportJson = () => {
  emit('export', {
    locales: selectedLocales.value,
    tag: selectedTag.value
  })
}
</script>

<template>
  <div class="export-section">
    <div class="export-card">
      <h3>Export Translations</h3>
      <p>Download your translations in JSON format for frontend applications</p>
      <div class="export-options">
        <div class="form-group">
          <label>Select Locales</label>
          <div class="checkbox-group">
            <label>
              <input type="checkbox" value="en" v-model="selectedLocales"> English (en)
            </label>
            <label>
              <input type="checkbox" value="fr" v-model="selectedLocales"> French (fr)
            </label>
            <label>
              <input type="checkbox" value="es" v-model="selectedLocales"> Spanish (es)
            </label>
          </div>
        </div>
        <div class="form-group">
          <label>Filter by Tag</label>
          <select v-model="selectedTag">
            <option value="">All Tags</option>
            <option v-for="tag in tags" :key="tag.id" :value="tag.name">{{ tag.name }}</option>
          </select>
        </div>
      </div>
      <button class="btn btn-primary btn-large" @click="exportJson">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export JSON
      </button>
    </div>
  </div>
</template>

<style scoped>
.export-section {
  display: flex;
  justify-content: center;
}

.export-card {
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  max-width: 500px;
  width: 100%;
  text-align: center;
}

.export-card h3 {
  font-size: 24px;
  margin-bottom: 8px;
  color: #0f172a;
}

.export-card p {
  color: #64748b;
  margin-bottom: 32px;
}

.export-options {
  text-align: left;
  margin-bottom: 32px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 8px;
  color: #475569;
}

.checkbox-group label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 400;
  margin-bottom: 8px;
}

.form-group select {
  width: 100%;
  padding: 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 14px;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  border: none;
  transition: all 0.2s;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}

.btn-large {
  padding: 14px 28px;
  font-size: 16px;
}

@media (max-width: 768px) {
  .export-card {
    padding: 24px;
  }
  
  .export-card h3 {
    font-size: 20px;
  }
  
  .export-card p {
    margin-bottom: 24px;
  }
}

@media (max-width: 480px) {
  .export-card {
    padding: 16px;
  }
  
  .export-card h3 {
    font-size: 18px;
  }
  
  .btn-large {
    width: 100%;
    justify-content: center;
  }
}
</style>
