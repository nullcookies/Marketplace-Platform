<template>
  <div class="price-list">
    <h1>Prices</h1>
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>SKU</th>
            <th>Type</th>
            <th>Value</th>
            <th>Commission</th>
            <th>Logistics</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="price in prices" :key="price.id">
            <td>{{ price.product?.name }}</td>
            <td>{{ price.product?.sku }}</td>
            <td>{{ price.type }}</td>
            <td>{{ price.value }}</td>
            <td>{{ price.commissionPercent ?? '-' }}%</td>
            <td>{{ price.logisticsCost ?? '-' }}</td>
            <td>{{ new Date(price.createdAt).toLocaleDateString() }}</td>
          </tr>
          <tr v-if="!prices.length">
            <td colspan="7" class="empty">No prices recorded</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { Price } from '@/types'

const prices = ref<Price[]>([])

onMounted(async () => {
  const { data } = await apiClient.get('/prices?order[createdAt]=desc')
  prices.value = data['hydra:member']
})
</script>

<style scoped>
.empty { text-align: center; color: #999; padding: 2rem; }
</style>
