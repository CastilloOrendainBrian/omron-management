import { apiClient } from '@/api/apiClient'
import type { User, UpdateUserPayload } from '@/types/api/User'

export async function updateUserAction(id: number, payload: UpdateUserPayload): Promise<User> {
  const { data } = await apiClient.put<{ success: boolean; data: User }>(`/users/${id}`, payload)
  return data.data
}
