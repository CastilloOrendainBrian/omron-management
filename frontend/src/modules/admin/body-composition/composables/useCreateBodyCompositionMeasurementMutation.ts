import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createBodyCompositionMeasurementAction } from '@/modules/admin/body-composition/actions/createBodyCompositionMeasurementAction'
import type {
  BodyCompositionMeasurement,
  CreateBodyCompositionMeasurementPayload,
} from '@/types/api/BodyCompositionMeasurement'

export function useCreateBodyCompositionMeasurementMutation(): UseMutationReturnType<
  BodyCompositionMeasurement,
  Error,
  CreateBodyCompositionMeasurementPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateBodyCompositionMeasurementPayload) =>
      createBodyCompositionMeasurementAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['body-composition-measurements'] })
    },
  })
}
