import { apiClient } from '@/api/apiClient'
import type { UserProfile } from '@/types/api/UserProfile'

export async function getUserProfileAction(id: number): Promise<UserProfile> {
  const { data } = await apiClient.get<{ success: boolean; data: UserProfile }>(
    `/user-profiles/${id}`,
  )
  return data.data
}
