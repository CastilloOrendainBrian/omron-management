import { apiClient } from '@/api/apiClient'
import type {
  AnthropometricMeasurement,
  CreateAnthropometricMeasurementPayload,
} from '@/types/api/AnthropometricMeasurement'

export async function createAnthropometricMeasurementAction(
  payload: CreateAnthropometricMeasurementPayload,
): Promise<AnthropometricMeasurement> {
  const { data } = await apiClient.post<{ success: boolean; data: AnthropometricMeasurement }>(
    '/anthropometric-measurements',
    payload,
  )
  return data.data
}
