import { apiClient } from '@/api/apiClient'
import type { BodyCompositionMeasurement } from '@/types/api/BodyCompositionMeasurement'

export async function getBodyCompositionMeasurementAction(
  id: number,
): Promise<BodyCompositionMeasurement> {
  const { data } = await apiClient.get<{ success: boolean; data: BodyCompositionMeasurement }>(
    `/body-composition-measurements/${id}`,
  )
  return data.data
}
