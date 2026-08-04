import type { SkinfoldProtocol } from './SkinfoldProtocol'

export interface SkinfoldMeasurementDetail {
  id: number
  skinfold_measurement_id: number
  skinfold_site_id: number
  value_mm: number
}

export interface SkinfoldMeasurementDetailInput {
  skinfold_site_id: number
  value_mm: number
}

export interface SkinfoldMeasurement {
  id: number
  measurement_session_id: number
  skinfold_protocol_id: number
  estimated_body_fat_percentage: number | null
  details?: SkinfoldMeasurementDetail[]
  protocol?: SkinfoldProtocol
  created_at: string | null
  updated_at: string | null
}

export interface SkinfoldMeasurementListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface SkinfoldMeasurementListResponse {
  data: SkinfoldMeasurement[]
  meta: SkinfoldMeasurementListMeta
}

export interface CreateSkinfoldMeasurementPayload {
  measurement_session_id: number
  skinfold_protocol_id: number
  estimated_body_fat_percentage?: number | null
  details: SkinfoldMeasurementDetailInput[]
}

export type UpdateSkinfoldMeasurementPayload = Partial<
  Omit<CreateSkinfoldMeasurementPayload, 'measurement_session_id'>
>

export interface ListSkinfoldMeasurementsParams {
  page?: number
  per_page?: number
  measurement_session_id?: number
  skinfold_protocol_id?: number
}
