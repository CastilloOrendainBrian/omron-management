import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createDeviceAction } from '@/modules/admin/devices/actions/createDeviceAction'
import type { CreateDevicePayload, Device } from '@/types/api/Device'

export function useCreateDeviceMutation(): UseMutationReturnType<
  Device,
  Error,
  CreateDevicePayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateDevicePayload) => createDeviceAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['devices'] })
    },
  })
}
