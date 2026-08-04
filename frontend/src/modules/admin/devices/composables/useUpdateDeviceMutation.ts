import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateDeviceAction } from '@/modules/admin/devices/actions/updateDeviceAction'
import { deviceQueryKey } from '@/modules/admin/devices/composables/useDeviceQuery'
import type { Device, UpdateDevicePayload } from '@/types/api/Device'

export function useUpdateDeviceMutation(
  id: number,
): UseMutationReturnType<Device, Error, UpdateDevicePayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateDevicePayload) => updateDeviceAction(id, payload),
    onSuccess: (device) => {
      queryClient.setQueryData(deviceQueryKey(id), device)
      void queryClient.invalidateQueries({ queryKey: ['devices'] })
    },
  })
}
