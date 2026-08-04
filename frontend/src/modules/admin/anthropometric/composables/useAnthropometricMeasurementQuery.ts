import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getAnthropometricMeasurementAction } from '@/modules/admin/anthropometric/actions/getAnthropometricMeasurementAction'
import type { AnthropometricMeasurement } from '@/types/api/AnthropometricMeasurement'

export const anthropometricMeasurementQueryKey = (id: number): unknown[] => [
  'anthropometric-measurement',
  id,
]

export function useAnthropometricMeasurementQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<AnthropometricMeasurement, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? anthropometricMeasurementQueryKey(idRef.value)
        : ['anthropometric-measurement', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('AnthropometricMeasurement id is required')
      }
      return getAnthropometricMeasurementAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
