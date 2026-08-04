import type { User } from './User'

export interface Device {
  id: number
  user_id: number | null
  brand: string
  model: string
  serial_number: string | null
  user?: User
  created_at: string | null
  updated_at: string | null
}

export interface DeviceListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface DeviceListResponse {
  data: Device[]
  meta: DeviceListMeta
}

export interface CreateDevicePayload {
  user_id?: number | null
  brand: string
  model: string
  serial_number?: string | null
}

export type UpdateDevicePayload = Partial<CreateDevicePayload>

export interface ListDevicesParams {
  page?: number
  per_page?: number
  user_id?: number
  brand?: string
}
