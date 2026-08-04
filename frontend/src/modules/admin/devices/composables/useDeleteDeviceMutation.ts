import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteDeviceAction } from '@/modules/admin/devices/actions/deleteDeviceAction'
import { deviceQueryKey } from '@/modules/admin/devices/composables/useDeviceQuery'

export function useDeleteDeviceMutation(): UseMutationReturnType<void, Error, number, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteDeviceAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: deviceQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['devices'] })
    },
  })
}
