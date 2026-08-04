import { apiClient } from '@/api/apiClient'
import type { SkinfoldProtocol } from '@/types/api/SkinfoldProtocol'

export async function getSkinfoldProtocolAction(id: number): Promise<SkinfoldProtocol> {
  const { data } = await apiClient.get<{ success: boolean; data: SkinfoldProtocol }>(
    `/skinfold-protocols/${id}`,
  )
  return data.data
}
