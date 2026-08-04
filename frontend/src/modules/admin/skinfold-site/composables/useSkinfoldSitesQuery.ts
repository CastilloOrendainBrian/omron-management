import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listSkinfoldSitesAction } from '@/modules/admin/skinfold-site/actions/listSkinfoldSitesAction'
import type { ListSkinfoldSitesParams, SkinfoldSiteListResponse } from '@/types/api/SkinfoldSite'

export const skinfoldSiteListQueryKey = (params: ListSkinfoldSitesParams): unknown[] => [
  'skinfold-sites',
  params,
]

export function useSkinfoldSitesQuery(
  params: MaybeRefOrGetter<ListSkinfoldSitesParams> = {},
): UseQueryReturnType<SkinfoldSiteListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => skinfoldSiteListQueryKey(paramsRef.value)),
    queryFn: () => listSkinfoldSitesAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
