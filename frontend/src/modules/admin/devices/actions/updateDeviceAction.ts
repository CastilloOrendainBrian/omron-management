import { apiClient } from '@/api/apiClient'
import type { Device, UpdateDevicePayload } from '@/types/api/Device'

export async function updateDeviceAction(
  id: number,
  payload: UpdateDevicePayload,
): Promise<Device> {
  const { data } = await apiClient.put<{ success: boolean; data: Device }>(
    `/devices/${id}`,
    payload,
  )
  return data.data
}
