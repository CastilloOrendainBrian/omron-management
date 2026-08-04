import { apiClient } from '@/api/apiClient'
import type {
  SkinfoldMeasurement,
  UpdateSkinfoldMeasurementPayload,
} from '@/types/api/SkinfoldMeasurement'

export async function updateSkinfoldMeasurementAction(
  id: number,
  payload: UpdateSkinfoldMeasurementPayload,
): Promise<SkinfoldMeasurement> {
  const { data } = await apiClient.put<{ success: boolean; data: SkinfoldMeasurement }>(
    `/skinfold-measurements/${id}`,
    payload,
  )
  return data.data
}
