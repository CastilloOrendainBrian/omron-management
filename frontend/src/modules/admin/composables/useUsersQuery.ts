import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { listUsersAction } from '@/modules/admin/actions/listUsersAction'
import type { ListUsersParams, UserListResponse } from '@/types/api/User'

export const userListQueryKey = (params: ListUsersParams): unknown[] => ['users', params]

export function useUsersQuery(
  params: MaybeRefOrGetter<ListUsersParams> = {},
): UseQueryReturnType<UserListResponse, Error> {
  const paramsRef = computed(() => toValue(params))
  return useQuery({
    queryKey: computed(() => userListQueryKey(paramsRef.value)),
    queryFn: () => listUsersAction(paramsRef.value),
    placeholderData: (previousData) => previousData,
  })
}
