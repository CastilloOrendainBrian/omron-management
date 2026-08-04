import { apiClient } from '@/api/apiClient'
import type {
  BodyCompositionMeasurement,
  CreateBodyCompositionMeasurementPayload,
} from '@/types/api/BodyCompositionMeasurement'

export async function createBodyCompositionMeasurementAction(
  payload: CreateBodyCompositionMeasurementPayload,
): Promise<BodyCompositionMeasurement> {
  const { data } = await apiClient.post<{ success: boolean; data: BodyCompositionMeasurement }>(
    '/body-composition-measurements',
    payload,
  )
  return data.data
}
