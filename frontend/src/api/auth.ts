import apiClient from './client'
import type { LoginCredentials, AuthResponse } from '@/types'

export const authApi = {
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const { data } = await apiClient.post('/login', credentials, {
      headers: { 'Content-Type': 'application/json' },
    })
    return data
  },

  async me(): Promise<any> {
    const { data } = await apiClient.get('/me')
    return data
  },
}
