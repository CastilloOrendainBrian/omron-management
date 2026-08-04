import { apiClient } from '@/api/apiClient'
import type { SkinfoldSite, UpdateSkinfoldSitePayload } from '@/types/api/SkinfoldSite'

export async function updateSkinfoldSiteAction(
  id: number,
  payload: UpdateSkinfoldSitePayload,
): Promise<SkinfoldSite> {
  const { data } = await apiClient.put<{ success: boolean; data: SkinfoldSite }>(
    `/skinfold-sites/${id}`,
    payload,
  )
  return data.data
}
