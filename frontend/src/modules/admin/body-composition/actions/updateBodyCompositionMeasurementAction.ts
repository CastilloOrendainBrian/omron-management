import { apiClient } from '@/api/apiClient'
import type {
  BodyCompositionMeasurement,
  UpdateBodyCompositionMeasurementPayload,
} from '@/types/api/BodyCompositionMeasurement'

export async function updateBodyCompositionMeasurementAction(
  id: number,
  payload: UpdateBodyCompositionMeasurementPayload,
): Promise<BodyCompositionMeasurement> {
  const { data } = await apiClient.put<{ success: boolean; data: BodyCompositionMeasurement }>(
    `/body-composition-measurements/${id}`,
    payload,
  )
  return data.data
}
