import { apiClient } from '@/api/apiClient'
import type { ListSkinfoldSitesParams, SkinfoldSiteListResponse } from '@/types/api/SkinfoldSite'

export async function listSkinfoldSitesAction(
  params: ListSkinfoldSitesParams = {},
): Promise<SkinfoldSiteListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: SkinfoldSiteListResponse['data']
    meta: SkinfoldSiteListResponse['meta']
  }>('/skinfold-sites', { params })
  return { data: data.data, meta: data.meta }
}
