import axios, { type AxiosError, type AxiosInstance } from 'axios'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

const apiBaseUrl = import.meta.env.VITE_API_URL

if (!apiBaseUrl) {
  // eslint-disable-next-line no-console
  console.warn(
    '[apiClient] VITE_API_URL is not defined. Set it in your .env file (see .env.example).',
  )
}

export const apiClient: AxiosInstance = axios.create({
  baseURL: apiBaseUrl || undefined,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
  timeout: 15_000,
})

apiClient.interceptors.request.use((config) => {
  if (!apiBaseUrl) {
    throw new Error('VITE_API_URL is not defined. Check your .env file (see .env.example).')
  }
  const authStore = useAuthStore()
  if (authStore.token) {
    config.headers.Authorization = `Bearer ${authStore.token}`
  }
  return config
})

apiClient.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    if (error.response?.status === 401) {
      const authStore = useAuthStore()
      authStore.clearSession()
    }
    return Promise.reject(error)
  },
)

export interface ApiErrorResponse {
  message: string
  errors?: Record<string, string[]>
}

export function getApiErrorMessage(
  error: unknown,
  fallback = 'Ha ocurrido un error inesperado.',
): string {
  if (axios.isAxiosError<ApiErrorResponse>(error)) {
    const data = error.response?.data
    if (data?.message) {
      return data.message
    }
    if (data?.errors) {
      const firstField = Object.values(data.errors)[0]
      const firstMessage = firstField?.[0]
      if (firstMessage) {
        return firstMessage
      }
    }
  }
  return fallback
}
