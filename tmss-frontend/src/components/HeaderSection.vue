<script setup>
defineProps({
  title: {
    type: String,
    required: true
  }
})
</script>

<template>
  <header class="header">
    <h1>{{ title }}</h1>
    <div class="header-actions">
      <slot name="actions"></slot>
    </div>
  </header>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
  flex-wrap: wrap;
  gap: 16px;
}

.header h1 {
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
}

.header-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .header {
    margin-bottom: 20px;
  }
  
  .header h1 {
    font-size: 22px;
  }
  
  .header-actions {
    width: 100%;
    justify-content: flex-start;
  }
}

@media (max-width: 480px) {
  .header h1 {
    font-size: 18px;
  }
}
</style>
