<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  translations: {
    type: Array,
    default: () => []
  },
  tags: {
    type: Array,
    default: () => []
  },
  locales: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['add', 'edit', 'delete'])

const searchQuery = ref('')
const selectedTag = ref('')
const showLanguageSelector = ref(false)

const defaultLocales = ['en', 'fr', 'es', 'de']
const availableLocales = computed(() => {
  if (props.locales.length > 0) {
    return props.locales.map(l => l.code)
  }
  return defaultLocales
})

const selectedLocales = ref([...availableLocales.value])

const localeNames = {
  en: 'English',
  fr: 'French',
  es: 'Spanish',
  de: 'German',
  it: 'Italian',
  pt: 'Portuguese',
  zh: 'Chinese',
  ja: 'Japanese',
  ko: 'Korean',
  ar: 'Arabic'
}

const filteredTranslations = computed(() => {
  const query = searchQuery.value.toLowerCase()
  const searchFields = ['key', ...selectedLocales.value]
  
  return props.translations.filter(t => {
    const matchesSearch = !query || searchFields.some(field => {
      const value = t[field]
      return value && value.toLowerCase().includes(query)
    })
    const matchesTag = !selectedTag.value || t.tags?.includes(selectedTag.value)
    return matchesSearch && matchesTag
  })
})

const getTagColor = (tagName) => {
  const tag = props.tags.find(t => t.name === tagName)
  return tag?.color || '#6B7280'
}

const toggleLocale = (locale) => {
  const index = selectedLocales.value.indexOf(locale)
  if (index === -1) {
    selectedLocales.value.push(locale)
  } else if (selectedLocales.value.length > 1) {
    selectedLocales.value.splice(index, 1)
  }
}

const isLocaleSelected = (locale) => {
  return selectedLocales.value.includes(locale)
}
</script>

<template>
  <div class="translations-section">
    <div class="filters">
      <div class="search-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" v-model="searchQuery" placeholder="Search translations...">
      </div>
      <select v-model="selectedTag" class="tag-filter">
        <option value="">All Tags</option>
        <option v-for="tag in tags" :key="tag.id" :value="tag.name">{{ tag.name }}</option>
      </select>
      
      <div class="language-selector">
        <button class="language-toggle" @click="showLanguageSelector = !showLanguageSelector">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          Languages ({{ selectedLocales.length }})
        </button>
        <div v-if="showLanguageSelector" class="language-dropdown">
          <label v-for="locale in availableLocales" :key="locale" class="language-option">
            <input 
              type="checkbox" 
              :checked="isLocaleSelected(locale)"
              @change="toggleLocale(locale)"
            >
            <span class="locale-code">{{ locale }}</span>
            <span class="locale-name">{{ localeNames[locale] || locale }}</span>
          </label>
        </div>
      </div>
    </div>

    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Key</th>
            <th v-for="locale in selectedLocales" :key="locale">
              {{ localeNames[locale] || locale }}
            </th>
            <th>Tags</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in filteredTranslations" :key="t.id">
            <td class="key-cell">{{ t.key }}</td>
            <td v-for="locale in selectedLocales" :key="locale">
              {{ t[locale] || '-' }}
            </td>
            <td>
              <div class="tag-list">
                <span v-for="tagName in t.tags" :key="tagName" class="tag" :style="{ backgroundColor: getTagColor(tagName) }">
                  {{ tagName }}
                </span>
              </div>
            </td>
            <td>
              <div class="action-buttons">
                <button class="icon-btn edit" title="Edit" @click="emit('edit', t)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="icon-btn delete" title="Delete" @click="emit('delete', t)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.filters {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.search-box {
  flex: 1;
  min-width: 200px;
  max-width: 400px;
  position: relative;
}

.search-box svg {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
}

.search-box input {
  width: 100%;
  padding: 12px 12px 12px 44px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 14px;
  transition: all 0.2s;
}

.search-box input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.tag-filter {
  padding: 12px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 14px;
  background: white;
  cursor: pointer;
}

.language-selector {
  position: relative;
}

.language-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.language-toggle:hover {
  background: #f8fafc;
}

.language-dropdown {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  min-width: 200px;
  z-index: 100;
  padding: 8px;
}

.language-option {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
}

.language-option:hover {
  background: #f8fafc;
}

.language-option input {
  width: 16px;
  height: 16px;
  cursor: pointer;
}

.language-option .locale-code {
  font-weight: 600;
  color: #3b82f6;
  min-width: 30px;
}

.language-option .locale-name {
  color: #64748b;
  font-size: 13px;
}

.table-container {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  text-align: left;
  padding: 16px 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  color: #64748b;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.data-table td {
  padding: 16px 20px;
  font-size: 14px;
  border-bottom: 1px solid #f1f5f9;
}

.data-table tr:hover {
  background: #f8fafc;
}

.key-cell {
  font-family: monospace;
  font-weight: 600;
  color: #3b82f6;
}

.tag-list {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}

.tag {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  color: white;
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.icon-btn {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.icon-btn.edit {
  background: #eff6ff;
  color: #3b82f6;
}

.icon-btn.edit:hover {
  background: #3b82f6;
  color: white;
}

.icon-btn.delete {
  background: #fef2f2;
  color: #ef4444;
}

.icon-btn.delete:hover {
  background: #ef4444;
  color: white;
}

@media (max-width: 768px) {
  .filters {
    flex-direction: column;
    gap: 12px;
  }
  
  .search-box {
    max-width: 100%;
  }
  
  .tag-filter {
    width: 100%;
  }
  
  .table-container {
    overflow-x: auto;
  }
  
  .data-table {
    min-width: 600px;
  }
  
  .data-table th,
  .data-table td {
    padding: 12px;
  }
}

@media (max-width: 480px) {
  .data-table th,
  .data-table td {
    padding: 8px;
    font-size: 12px;
  }
  
  .key-cell {
    font-size: 11px;
  }
  
  .tag {
    padding: 2px 6px;
    font-size: 10px;
  }
}
</style>
