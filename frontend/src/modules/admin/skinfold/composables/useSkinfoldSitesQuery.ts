import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { listSkinfoldSitesAction } from '@/modules/admin/skinfold/actions/listSkinfoldSitesAction'
import type { ListSkinfoldSitesParams, SkinfoldSiteListResponse } from '@/types/api/SkinfoldSite'

export function useSkinfoldSitesQuery(
  params: ListSkinfoldSitesParams = {},
): UseQueryReturnType<SkinfoldSiteListResponse, Error> {
  return useQuery({
    queryKey: ['skinfold-sites', params],
    queryFn: () => listSkinfoldSitesAction(params),
  })
}
