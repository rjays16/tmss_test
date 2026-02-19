<script setup>
defineProps({
  locales: {
    type: Array,
    default: () => []
  }
})

defineEmits(['edit', 'delete'])
</script>

<template>
  <div class="locales-section">
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
          <button class="icon-btn edit" @click="$emit('edit', locale)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="icon-btn delete" @click="$emit('delete', locale)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
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
</style>
