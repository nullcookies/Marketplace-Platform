export interface User {
  id: number
  email: string
  name: string
  roles: string[]
}

export interface Marketplace {
  id: number
  type: 'wb' | 'ozon' | 'yandex_market'
  name: string
}

export interface Shop {
  id: number
  name: string
  marketplace: Marketplace
  apiToken?: string
  apiClientId?: string
}

export interface Product {
  id: number
  name: string
  sku: string
  barcode?: string
  shop: Shop
  category?: Category
  purchasePrice?: number
  wholesalePrice?: number
  recommendedPrice?: number
  marginPercent?: number
  externalId?: string
  active: boolean
  createdAt: string
  updatedAt: string
}

export interface Category {
  id: number
  name: string
  externalId?: string
  parent?: Category
  children?: Category[]
  marketplace?: string
}

export interface Price {
  id: number
  product: Product
  type: 'purchase' | 'wholesale' | 'retail' | 'marketplace'
  value: number
  commissionPercent?: number
  logisticsCost?: number
  note?: string
  createdAt: string
}

export interface PriceRule {
  id: number
  name: string
  formula: string
  conditions: Record<string, any>
  marketplace?: string
  priority: number
  active: boolean
}

export interface ImportTask {
  id: number
  filename: string
  status: 'pending' | 'processing' | 'completed' | 'failed'
  shop: Shop
  totalRows?: number
  processedRows?: number
  errorRows?: number
  errors?: string
  createdAt: string
  completedAt?: string
}

export interface QueueJob {
  id: number
  status: 'queued' | 'processing' | 'published' | 'failed'
  action: string
  product?: Product
  shop?: Shop
  payload: Record<string, any>
  errorMessage?: string
  attempts: number
  createdAt: string
  processedAt?: string
}

export interface PaginatedResponse<T> {
  'hydra:member': T[]
  'hydra:totalItems': number
  'hydra:view'?: {
    'hydra:first'?: string
    'hydra:last'?: string
    'hydra:next'?: string
    'hydra:previous'?: string
  }
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface AuthResponse {
  token: string
}
