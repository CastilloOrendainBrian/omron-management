import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getDeviceAction } from '@/modules/admin/devices/actions/getDeviceAction'
import type { Device } from '@/types/api/Device'

export const deviceQueryKey = (id: number): unknown[] => ['device', id]

export function useDeviceQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<Device, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? deviceQueryKey(idRef.value)
        : ['device', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('Device id is required')
      }
      return getDeviceAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
