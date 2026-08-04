import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listBodyCompositionMeasurementsAction } from '@/modules/admin/body-composition/actions/listBodyCompositionMeasurementsAction'
import type {
  BodyCompositionMeasurementListResponse,
  ListBodyCompositionMeasurementsParams,
} from '@/types/api/BodyCompositionMeasurement'

export const bodyCompositionMeasurementListQueryKey = (
  params: ListBodyCompositionMeasurementsParams,
): unknown[] => ['body-composition-measurements', params]

export function useBodyCompositionMeasurementsQuery(
  params: MaybeRefOrGetter<ListBodyCompositionMeasurementsParams> = {},
): UseQueryReturnType<BodyCompositionMeasurementListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => bodyCompositionMeasurementListQueryKey(paramsRef.value)),
    queryFn: () => listBodyCompositionMeasurementsAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
