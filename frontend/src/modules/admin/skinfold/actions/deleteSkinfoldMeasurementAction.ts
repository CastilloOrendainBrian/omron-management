import { apiClient } from '@/api/apiClient'

export async function deleteSkinfoldMeasurementAction(id: number): Promise<void> {
  await apiClient.delete(`/skinfold-measurements/${id}`)
}
