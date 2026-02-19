<script setup>
import { ref } from 'vue'

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

const searchQuery = ref('')
const selectedTag = ref('')

const filteredTranslations = () => {
  return translations.value.filter(t => {
    const matchesSearch = !searchQuery.value || 
      t.key.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      t.en.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesTag = !selectedTag.value || t.tags.includes(selectedTag.value)
    return matchesSearch && matchesTag
  })
}
</script>

<template>
  <div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        <span>TMSS</span>
      </div>
      
      <nav class="nav-menu">
        <a href="#" :class="{ active: activeTab === 'translations' }" @click.prevent="activeTab = 'translations'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          Translations
        </a>
        <a href="#" :class="{ active: activeTab === 'locales' }" @click.prevent="activeTab = 'locales'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          Locales
        </a>
        <a href="#" :class="{ active: activeTab === 'tags' }" @click.prevent="activeTab = 'tags'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          Tags
        </a>
        <a href="#" :class="{ active: activeTab === 'export' }" @click.prevent="activeTab = 'export'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Export
        </a>
      </nav>

      <div class="user-info">
        <div class="avatar">A</div>
        <div class="user-details">
          <span class="name">Admin</span>
          <span class="role">Administrator</span>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <header class="header">
        <h1>{{ activeTab.charAt(0).toUpperCase() + activeTab.slice(1) }}</h1>
        <div class="header-actions">
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
        </div>
      </header>

      <!-- Translations Tab -->
      <div v-if="activeTab === 'translations'" class="content">
        <div class="filters">
          <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" v-model="searchQuery" placeholder="Search translations...">
          </div>
          <select v-model="selectedTag" class="tag-filter">
            <option value="">All Tags</option>
            <option v-for="tag in tags" :key="tag.id" :value="tag.name">{{ tag.name }}</option>
          </select>
        </div>

        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Key</th>
                <th>English</th>
                <th>French</th>
                <th>Spanish</th>
                <th>Tags</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="t in filteredTranslations()" :key="t.id">
                <td class="key-cell">{{ t.key }}</td>
                <td>{{ t.en }}</td>
                <td>{{ t.fr }}</td>
                <td>{{ t.es }}</td>
                <td>
                  <div class="tag-list">
                    <span v-for="tagName in t.tags" :key="tagName" class="tag" :style="{ backgroundColor: tags.find(t => t.name === tagName)?.color }">
                      {{ tagName }}
                    </span>
                  </div>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="icon-btn edit" title="Edit">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button class="icon-btn delete" title="Delete">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Locales Tab -->
      <div v-if="activeTab === 'locales'" class="content">
        <div class="cards-grid">
          <div v-for="locale in locales" :key="locale.id" class="locale-card">
            <div class="locale-header">
              <span class="locale-code">{{ locale.code }}</span>
              <span class="status-badge" :class="{ active: locale.is_active }">
                {{ locale.is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
            <h3>{{ locale.name }}</h3>
            <div class="locale-actions">
              <button class="icon-btn edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </button>
              <button class="icon-btn delete">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tags Tab -->
      <div v-if="activeTab === 'tags'" class="content">
        <div class="tags-grid">
          <div v-for="tag in tags" :key="tag.id" class="tag-card">
            <span class="tag-color" :style="{ backgroundColor: tag.color }"></span>
            <span class="tag-name">{{ tag.name }}</span>
            <div class="tag-actions">
              <button class="icon-btn edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </button>
              <button class="icon-btn delete">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Export Tab -->
      <div v-if="activeTab === 'export'" class="content">
        <div class="export-section">
          <div class="export-card">
            <h3>Export Translations</h3>
            <p>Download your translations in JSON format for frontend applications</p>
            <div class="export-options">
              <div class="form-group">
                <label>Select Locales</label>
                <div class="checkbox-group">
                  <label><input type="checkbox" checked> English (en)</label>
                  <label><input type="checkbox" checked> French (fr)</label>
                  <label><input type="checkbox" checked> Spanish (es)</label>
                </div>
              </div>
              <div class="form-group">
                <label>Filter by Tag</label>
                <select>
                  <option value="">All Tags</option>
                  <option v-for="tag in tags" :key="tag.id" :value="tag.name">{{ tag.name }}</option>
                </select>
              </div>
            </div>
            <button class="btn btn-primary btn-large">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Export JSON
            </button>
          </div>
        </div>
      </div>
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

/* Sidebar */
.sidebar {
  width: 260px;
  background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
  padding: 24px 16px;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
}

.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0 8px 32px;
  color: white;
  font-size: 22px;
  font-weight: 700;
}

.logo svg {
  color: #60a5fa;
}

.nav-menu {
  display: flex;
  flex-direction: column;
  gap: 4px;
  flex: 1;
}

.nav-menu a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  color: #94a3b8;
  text-decoration: none;
  border-radius: 8px;
  transition: all 0.2s;
  font-weight: 500;
}

.nav-menu a:hover {
  background: rgba(255, 255, 255, 0.05);
  color: white;
}

.nav-menu a.active {
  background: #3b82f6;
  color: white;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  margin-top: auto;
}

.avatar {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
}

.user-details {
  display: flex;
  flex-direction: column;
}

.user-details .name {
  color: white;
  font-weight: 600;
  font-size: 14px;
}

.user-details .role {
  color: #94a3b8;
  font-size: 12px;
}

/* Main Content */
.main-content {
  flex: 1;
  margin-left: 260px;
  padding: 32px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.header h1 {
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
}

.header-actions {
  display: flex;
  gap: 12px;
}

/* Buttons */
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

.btn-large {
  padding: 14px 28px;
  font-size: 16px;
}

/* Filters */
.filters {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
}

.search-box {
  flex: 1;
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

/* Table */
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

/* Cards Grid */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.locale-card {
  background: white;
  padding: 24px;
  border-radius: 16px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.locale-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.locale-code {
  font-size: 24px;
  font-weight: 700;
  color: #0f172a;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  background: #f1f5f9;
  color: #64748b;
}

.status-badge.active {
  background: #dcfce7;
  color: #16a34a;
}

.locale-card h3 {
  font-size: 16px;
  color: #475569;
  margin-bottom: 16px;
}

.locale-actions {
  display: flex;
  gap: 8px;
}

/* Tags Grid */
.tags-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.tag-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: white;
  padding: 12px 20px;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.tag-color {
  width: 16px;
  height: 16px;
  border-radius: 50%;
}

.tag-name {
  font-weight: 600;
  font-size: 14px;
  color: #0f172a;
}

.tag-actions {
  display: flex;
  gap: 4px;
  margin-left: 12px;
}

/* Export Section */
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
</style>
