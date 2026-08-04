import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateAnthropometricMeasurementAction } from '@/modules/admin/anthropometric/actions/updateAnthropometricMeasurementAction'
import { anthropometricMeasurementQueryKey } from '@/modules/admin/anthropometric/composables/useAnthropometricMeasurementQuery'
import type {
  AnthropometricMeasurement,
  UpdateAnthropometricMeasurementPayload,
} from '@/types/api/AnthropometricMeasurement'

export function useUpdateAnthropometricMeasurementMutation(
  id: number,
): UseMutationReturnType<
  AnthropometricMeasurement,
  Error,
  UpdateAnthropometricMeasurementPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateAnthropometricMeasurementPayload) =>
      updateAnthropometricMeasurementAction(id, payload),
    onSuccess: (measurement) => {
      queryClient.setQueryData(anthropometricMeasurementQueryKey(id), measurement)
      void queryClient.invalidateQueries({ queryKey: ['anthropometric-measurements'] })
    },
  })
}
