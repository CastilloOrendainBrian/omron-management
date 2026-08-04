import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createSkinfoldMeasurementAction } from '@/modules/admin/skinfold/actions/createSkinfoldMeasurementAction'
import type {
  CreateSkinfoldMeasurementPayload,
  SkinfoldMeasurement,
} from '@/types/api/SkinfoldMeasurement'

export function useCreateSkinfoldMeasurementMutation(): UseMutationReturnType<
  SkinfoldMeasurement,
  Error,
  CreateSkinfoldMeasurementPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateSkinfoldMeasurementPayload) =>
      createSkinfoldMeasurementAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['skinfold-measurements'] })
    },
  })
}
