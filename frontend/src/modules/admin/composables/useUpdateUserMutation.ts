import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateUserAction } from '@/modules/admin/actions/updateUserAction'
import { userQueryKey } from '@/modules/admin/composables/useUserQuery'
import type { UpdateUserPayload, User } from '@/types/api/User'

export function useUpdateUserMutation(
  id: number,
): UseMutationReturnType<User, Error, UpdateUserPayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateUserPayload) => updateUserAction(id, payload),
    onSuccess: (user) => {
      queryClient.setQueryData(userQueryKey(id), user)
      void queryClient.invalidateQueries({ queryKey: ['users'] })
    },
  })
}
