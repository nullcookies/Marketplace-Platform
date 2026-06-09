import apiClient from './client'
import type { Product, PaginatedResponse } from '@/types'

export const productApi = {
  async list(page = 1, filters = {}): Promise<PaginatedResponse<Product>> {
    const params = new URLSearchParams({ page: String(page), ...filters })
    const { data } = await apiClient.get(`/products?${params}`)
    return data
  },

  async get(id: number): Promise<Product> {
    const { data } = await apiClient.get(`/products/${id}`)
    return data
  },

  async create(product: Partial<Product>): Promise<Product> {
    const { data } = await apiClient.post('/products', product)
    return data
  },

  async update(id: number, product: Partial<Product>): Promise<Product> {
    const { data } = await apiClient.put(`/products/${id}`, product)
    return data
  },

  async bulkUpdate(ids: number[], updates: Partial<Product>): Promise<void> {
    await apiClient.post('/products/bulk', { ids, updates })
  },
}
