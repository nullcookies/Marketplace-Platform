<template>
  <div class="queue-view">
    <h1>Publication Queue</h1>
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Action</th>
            <th>Product</th>
            <th>Shop</th>
            <th>Status</th>
            <th>Attempts</th>
            <th>Error</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="job in jobs" :key="job.id">
            <td>{{ job.id }}</td>
            <td>{{ job.action }}</td>
            <td>{{ job.product?.sku || '-' }}</td>
            <td>{{ job.shop?.name || '-' }}</td>
            <td><span :class="'badge badge-' + job.status">{{ job.status }}</span></td>
            <td>{{ job.attempts }}</td>
            <td>{{ job.errorMessage?.slice(0, 50) || '-' }}</td>
            <td>{{ new Date(job.createdAt).toLocaleDateString() }}</td>
          </tr>
          <tr v-if="!jobs.length">
            <td colspan="8" class="empty">No jobs in queue</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { QueueJob } from '@/types'

const jobs = ref<QueueJob[]>([])

onMounted(async () => {
  const { data } = await apiClient.get('/queue_jobs?order[createdAt]=desc')
  jobs.value = data['hydra:member']
})
</script>

<style scoped>
.badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; }
.badge-queued { background: #fef3c7; color: #92400e; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-published { background: #dcfce7; color: #166534; }
.badge-failed { background: #fee2e2; color: #991b1b; }
.empty { text-align: center; color: #999; padding: 2rem; }
</style>
