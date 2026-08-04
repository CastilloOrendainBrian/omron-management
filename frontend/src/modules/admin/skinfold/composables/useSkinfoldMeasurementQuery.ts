import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getSkinfoldMeasurementAction } from '@/modules/admin/skinfold/actions/getSkinfoldMeasurementAction'
import type { SkinfoldMeasurement } from '@/types/api/SkinfoldMeasurement'

export const skinfoldMeasurementQueryKey = (id: number): unknown[] => ['skinfold-measurement', id]

export function useSkinfoldMeasurementQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<SkinfoldMeasurement, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? skinfoldMeasurementQueryKey(idRef.value)
        : ['skinfold-measurement', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('SkinfoldMeasurement id is required')
      }
      return getSkinfoldMeasurementAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
