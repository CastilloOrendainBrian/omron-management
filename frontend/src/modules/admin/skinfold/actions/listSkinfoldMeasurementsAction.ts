import { apiClient } from '@/api/apiClient'
import type {
  ListSkinfoldMeasurementsParams,
  SkinfoldMeasurementListResponse,
} from '@/types/api/SkinfoldMeasurement'

export async function listSkinfoldMeasurementsAction(
  params: ListSkinfoldMeasurementsParams = {},
): Promise<SkinfoldMeasurementListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: SkinfoldMeasurementListResponse['data']
    meta: SkinfoldMeasurementListResponse['meta']
  }>('/skinfold-measurements', { params })
  return { data: data.data, meta: data.meta }
}
