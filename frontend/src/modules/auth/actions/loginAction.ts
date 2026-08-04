import { apiClient } from '@/api/apiClient'
import type { AuthToken } from '@/types/api/AuthToken'
import type { LoginCredentials } from '@/modules/auth/interfaces/login.interface'

interface AuthTokenEnvelope {
  data: AuthToken
}

export async function loginAction(credentials: LoginCredentials): Promise<AuthToken> {
  const { data } = await apiClient.post<AuthTokenEnvelope>('/auth/login', credentials)
  return data.data
}
