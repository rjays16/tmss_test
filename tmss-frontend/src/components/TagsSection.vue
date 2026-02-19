<script setup>
defineProps({
  tags: {
    type: Array,
    default: () => []
  }
})

defineEmits(['edit', 'delete'])
</script>

<template>
  <div class="tags-section">
    <div class="tags-grid">
      <div v-for="tag in tags" :key="tag.id" class="tag-card">
        <span class="tag-color" :style="{ backgroundColor: tag.color }"></span>
        <span class="tag-name">{{ tag.name }}</span>
        <div class="tag-actions">
          <button class="icon-btn edit" @click="$emit('edit', tag)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="icon-btn delete" @click="$emit('delete', tag)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
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
