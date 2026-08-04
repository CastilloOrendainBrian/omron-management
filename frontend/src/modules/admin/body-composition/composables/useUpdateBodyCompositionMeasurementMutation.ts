import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateBodyCompositionMeasurementAction } from '@/modules/admin/body-composition/actions/updateBodyCompositionMeasurementAction'
import { bodyCompositionMeasurementQueryKey } from '@/modules/admin/body-composition/composables/useBodyCompositionMeasurementQuery'
import type {
  BodyCompositionMeasurement,
  UpdateBodyCompositionMeasurementPayload,
} from '@/types/api/BodyCompositionMeasurement'

export function useUpdateBodyCompositionMeasurementMutation(
  id: number,
): UseMutationReturnType<
  BodyCompositionMeasurement,
  Error,
  UpdateBodyCompositionMeasurementPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateBodyCompositionMeasurementPayload) =>
      updateBodyCompositionMeasurementAction(id, payload),
    onSuccess: (measurement) => {
      queryClient.setQueryData(bodyCompositionMeasurementQueryKey(id), measurement)
      void queryClient.invalidateQueries({ queryKey: ['body-composition-measurements'] })
    },
  })
}
