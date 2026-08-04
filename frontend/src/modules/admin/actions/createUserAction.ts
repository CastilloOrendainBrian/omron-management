import { apiClient } from '@/api/apiClient'
import type { User, CreateUserPayload } from '@/types/api/User'

export async function createUserAction(payload: CreateUserPayload): Promise<User> {
  const { data } = await apiClient.post<{ success: boolean; data: User }>('/users', payload)
  return data.data
}
