import { apiClient } from '@/api/apiClient'
import type { UserListResponse, ListUsersParams } from '@/types/api/User'

export async function listUsersAction(params: ListUsersParams = {}): Promise<UserListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: UserListResponse['data']
    meta: UserListResponse['meta']
  }>('/users', { params })
  return { data: data.data, meta: data.meta }
}
