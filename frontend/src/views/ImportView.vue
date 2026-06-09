<template>
  <div class="import-view">
    <h1>Import Prices</h1>

    <div class="card">
      <h2>Upload Price File</h2>
      <p class="help">Upload CSV or XLSX file with price data</p>
      <div class="upload-area" @drop.prevent="handleDrop" @dragover.prevent>
        <input type="file" ref="fileInput" accept=".csv,.xlsx,.xls" hidden @change="handleFile" />
        <p v-if="!selectedFile">Drag & drop file here or <a href="#" @click.prevent="$refs.fileInput.click()">browse</a></p>
        <p v-else>Selected: {{ selectedFile.name }}</p>
      </div>
      <div class="form-group" v-if="selectedFile">
        <label>Target Shop</label>
        <select v-model="selectedShopId" class="form-select">
          <option value="">Select shop...</option>
          <option v-for="shop in shops" :key="shop.id" :value="shop.id">{{ shop.name }}</option>
        </select>
      </div>
      <button class="btn btn-primary" :disabled="!selectedFile || !selectedShopId" @click="startImport">
        Upload & Import
      </button>
    </div>

    <div class="card">
      <h2>Import History</h2>
      <table>
        <thead>
          <tr>
            <th>File</th>
            <th>Shop</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Errors</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id">
            <td>{{ task.filename }}</td>
            <td>{{ task.shop?.name }}</td>
            <td><span :class="'badge badge-' + task.status">{{ task.status }}</span></td>
            <td>{{ task.processedRows }}/{{ task.totalRows }}</td>
            <td>{{ task.errorRows }}</td>
            <td>{{ new Date(task.createdAt).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import apiClient from '@/api/client'
import type { ImportTask, Shop } from '@/types'

const fileInput = ref<HTMLInputElement>()
const selectedFile = ref<File | null>(null)
const selectedShopId = ref<number | null>(null)
const shops = ref<Shop[]>([])
const tasks = ref<ImportTask[]>([])

function handleFile(e: Event) {
  const input = e.target as HTMLInputElement
  if (input.files?.length) selectedFile.value = input.files[0]
}

function handleDrop(e: DragEvent) {
  if (e.dataTransfer?.files.length) selectedFile.value = e.dataTransfer.files[0]
}

async function startImport() {
  if (!selectedFile.value || !selectedShopId.value) return
  const formData = new FormData()
  formData.append('file', selectedFile.value)
  formData.append('shopId', String(selectedShopId.value))
  await apiClient.post('/import', formData)
  selectedFile.value = null
  selectedShopId.value = null
  fetchTasks()
}

async function fetchTasks() {
  const { data } = await apiClient.get('/import_tasks?order[createdAt]=desc')
  tasks.value = data['hydra:member']
}

onMounted(async () => {
  const { data } = await apiClient.get('/shops')
  shops.value = data['hydra:member']
  fetchTasks()
})
</script>

<style scoped>
.help { color: #666; margin-bottom: 1rem; }
.upload-area {
  border: 2px dashed #ccc;
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  margin-bottom: 1rem;
  cursor: pointer;
}
.upload-area:hover { border-color: #4f46e5; }
.form-group { margin-bottom: 1rem; }
.form-select { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
.badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; }
.badge-completed { background: #dcfce7; color: #166534; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-failed { background: #fee2e2; color: #991b1b; }
</style>
