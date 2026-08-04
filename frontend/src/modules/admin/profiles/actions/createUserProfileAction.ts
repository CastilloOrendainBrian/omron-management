import { apiClient } from '@/api/apiClient'
import type { CreateUserProfilePayload, UserProfile } from '@/types/api/UserProfile'

export async function createUserProfileAction(
  payload: CreateUserProfilePayload,
): Promise<UserProfile> {
  const { data } = await apiClient.post<{ success: boolean; data: UserProfile }>(
    '/user-profiles',
    payload,
  )
  return data.data
}
