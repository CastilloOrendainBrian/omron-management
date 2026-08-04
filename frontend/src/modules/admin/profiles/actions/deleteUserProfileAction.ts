import { apiClient } from '@/api/apiClient'

export async function deleteUserProfileAction(id: number): Promise<void> {
  await apiClient.delete(`/user-profiles/${id}`)
}
