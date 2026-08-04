import { apiClient } from '@/api/apiClient'
import type { SkinfoldProtocol, UpdateSkinfoldProtocolPayload } from '@/types/api/SkinfoldProtocol'

export async function updateSkinfoldProtocolAction(
  id: number,
  payload: UpdateSkinfoldProtocolPayload,
): Promise<SkinfoldProtocol> {
  const { data } = await apiClient.put<{ success: boolean; data: SkinfoldProtocol }>(
    `/skinfold-protocols/${id}`,
    payload,
  )
  return data.data
}
