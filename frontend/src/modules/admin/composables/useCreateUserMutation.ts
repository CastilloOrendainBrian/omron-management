import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createUserAction } from '@/modules/admin/actions/createUserAction'
import { userListQueryKey } from '@/modules/admin/composables/useUsersQuery'
import type { CreateUserPayload, User } from '@/types/api/User'

export function useCreateUserMutation(): UseMutationReturnType<
  User,
  Error,
  CreateUserPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateUserPayload) => createUserAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['users'] })
    },
  })
}
