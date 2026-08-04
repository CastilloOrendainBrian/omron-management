import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getSkinfoldProtocolAction } from '@/modules/admin/skinfold-protocol/actions/getSkinfoldProtocolAction'
import type { SkinfoldProtocol } from '@/types/api/SkinfoldProtocol'

export const skinfoldProtocolQueryKey = (id: number): unknown[] => ['skinfold-protocol', id]

export function useSkinfoldProtocolQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<SkinfoldProtocol, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? skinfoldProtocolQueryKey(idRef.value)
        : ['skinfold-protocol', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('SkinfoldProtocol id is required')
      }
      return getSkinfoldProtocolAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
