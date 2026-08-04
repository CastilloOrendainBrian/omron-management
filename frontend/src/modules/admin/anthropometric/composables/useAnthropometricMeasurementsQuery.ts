import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listAnthropometricMeasurementsAction } from '@/modules/admin/anthropometric/actions/listAnthropometricMeasurementsAction'
import type {
  AnthropometricMeasurementListResponse,
  ListAnthropometricMeasurementsParams,
} from '@/types/api/AnthropometricMeasurement'

export const anthropometricMeasurementListQueryKey = (
  params: ListAnthropometricMeasurementsParams,
): unknown[] => ['anthropometric-measurements', params]

export function useAnthropometricMeasurementsQuery(
  params: MaybeRefOrGetter<ListAnthropometricMeasurementsParams> = {},
): UseQueryReturnType<AnthropometricMeasurementListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => anthropometricMeasurementListQueryKey(paramsRef.value)),
    queryFn: () => listAnthropometricMeasurementsAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
