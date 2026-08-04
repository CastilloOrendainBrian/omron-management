import { apiClient } from '@/api/apiClient'
import type { CreateDevicePayload, Device } from '@/types/api/Device'

export async function createDeviceAction(payload: CreateDevicePayload): Promise<Device> {
  const { data } = await apiClient.post<{ success: boolean; data: Device }>('/devices', payload)
  return data.data
}
