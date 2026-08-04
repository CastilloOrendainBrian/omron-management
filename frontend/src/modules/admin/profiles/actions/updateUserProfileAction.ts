import { apiClient } from '@/api/apiClient'
import type { UpdateUserProfilePayload, UserProfile } from '@/types/api/UserProfile'

export async function updateUserProfileAction(
  id: number,
  payload: UpdateUserProfilePayload,
): Promise<UserProfile> {
  const { data } = await apiClient.put<{ success: boolean; data: UserProfile }>(
    `/user-profiles/${id}`,
    payload,
  )
  return data.data
}
