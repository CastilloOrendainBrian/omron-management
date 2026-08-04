import { apiClient } from '@/api/apiClient'
import type { AnthropometricMeasurement } from '@/types/api/AnthropometricMeasurement'

export async function getAnthropometricMeasurementAction(
  id: number,
): Promise<AnthropometricMeasurement> {
  const { data } = await apiClient.get<{ success: boolean; data: AnthropometricMeasurement }>(
    `/anthropometric-measurements/${id}`,
  )
  return data.data
}
