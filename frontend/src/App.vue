<template>
  <div class="app">
    <aside class="sidebar" v-if="authStore.isAuthenticated">
      <div class="sidebar-header">
        <h2>MP Platform</h2>
      </div>
      <nav class="sidebar-nav">
        <router-link to="/" class="nav-item">Dashboard</router-link>
        <router-link to="/products" class="nav-item">Products</router-link>
        <router-link to="/prices" class="nav-item">Prices</router-link>
        <router-link to="/import" class="nav-item">Import</router-link>
        <router-link to="/queue" class="nav-item">Queue</router-link>
        <router-link to="/rules" class="nav-item">Price Rules</router-link>
        <router-link to="/shops" class="nav-item">Shops</router-link>
      </nav>
    </aside>
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
</script>

<style scoped>
.app {
  display: flex;
  min-height: 100vh;
}
.sidebar {
  width: 240px;
  background: #1a1a2e;
  color: #fff;
  padding: 1rem;
}
.sidebar-header h2 {
  margin-bottom: 2rem;
  font-size: 1.25rem;
}
.nav-item {
  display: block;
  padding: 0.5rem 1rem;
  color: #ccc;
  text-decoration: none;
  border-radius: 4px;
  margin-bottom: 0.25rem;
}
.nav-item:hover, .nav-item.router-link-active {
  background: rgba(255,255,255,0.1);
  color: #fff;
}
.main-content {
  flex: 1;
  padding: 2rem;
  background: #f5f5f5;
}
</style>
