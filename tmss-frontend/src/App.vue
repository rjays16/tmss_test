<script setup>
import { ref } from 'vue'
import DefaultLayout from './layouts/DefaultLayout.vue'
import HeaderSection from './components/HeaderSection.vue'
import TranslationsSection from './components/TranslationsSection.vue'
import LocalesSection from './components/LocalesSection.vue'
import TagsSection from './components/TagsSection.vue'
import ExportSection from './components/ExportSection.vue'

const activeTab = ref('translations')

const translations = ref([
  { id: 1, key: 'welcome_message', en: 'Welcome', fr: 'Bienvenue', es: 'Bienvenido', tags: ['web', 'home'] },
  { id: 2, key: 'login_button', en: 'Login', fr: 'Connexion', es: 'Iniciar sesión', tags: ['web', 'auth'] },
  { id: 3, key: 'logout_button', en: 'Logout', fr: 'Déconnexion', es: 'Cerrar sesión', tags: ['web', 'auth'] },
  { id: 4, key: 'save_button', en: 'Save', fr: 'Enregistrer', es: 'Guardar', tags: ['mobile', 'common'] },
  { id: 5, key: 'cancel_button', en: 'Cancel', fr: 'Annuler', es: 'Cancelar', tags: ['mobile', 'common'] },
])

const locales = ref([
  { id: 1, code: 'en', name: 'English', is_active: true },
  { id: 2, code: 'fr', name: 'French', is_active: true },
  { id: 3, code: 'es', name: 'Spanish', is_active: true },
])

const tags = ref([
  { id: 1, name: 'web', color: '#3B82F6' },
  { id: 2, name: 'mobile', color: '#10B981' },
  { id: 3, name: 'desktop', color: '#8B5CF6' },
  { id: 4, name: 'auth', color: '#F59E0B' },
  { id: 5, name: 'home', color: '#EF4444' },
  { id: 6, name: 'common', color: '#6B7280' },
])

const getTitle = () => {
  return activeTab.value.charAt(0).toUpperCase() + activeTab.value.slice(1)
}
</script>

<template>
  <div class="app-container">
    <DefaultLayout :activeTab="activeTab" @update:activeTab="activeTab = $event" />

    <main class="main-content">
      <HeaderSection :title="getTitle()">
        <template #actions>
          <button class="btn btn-primary" v-if="activeTab === 'translations'">
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
</style>
