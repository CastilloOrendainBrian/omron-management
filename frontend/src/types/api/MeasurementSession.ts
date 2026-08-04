export type MeasurementSource = 'manual' | 'device' | 'import'

export interface MeasurementSession {
  id: number
  user_id: number
  device_id: number | null
  measured_at: string | null
  source: MeasurementSource
  notes: string | null
  created_at: string | null
  updated_at: string | null
}
