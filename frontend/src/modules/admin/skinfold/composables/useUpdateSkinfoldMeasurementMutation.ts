import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateSkinfoldMeasurementAction } from '@/modules/admin/skinfold/actions/updateSkinfoldMeasurementAction'
import { skinfoldMeasurementQueryKey } from '@/modules/admin/skinfold/composables/useSkinfoldMeasurementQuery'
import type {
  SkinfoldMeasurement,
  UpdateSkinfoldMeasurementPayload,
} from '@/types/api/SkinfoldMeasurement'

export function useUpdateSkinfoldMeasurementMutation(
  id: number,
): UseMutationReturnType<SkinfoldMeasurement, Error, UpdateSkinfoldMeasurementPayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateSkinfoldMeasurementPayload) =>
      updateSkinfoldMeasurementAction(id, payload),
    onSuccess: (measurement) => {
      queryClient.setQueryData(skinfoldMeasurementQueryKey(id), measurement)
      void queryClient.invalidateQueries({ queryKey: ['skinfold-measurements'] })
    },
  })
}
