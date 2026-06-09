<template>
  <div class="dashboard">
    <h1>Dashboard</h1>
    <div class="stats-grid">
      <div class="card stat-card">
        <h3>Total Products</h3>
        <p class="stat-value">{{ stats.totalProducts }}</p>
      </div>
      <div class="card stat-card">
        <h3>Active Products</h3>
        <p class="stat-value">{{ stats.activeProducts }}</p>
      </div>
      <div class="card stat-card">
        <h3>Pending Queue</h3>
        <p class="stat-value">{{ stats.pendingQueue }}</p>
      </div>
      <div class="card stat-card">
        <h3>Shops</h3>
        <p class="stat-value">{{ stats.totalShops }}</p>
      </div>
    </div>

    <div class="card">
      <h2>Recent Imports</h2>
      <table>
        <thead>
          <tr>
            <th>File</th>
            <th>Shop</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in recentImports" :key="task.id">
            <td>{{ task.filename }}</td>
            <td>{{ task.shop?.name }}</td>
            <td><span :class="'badge badge-' + task.status">{{ task.status }}</span></td>
            <td>{{ task.processedRows }}/{{ task.totalRows }}</td>
            <td>{{ new Date(task.createdAt).toLocaleDateString() }}</td>
          </tr>
          <tr v-if="!recentImports.length">
            <td colspan="5" class="empty">No imports yet</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { ImportTask } from '@/types'

const stats = ref({
  totalProducts: 0,
  activeProducts: 0,
  pendingQueue: 0,
  totalShops: 0,
})

const recentImports = ref<ImportTask[]>([])

onMounted(async () => {
  try {
    const [productsRes, importRes] = await Promise.all([
      apiClient.get('/products?pagination=false'),
      apiClient.get('/import_tasks?order[createdAt]=desc&itemsPerPage=5'),
    ])
    const products = productsRes.data['hydra:member']
    stats.value.totalProducts = productsRes.data['hydra:totalItems']
    stats.value.activeProducts = products.filter((p: any) => p.active).length
    recentImports.value = importRes.data['hydra:member']
  } catch {}
})
</script>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}
.stat-card h3 { font-size: 0.875rem; color: #666; margin-bottom: 0.5rem; }
.stat-value { font-size: 2rem; font-weight: 700; color: #1a1a2e; }
.badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; }
.badge-completed { background: #dcfce7; color: #166534; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-failed { background: #fee2e2; color: #991b1b; }
.empty { text-align: center; color: #999; padding: 2rem; }
</style>
