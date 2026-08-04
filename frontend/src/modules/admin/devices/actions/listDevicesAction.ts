import { apiClient } from '@/api/apiClient'
import type { DeviceListResponse, ListDevicesParams } from '@/types/api/Device'

export async function listDevicesAction(
  params: ListDevicesParams = {},
): Promise<DeviceListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: DeviceListResponse['data']
    meta: DeviceListResponse['meta']
  }>('/devices', { params })
  return { data: data.data, meta: data.meta }
}
