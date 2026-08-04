import { apiClient } from '@/api/apiClient'
import type { User } from '@/types/api/User'

interface MeEnvelope {
  data: User
}

export async function fetchMeAction(): Promise<User> {
  const { data } = await apiClient.get<MeEnvelope>('/auth/me')
  return data.data
}
