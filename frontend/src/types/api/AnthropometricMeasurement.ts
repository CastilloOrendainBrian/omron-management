export interface AnthropometricMeasurement {
  id: number
  measurement_session_id: number
  height_cm: number
  weight_kg: number
  bmi: number
  created_at: string | null
  updated_at: string | null
}

export interface AnthropometricMeasurementListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface AnthropometricMeasurementListResponse {
  data: AnthropometricMeasurement[]
  meta: AnthropometricMeasurementListMeta
}

export interface CreateAnthropometricMeasurementPayload {
  measurement_session_id: number
  height_cm: number
  weight_kg: number
  bmi: number
}

export type UpdateAnthropometricMeasurementPayload = Partial<
  Omit<CreateAnthropometricMeasurementPayload, 'measurement_session_id'>
>

export interface ListAnthropometricMeasurementsParams {
  page?: number
  per_page?: number
  measurement_session_id?: number
}
