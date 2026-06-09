<template>
  <div class="product-detail">
    <router-link to="/products" class="back-link">← Back to products</router-link>
    <div class="card" v-if="product">
      <h1>{{ product.name }}</h1>
      <div class="detail-grid">
        <div class="field">
          <label>SKU</label>
          <span>{{ product.sku }}</span>
        </div>
        <div class="field">
          <label>Barcode</label>
          <span>{{ product.barcode || '-' }}</span>
        </div>
        <div class="field">
          <label>Shop</label>
          <span>{{ product.shop?.name }}</span>
        </div>
        <div class="field">
          <label>Category</label>
          <span>{{ product.category?.name || '-' }}</span>
        </div>
        <div class="field">
          <label>Purchase Price</label>
          <span>{{ product.purchasePrice || '-' }}</span>
        </div>
        <div class="field">
          <label>Wholesale Price</label>
          <span>{{ product.wholesalePrice || '-' }}</span>
        </div>
        <div class="field">
          <label>Recommended Price</label>
          <span>{{ product.recommendedPrice || '-' }}</span>
        </div>
        <div class="field">
          <label>Margin %</label>
          <span>{{ product.marginPercent || '-' }}%</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import apiClient from '@/api/client'
import type { Product } from '@/types'

const route = useRoute()
const product = ref<Product | null>(null)

onMounted(async () => {
  const { data } = await apiClient.get(`/products/${route.params.id}`)
  product.value = data
})
</script>

<style scoped>
.back-link { display: inline-block; margin-bottom: 1rem; color: #4f46e5; }
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem; }
.field label { display: block; font-size: 0.75rem; color: #666; margin-bottom: 0.25rem; text-transform: uppercase; }
.field span { font-size: 1rem; font-weight: 500; }
</style>
