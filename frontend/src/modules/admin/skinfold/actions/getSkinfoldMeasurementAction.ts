import { apiClient } from '@/api/apiClient'
import type { SkinfoldMeasurement } from '@/types/api/SkinfoldMeasurement'

export async function getSkinfoldMeasurementAction(id: number): Promise<SkinfoldMeasurement> {
  const { data } = await apiClient.get<{ success: boolean; data: SkinfoldMeasurement }>(
    `/skinfold-measurements/${id}`,
  )
  return data.data
}
