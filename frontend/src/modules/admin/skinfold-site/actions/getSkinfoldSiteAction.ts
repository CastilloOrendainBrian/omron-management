import { apiClient } from '@/api/apiClient'
import type { SkinfoldSite } from '@/types/api/SkinfoldSite'

export async function getSkinfoldSiteAction(id: number): Promise<SkinfoldSite> {
  const { data } = await apiClient.get<{ success: boolean; data: SkinfoldSite }>(
    `/skinfold-sites/${id}`,
  )
  return data.data
}
