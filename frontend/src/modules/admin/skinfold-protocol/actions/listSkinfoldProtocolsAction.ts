import { apiClient } from '@/api/apiClient'
import type {
  ListSkinfoldProtocolsParams,
  SkinfoldProtocolListResponse,
} from '@/types/api/SkinfoldProtocol'

export async function listSkinfoldProtocolsAction(
  params: ListSkinfoldProtocolsParams = {},
): Promise<SkinfoldProtocolListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: SkinfoldProtocolListResponse['data']
    meta: SkinfoldProtocolListResponse['meta']
  }>('/skinfold-protocols', { params })
  return { data: data.data, meta: data.meta }
}
