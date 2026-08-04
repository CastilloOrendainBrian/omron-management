import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getGoalAction } from '@/modules/admin/goals/actions/getGoalAction'
import type { Goal } from '@/types/api/Goal'

export const goalQueryKey = (id: number): unknown[] => ['goal', id]

export function useGoalQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<Goal, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? goalQueryKey(idRef.value)
        : ['goal', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('Goal id is required')
      }
      return getGoalAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
