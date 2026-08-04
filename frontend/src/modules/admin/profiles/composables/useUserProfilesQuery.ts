import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listUserProfilesAction } from '@/modules/admin/profiles/actions/listUserProfilesAction'
import type { ListUserProfilesParams, UserProfileListResponse } from '@/types/api/UserProfile'

export const userProfileListQueryKey = (params: ListUserProfilesParams): unknown[] => [
  'user-profiles',
  params,
]

export function useUserProfilesQuery(
  params: MaybeRefOrGetter<ListUserProfilesParams> = {},
): UseQueryReturnType<UserProfileListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => userProfileListQueryKey(paramsRef.value)),
    queryFn: () => listUserProfilesAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
