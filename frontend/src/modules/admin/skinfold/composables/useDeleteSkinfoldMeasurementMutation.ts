import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteSkinfoldMeasurementAction } from '@/modules/admin/skinfold/actions/deleteSkinfoldMeasurementAction'
import { skinfoldMeasurementQueryKey } from '@/modules/admin/skinfold/composables/useSkinfoldMeasurementQuery'

export function useDeleteSkinfoldMeasurementMutation(): UseMutationReturnType<
  void,
  Error,
  number,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteSkinfoldMeasurementAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: skinfoldMeasurementQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['skinfold-measurements'] })
    },
  })
}
