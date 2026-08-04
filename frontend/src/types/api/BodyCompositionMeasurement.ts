export interface BodyCompositionMeasurement {
  id: number
  measurement_session_id: number
  body_fat_percentage: number | null
  muscle_percentage: number | null
  visceral_fat_level: number | null
  metabolic_age: number | null
  bmr_kcal: number | null
  created_at: string | null
  updated_at: string | null
}

export interface BodyCompositionMeasurementListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface BodyCompositionMeasurementListResponse {
  data: BodyCompositionMeasurement[]
  meta: BodyCompositionMeasurementListMeta
}

export interface CreateBodyCompositionMeasurementPayload {
  measurement_session_id: number
  body_fat_percentage?: number | null
  muscle_percentage?: number | null
  visceral_fat_level?: number | null
  metabolic_age?: number | null
  bmr_kcal?: number | null
}

export type UpdateBodyCompositionMeasurementPayload = Partial<
  Omit<CreateBodyCompositionMeasurementPayload, 'measurement_session_id'>
>

export interface ListBodyCompositionMeasurementsParams {
  page?: number
  per_page?: number
  measurement_session_id?: number
}
