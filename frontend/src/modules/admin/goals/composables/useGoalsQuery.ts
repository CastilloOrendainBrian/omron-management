import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listGoalsAction } from '@/modules/admin/goals/actions/listGoalsAction'
import type { GoalListResponse, ListGoalsParams } from '@/types/api/Goal'

export const goalListQueryKey = (params: ListGoalsParams): unknown[] => ['goals', params]

export function useGoalsQuery(
  params: MaybeRefOrGetter<ListGoalsParams> = {},
): UseQueryReturnType<GoalListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => goalListQueryKey(paramsRef.value)),
    queryFn: () => listGoalsAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
