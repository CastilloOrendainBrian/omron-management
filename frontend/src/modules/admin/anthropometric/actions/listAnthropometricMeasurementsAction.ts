import { apiClient } from '@/api/apiClient'
import type {
  AnthropometricMeasurementListResponse,
  ListAnthropometricMeasurementsParams,
} from '@/types/api/AnthropometricMeasurement'

export async function listAnthropometricMeasurementsAction(
  params: ListAnthropometricMeasurementsParams = {},
): Promise<AnthropometricMeasurementListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: AnthropometricMeasurementListResponse['data']
    meta: AnthropometricMeasurementListResponse['meta']
  }>('/anthropometric-measurements', { params })
  return { data: data.data, meta: data.meta }
}
