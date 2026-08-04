import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteSkinfoldProtocolAction } from '@/modules/admin/skinfold-protocol/actions/deleteSkinfoldProtocolAction'
import { skinfoldProtocolQueryKey } from '@/modules/admin/skinfold-protocol/composables/useSkinfoldProtocolQuery'

export function useDeleteSkinfoldProtocolMutation(): UseMutationReturnType<
  void,
  Error,
  number,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteSkinfoldProtocolAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: skinfoldProtocolQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['skinfold-protocols'] })
    },
  })
}
