<script setup>
import { ref, onMounted, watch } from 'vue'
import DefaultLayout from './layouts/DefaultLayout.vue'
import HeaderSection from './components/HeaderSection.vue'
import TranslationsSection from './components/TranslationsSection.vue'
import TranslationModal from './components/modals/TranslationModal.vue'
import LocalesSection from './components/LocalesSection.vue'
import TagsSection from './components/TagsSection.vue'
import ExportSection from './components/ExportSection.vue'
import api from './services/api'

const activeTab = ref('translations')
const loading = ref(false)
const error = ref(null)

const translations = ref([])
const locales = ref([])
const tags = ref([])

const showModal = ref(false)
const editingTranslation = ref(null)

const fetchTranslations = async () => {
  try {
    loading.value = true
    const response = await api.getTranslations()
    const allTranslations = response.data
    
    const grouped = {}
    for (const t of allTranslations) {
      const key = t.key
      if (!grouped[key]) {
        grouped[key] = {
          ids: [],
          key: key,
          tags: [],
          tag_ids: []
        }
      }
      grouped[key].ids.push(t.id)
      const lang = t.locale?.code || t.locale
      grouped[key][lang] = t.value
      if (t.tags) {
        for (const tag of t.tags) {
          if (!grouped[key].tags.includes(tag.name)) {
            grouped[key].tags.push(tag.name)
          }
          if (!grouped[key].tag_ids.includes(tag.id)) {
            grouped[key].tag_ids.push(tag.id)
          }
        }
      }
    }
    translations.value = Object.values(grouped)
  } catch (e) {
    error.value = e.message
    console.error('Error fetching translations:', e)
  } finally {
    loading.value = false
  }
}

const fetchLocales = async () => {
  try {
    loading.value = true
    const response = await api.getAllLocales()
    locales.value = response.data.map(l => ({
      id: l.id,
      code: l.code,
      name: l.name,
      is_active: l.is_active
    }))
  } catch (e) {
    error.value = e.message
    console.error('Error fetching locales:', e)
  } finally {
    loading.value = false
  }
}

const fetchTags = async () => {
  try {
    loading.value = true
    const response = await api.getTags()
    tags.value = response.data.map(t => ({
      id: t.id,
      name: t.name,
      color: t.color
    }))
  } catch (e) {
    error.value = e.message
    console.error('Error fetching tags:', e)
  } finally {
    loading.value = false
  }
}

const fetchData = () => {
  if (activeTab.value === 'translations') {
    fetchTranslations()
    fetchTags()
    fetchLocales()
  }
  if (activeTab.value === 'locales') fetchLocales()
  if (activeTab.value === 'tags') fetchTags()
  if (activeTab.value === 'export') fetchTags()
}

onMounted(() => {
  fetchData()
})

watch(activeTab, () => {
  fetchData()
})

const getTitle = () => {
  return activeTab.value.charAt(0).toUpperCase() + activeTab.value.slice(1)
}

const openAddModal = () => {
  editingTranslation.value = null
  showModal.value = true
}

const openEditModal = (translation) => {
  editingTranslation.value = translation
  showModal.value = true
}

const handleSaveTranslation = async (form) => {
  try {
    loading.value = true
    
    if (locales.value.length === 0) {
      await fetchLocales()
    }
    
    const localeMap = {}
    locales.value.forEach(locale => {
      localeMap[locale.code] = locale.id
    })
    
    if (editingTranslation.value) {
      const existing = translations.value.find(t => t.key === form.key)
      if (existing) {
        for (const id of existing.ids) {
          await api.deleteTranslation(id)
        }
        for (const [lang, value] of Object.entries(form)) {
          if (lang !== 'key' && lang !== 'tag_ids' && value && localeMap[lang]) {
            await api.createTranslation({
              key: form.key,
              value: value,
              locale_id: localeMap[lang],
              tag_ids: form.tag_ids
            })
          }
        }
      }
    } else {
      for (const [lang, value] of Object.entries(form)) {
        if (lang !== 'key' && lang !== 'tag_ids' && value && localeMap[lang]) {
          await api.createTranslation({
            key: form.key,
            value: value,
            locale_id: localeMap[lang],
            tag_ids: form.tag_ids
          })
        }
      }
    }
    showModal.value = false
    fetchTranslations()
  } catch (e) {
    error.value = e.message
    console.error('Error saving translation:', e)
  } finally {
    loading.value = false
  }
}

const handleDeleteTranslation = async (translation) => {
  if (!confirm(`Delete translation "${translation.key}"?`)) return
  try {
    loading.value = true
    for (const id of translation.ids) {
      await api.deleteTranslation(id)
    }
    fetchTranslations()
  } catch (e) {
    error.value = e.message
    console.error('Error deleting translation:', e)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="app-container">
    <DefaultLayout :activeTab="activeTab" @update:activeTab="activeTab = $event" />

    <main class="main-content">
      <HeaderSection :title="getTitle()">
        <template #actions>
          <button class="btn btn-primary" v-if="activeTab === 'translations'" @click="openAddModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Translation
          </button>
          <button class="btn btn-secondary" v-if="activeTab === 'locales'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Locale
          </button>
          <button class="btn btn-secondary" v-if="activeTab === 'tags'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Tag
          </button>
        </template>
      </HeaderSection>

      <TranslationsSection 
        v-if="activeTab === 'translations'" 
        :translations="translations" 
        :tags="tags"
        :locales="locales"
        @add="openAddModal"
        @edit="openEditModal"
        @delete="handleDeleteTranslation"
      />
      
      <LocalesSection 
        v-if="activeTab === 'locales'" 
        :locales="locales" 
      />
      
      <TagsSection 
        v-if="activeTab === 'tags'" 
        :tags="tags" 
      />
      
      <ExportSection 
        v-if="activeTab === 'export'" 
        :tags="tags" 
      />

      <TranslationModal
        :show="showModal"
        :translation="editingTranslation"
        :locales="locales"
        :tags="tags"
        :isEdit="!!editingTranslation"
        @close="showModal = false"
        @save="handleSaveTranslation"
      />
    </main>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  background: #f8fafc;
  color: #1e293b;
}

.app-container {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  margin-left: 260px;
  padding: 32px;
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
  white-space: nowrap;
}

.btn svg {
  flex-shrink: 0;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}

.btn-secondary {
  background: white;
  color: #475569;
  border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
  background: #f8fafc;
}

/* Responsive */
@media (max-width: 1024px) {
  .main-content {
    margin-left: 220px;
    padding: 24px;
  }
}

@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
    margin-top: 60px;
    padding: 16px;
  }
}

@media (max-width: 480px) {
  .main-content {
    padding: 12px;
  }
}
</style>
