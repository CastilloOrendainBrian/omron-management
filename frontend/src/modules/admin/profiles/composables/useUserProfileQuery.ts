import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { getUserProfileAction } from '@/modules/admin/profiles/actions/getUserProfileAction'
import type { UserProfile } from '@/types/api/UserProfile'

export const userProfileQueryKey = (id: number): unknown[] => ['user-profile', id]

export function useUserProfileQuery(
  id: MaybeRefOrGetter<number | null | undefined>,
): UseQueryReturnType<UserProfile, Error> {
  const idRef = computed(() => toValue(id))
  return useQuery({
    queryKey: computed(() =>
      idRef.value !== null && idRef.value !== undefined
        ? userProfileQueryKey(idRef.value)
        : ['user-profile', 'none'],
    ),
    queryFn: () => {
      if (idRef.value === null || idRef.value === undefined) {
        throw new Error('UserProfile id is required')
      }
      return getUserProfileAction(idRef.value)
    },
    enabled: computed(() => idRef.value !== null && idRef.value !== undefined),
  })
}
