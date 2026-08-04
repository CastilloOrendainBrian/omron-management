import { apiClient } from '@/api/apiClient'
import type { CreateSkinfoldProtocolPayload, SkinfoldProtocol } from '@/types/api/SkinfoldProtocol'

export async function createSkinfoldProtocolAction(
  payload: CreateSkinfoldProtocolPayload,
): Promise<SkinfoldProtocol> {
  const { data } = await apiClient.post<{ success: boolean; data: SkinfoldProtocol }>(
    '/skinfold-protocols',
    payload,
  )
  return data.data
}
