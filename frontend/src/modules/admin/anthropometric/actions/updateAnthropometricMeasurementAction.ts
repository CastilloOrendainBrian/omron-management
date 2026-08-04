import { apiClient } from '@/api/apiClient'
import type {
  AnthropometricMeasurement,
  UpdateAnthropometricMeasurementPayload,
} from '@/types/api/AnthropometricMeasurement'

export async function updateAnthropometricMeasurementAction(
  id: number,
  payload: UpdateAnthropometricMeasurementPayload,
): Promise<AnthropometricMeasurement> {
  const { data } = await apiClient.put<{ success: boolean; data: AnthropometricMeasurement }>(
    `/anthropometric-measurements/${id}`,
    payload,
  )
  return data.data
}
