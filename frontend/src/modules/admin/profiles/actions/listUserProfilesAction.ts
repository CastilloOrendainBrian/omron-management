import { apiClient } from '@/api/apiClient'
import type { ListUserProfilesParams, UserProfileListResponse } from '@/types/api/UserProfile'

export async function listUserProfilesAction(
  params: ListUserProfilesParams = {},
): Promise<UserProfileListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: UserProfileListResponse['data']
    meta: UserProfileListResponse['meta']
  }>('/user-profiles', { params })
  return { data: data.data, meta: data.meta }
}
