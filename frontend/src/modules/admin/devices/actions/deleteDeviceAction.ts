import { apiClient } from '@/api/apiClient'

export async function deleteDeviceAction(id: number): Promise<void> {
  await apiClient.delete(`/devices/${id}`)
}
