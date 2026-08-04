import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listSkinfoldMeasurementsAction } from '@/modules/admin/skinfold/actions/listSkinfoldMeasurementsAction'
import type {
  ListSkinfoldMeasurementsParams,
  SkinfoldMeasurementListResponse,
} from '@/types/api/SkinfoldMeasurement'

export const skinfoldMeasurementListQueryKey = (
  params: ListSkinfoldMeasurementsParams,
): unknown[] => ['skinfold-measurements', params]

export function useSkinfoldMeasurementsQuery(
  params: MaybeRefOrGetter<ListSkinfoldMeasurementsParams> = {},
): UseQueryReturnType<SkinfoldMeasurementListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => skinfoldMeasurementListQueryKey(paramsRef.value)),
    queryFn: () => listSkinfoldMeasurementsAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
