import { apiClient } from '@/api/apiClient'
import type { CreateSkinfoldSitePayload, SkinfoldSite } from '@/types/api/SkinfoldSite'

export async function createSkinfoldSiteAction(
  payload: CreateSkinfoldSitePayload,
): Promise<SkinfoldSite> {
  const { data } = await apiClient.post<{ success: boolean; data: SkinfoldSite }>(
    '/skinfold-sites',
    payload,
  )
  return data.data
}
