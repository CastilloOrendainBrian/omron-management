import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listSkinfoldProtocolsAction } from '@/modules/admin/skinfold-protocol/actions/listSkinfoldProtocolsAction'
import type {
  ListSkinfoldProtocolsParams,
  SkinfoldProtocolListResponse,
} from '@/types/api/SkinfoldProtocol'

export const skinfoldProtocolListQueryKey = (params: ListSkinfoldProtocolsParams): unknown[] => [
  'skinfold-protocols',
  params,
]

export function useSkinfoldProtocolsQuery(
  params: MaybeRefOrGetter<ListSkinfoldProtocolsParams> = {},
): UseQueryReturnType<SkinfoldProtocolListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => skinfoldProtocolListQueryKey(paramsRef.value)),
    queryFn: () => listSkinfoldProtocolsAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
