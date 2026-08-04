import { apiClient } from '@/api/apiClient'

export async function deleteBodyCompositionMeasurementAction(id: number): Promise<void> {
  await apiClient.delete(`/body-composition-measurements/${id}`)
}
