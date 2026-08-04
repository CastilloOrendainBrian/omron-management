import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteAnthropometricMeasurementAction } from '@/modules/admin/anthropometric/actions/deleteAnthropometricMeasurementAction'
import { anthropometricMeasurementQueryKey } from '@/modules/admin/anthropometric/composables/useAnthropometricMeasurementQuery'

export function useDeleteAnthropometricMeasurementMutation(): UseMutationReturnType<
  void,
  Error,
  number,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteAnthropometricMeasurementAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: anthropometricMeasurementQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['anthropometric-measurements'] })
    },
  })
}
