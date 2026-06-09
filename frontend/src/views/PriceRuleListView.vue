<template>
  <div class="price-rules">
    <div class="header">
      <h1>Price Rules</h1>
      <button class="btn btn-primary" @click="showForm = true">Add Rule</button>
    </div>

    <div class="card" v-if="showForm">
      <h2>New Price Rule</h2>
      <div class="form-group">
        <label>Name</label>
        <input v-model="form.name" class="form-input" />
      </div>
      <div class="form-group">
        <label>Formula</label>
        <input v-model="form.formula" class="form-input" placeholder="e.g. {purchase_price} * 1.3" />
      </div>
      <div class="form-group">
        <label>Marketplace</label>
        <select v-model="form.marketplace" class="form-select">
          <option value="">All</option>
          <option value="wb">Wildberries</option>
          <option value="ozon">Ozon</option>
          <option value="yandex_market">Yandex Market</option>
        </select>
      </div>
      <div class="form-group">
        <label>Priority</label>
        <input v-model="form.priority" type="number" class="form-input" />
      </div>
      <button class="btn btn-primary" @click="saveRule">Save</button>
      <button class="btn btn-secondary" @click="showForm = false">Cancel</button>
    </div>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Formula</th>
            <th>Marketplace</th>
            <th>Priority</th>
            <th>Active</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="rule in rules" :key="rule.id">
            <td>{{ rule.name }}</td>
            <td><code>{{ rule.formula }}</code></td>
            <td>{{ rule.marketplace || 'All' }}</td>
            <td>{{ rule.priority }}</td>
            <td>{{ rule.active ? 'Yes' : 'No' }}</td>
            <td><button class="btn btn-danger btn-sm" @click="deleteRule(rule.id)">Delete</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { PriceRule } from '@/types'

const rules = ref<PriceRule[]>([])
const showForm = ref(false)
const form = ref({ name: '', formula: '', marketplace: '', priority: 0 })

async function fetchRules() {
  const { data } = await apiClient.get('/price_rules?order[priority]=asc')
  rules.value = data['hydra:member']
}

async function saveRule() {
  await apiClient.post('/price_rules', form.value)
  showForm.value = false
  form.value = { name: '', formula: '', marketplace: '', priority: 0 }
  fetchRules()
}

async function deleteRule(id: number) {
  await apiClient.delete(`/price_rules/${id}`)
  fetchRules()
}

onMounted(fetchRules)
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.form-group { margin-bottom: 1rem; }
.form-input, .form-select { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
code { background: #f4f4f5; padding: 0.125rem 0.375rem; border-radius: 3px; font-size: 0.875rem; }
</style>
