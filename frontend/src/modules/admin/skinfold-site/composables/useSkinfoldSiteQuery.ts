import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getSkinfoldSiteAction } from '@/modules/admin/skinfold-site/actions/getSkinfoldSiteAction'
import type { SkinfoldSite } from '@/types/api/SkinfoldSite'

export const skinfoldSiteQueryKey = (id: number): unknown[] => ['skinfold-site', id]

export function useSkinfoldSiteQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<SkinfoldSite, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? skinfoldSiteQueryKey(idRef.value)
        : ['skinfold-site', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('SkinfoldSite id is required')
      }
      return getSkinfoldSiteAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
