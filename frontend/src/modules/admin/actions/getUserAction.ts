import { apiClient } from '@/api/apiClient'
import type { User } from '@/types/api/User'

export async function getUserAction(id: number): Promise<User> {
  const { data } = await apiClient.get<{ success: boolean; data: User }>(`/users/${id}`)
  return data.data
}
