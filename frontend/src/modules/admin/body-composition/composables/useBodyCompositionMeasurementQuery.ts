import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getBodyCompositionMeasurementAction } from '@/modules/admin/body-composition/actions/getBodyCompositionMeasurementAction'
import type { BodyCompositionMeasurement } from '@/types/api/BodyCompositionMeasurement'

export const bodyCompositionMeasurementQueryKey = (id: number): unknown[] => [
  'body-composition-measurement',
  id,
]

export function useBodyCompositionMeasurementQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<BodyCompositionMeasurement, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? bodyCompositionMeasurementQueryKey(idRef.value)
        : ['body-composition-measurement', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('BodyCompositionMeasurement id is required')
      }
      return getBodyCompositionMeasurementAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
