<script setup>
defineProps({
  activeTab: {
    type: String,
    required: true
  }
})

defineEmits(['update:activeTab'])
</script>

<template>
  <aside class="sidebar">
    <div class="logo">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      <span>TMSS</span>
    </div>
    
    <nav class="nav-menu">
      <a href="#" :class="{ active: activeTab === 'translations' }" @click.prevent="$emit('update:activeTab', 'translations')">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        Translations
      </a>
      <a href="#" :class="{ active: activeTab === 'locales' }" @click.prevent="$emit('update:activeTab', 'locales')">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
        Locales
      </a>
      <a href="#" :class="{ active: activeTab === 'tags' }" @click.prevent="$emit('update:activeTab', 'tags')">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Tags
      </a>
      <a href="#" :class="{ active: activeTab === 'export' }" @click.prevent="$emit('update:activeTab', 'export')">
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
</template>

<style scoped>
.sidebar {
  width: 260px;
  background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
  padding: 24px 16px;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
  z-index: 100;
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

.logo span {
  display: block;
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
  flex-shrink: 0;
}

.user-details {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.user-details .name {
  color: white;
  font-weight: 600;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-details .role {
  color: #94a3b8;
  font-size: 12px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Mobile Menu Button */
.menu-toggle {
  display: none;
  position: fixed;
  top: 16px;
  left: 16px;
  z-index: 101;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: #3b82f6;
  border: none;
  color: white;
  cursor: pointer;
  align-items: center;
  justify-content: center;
}

/* Responsive */
@media (max-width: 1024px) {
  .sidebar {
    width: 220px;
  }
  
  .logo {
    font-size: 18px;
    padding-bottom: 24px;
  }
  
  .nav-menu a {
    padding: 10px 12px;
    font-size: 14px;
  }
  
  .nav-menu a svg {
    width: 18px;
    height: 18px;
  }
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    flex-direction: row;
    align-items: center;
    padding: 12px 16px;
    transform: translateY(-100%);
    transition: transform 0.3s ease;
  }
  
  .sidebar.open {
    transform: translateY(0);
  }
  
  .logo {
    padding: 0;
    font-size: 18px;
    gap: 8px;
  }
  
  .logo svg {
    width: 28px;
    height: 28px;
  }
  
  .nav-menu {
    flex-direction: row;
    gap: 8px;
    flex: 1;
    justify-content: center;
  }
  
  .nav-menu a {
    padding: 8px 12px;
    font-size: 13px;
  }
  
  .nav-menu a svg {
    display: none;
  }
  
  .user-info {
    display: none;
  }
  
  .menu-toggle {
    display: flex;
  }
}

@media (max-width: 480px) {
  .nav-menu a {
    padding: 8px 10px;
    font-size: 12px;
  }
}
</style>
