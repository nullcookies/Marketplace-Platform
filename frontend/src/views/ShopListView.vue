<template>
  <div class="shops">
    <h1>Shops</h1>
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Marketplace</th>
            <th>API Client ID</th>
            <th>Products</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="shop in shops" :key="shop.id">
            <td>{{ shop.name }}</td>
            <td>{{ shop.marketplace?.name }}</td>
            <td>{{ shop.apiClientId || '-' }}</td>
            <td>{{ shop.products?.length || 0 }}</td>
          </tr>
          <tr v-if="!shops.length">
            <td colspan="4" class="empty">No shops configured</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { Shop } from '@/types'

const shops = ref<Shop[]>([])

onMounted(async () => {
  const { data } = await apiClient.get('/shops')
  shops.value = data['hydra:member']
})
</script>

<style scoped>
.empty { text-align: center; color: #999; padding: 2rem; }
</style>
