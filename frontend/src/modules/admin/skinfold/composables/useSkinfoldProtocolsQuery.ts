import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { listSkinfoldProtocolsAction } from '@/modules/admin/skinfold/actions/listSkinfoldProtocolsAction'
import type {
  ListSkinfoldProtocolsParams,
  SkinfoldProtocolListResponse,
} from '@/types/api/SkinfoldProtocol'

export function useSkinfoldProtocolsQuery(
  params: ListSkinfoldProtocolsParams = {},
): UseQueryReturnType<SkinfoldProtocolListResponse, Error> {
  return useQuery({
    queryKey: ['skinfold-protocols', params],
    queryFn: () => listSkinfoldProtocolsAction(params),
  })
}
