import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getUserAction } from '@/modules/admin/actions/getUserAction'
import type { User } from '@/types/api/User'

export const userQueryKey = (id: number): unknown[] => ['user', id]

export function useUserQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<User, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? userQueryKey(idRef.value)
        : ['user', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('User id is required')
      }
      return getUserAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
