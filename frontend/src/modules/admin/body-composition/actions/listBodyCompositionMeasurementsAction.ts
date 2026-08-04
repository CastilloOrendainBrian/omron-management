import { apiClient } from '@/api/apiClient'
import type {
  BodyCompositionMeasurementListResponse,
  ListBodyCompositionMeasurementsParams,
} from '@/types/api/BodyCompositionMeasurement'

export async function listBodyCompositionMeasurementsAction(
  params: ListBodyCompositionMeasurementsParams = {},
): Promise<BodyCompositionMeasurementListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: BodyCompositionMeasurementListResponse['data']
    meta: BodyCompositionMeasurementListResponse['meta']
  }>('/body-composition-measurements', { params })
  return { data: data.data, meta: data.meta }
}
