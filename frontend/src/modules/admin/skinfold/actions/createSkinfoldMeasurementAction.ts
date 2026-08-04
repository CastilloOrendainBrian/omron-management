import { apiClient } from '@/api/apiClient'
import type {
  CreateSkinfoldMeasurementPayload,
  SkinfoldMeasurement,
} from '@/types/api/SkinfoldMeasurement'

export async function createSkinfoldMeasurementAction(
  payload: CreateSkinfoldMeasurementPayload,
): Promise<SkinfoldMeasurement> {
  const { data } = await apiClient.post<{ success: boolean; data: SkinfoldMeasurement }>(
    '/skinfold-measurements',
    payload,
  )
  return data.data
}
