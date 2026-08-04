import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listDevicesAction } from '@/modules/admin/devices/actions/listDevicesAction'
import type { DeviceListResponse, ListDevicesParams } from '@/types/api/Device'

export const deviceListQueryKey = (params: ListDevicesParams): unknown[] => ['devices', params]

export function useDevicesQuery(
  params: MaybeRefOrGetter<ListDevicesParams> = {},
): UseQueryReturnType<DeviceListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => deviceListQueryKey(paramsRef.value)),
    queryFn: () => listDevicesAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
