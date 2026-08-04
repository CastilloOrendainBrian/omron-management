import { apiClient } from '@/api/apiClient'

export async function deleteUserAction(id: number): Promise<void> {
  await apiClient.delete(`/users/${id}`)
}
