import { apiClient } from '@/api/apiClient'

export async function deleteAnthropometricMeasurementAction(id: number): Promise<void> {
  await apiClient.delete(`/anthropometric-measurements/${id}`)
}
