<template>
  <div class="product-list">
    <div class="header">
      <h1>Products</h1>
      <div class="actions">
        <input v-model="search" placeholder="Search by SKU or name..." class="search-input" />
        <button class="btn btn-primary" @click="showImport = true">Import CSV</button>
      </div>
    </div>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th><input type="checkbox" @change="selectAll" /></th>
            <th>SKU</th>
            <th>Name</th>
            <th>Shop</th>
            <th>Purchase Price</th>
            <th>Wholesale Price</th>
            <th>Margin %</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td><input type="checkbox" :checked="selected.has(product.id)" @change="toggle(product.id)" /></td>
            <td>{{ product.sku }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.shop?.name }}</td>
            <td>{{ product.purchasePrice ?? '-' }}</td>
            <td>{{ product.wholesalePrice ?? '-' }}</td>
            <td>{{ product.marginPercent ?? '-' }}%</td>
            <td>
              <span :class="'badge badge-' + (product.active ? 'active' : 'inactive')">
                {{ product.active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td><router-link :to="'/products/' + product.id" class="btn btn-secondary btn-sm">Edit</router-link></td>
          </tr>
          <tr v-if="!products.length">
            <td colspan="9" class="empty">No products found</td>
          </tr>
        </tbody>
      </table>

      <div class="pagination" v-if="totalPages > 1">
        <button :disabled="page <= 1" @click="page--" class="btn btn-secondary btn-sm">Previous</button>
        <span>Page {{ page }} of {{ totalPages }}</span>
        <button :disabled="page >= totalPages" @click="page++" class="btn btn-secondary btn-sm">Next</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { Product } from '@/types'

const products = ref<Product[]>([])
const search = ref('')
const page = ref(1)
const totalPages = ref(1)
const selected = ref(new Set<number>())
const showImport = ref(false)

async function fetchProducts() {
  try {
    const params = new URLSearchParams({
      page: String(page.value),
      itemsPerPage: '25',
    })
    if (search.value) params.set('property', search.value)
    const { data } = await apiClient.get(`/products?${params}`)
    products.value = data['hydra:member']
    const total = data['hydra:totalItems'] || 0
    totalPages.value = Math.ceil(total / 25)
  } catch {}
}

function toggle(id: number) {
  if (selected.value.has(id)) selected.value.delete(id)
  else selected.value.add(id)
}

function selectAll(e: Event) {
  const checked = (e.target as HTMLInputElement).checked
  if (checked) products.value.forEach(p => selected.value.add(p.id))
  else selected.value.clear()
}

watch([search, page], fetchProducts)
onMounted(fetchProducts)
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.actions { display: flex; gap: 0.5rem; }
.search-input { padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; width: 250px; }
.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
.badge-active { background: #dcfce7; color: #166534; }
.badge-inactive { background: #fee2e2; color: #991b1b; }
.pagination { display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 1rem; }
</style>
