import { apiClient } from '@/api/apiClient'
import type { Device } from '@/types/api/Device'

export async function getDeviceAction(id: number): Promise<Device> {
  const { data } = await apiClient.get<{ success: boolean; data: Device }>(`/devices/${id}`)
  return data.data
}
