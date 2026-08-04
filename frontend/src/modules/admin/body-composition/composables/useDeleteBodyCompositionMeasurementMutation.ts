import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteBodyCompositionMeasurementAction } from '@/modules/admin/body-composition/actions/deleteBodyCompositionMeasurementAction'
import { bodyCompositionMeasurementQueryKey } from '@/modules/admin/body-composition/composables/useBodyCompositionMeasurementQuery'

export function useDeleteBodyCompositionMeasurementMutation(): UseMutationReturnType<
  void,
  Error,
  number,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteBodyCompositionMeasurementAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: bodyCompositionMeasurementQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['body-composition-measurements'] })
    },
  })
}
