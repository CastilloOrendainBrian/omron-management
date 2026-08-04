import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createAnthropometricMeasurementAction } from '@/modules/admin/anthropometric/actions/createAnthropometricMeasurementAction'
import type {
  AnthropometricMeasurement,
  CreateAnthropometricMeasurementPayload,
} from '@/types/api/AnthropometricMeasurement'

export function useCreateAnthropometricMeasurementMutation(): UseMutationReturnType<
  AnthropometricMeasurement,
  Error,
  CreateAnthropometricMeasurementPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateAnthropometricMeasurementPayload) =>
      createAnthropometricMeasurementAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['anthropometric-measurements'] })
    },
  })
}
