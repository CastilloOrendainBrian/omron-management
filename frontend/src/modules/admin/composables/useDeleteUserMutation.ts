import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteUserAction } from '@/modules/admin/actions/deleteUserAction'
import { userQueryKey } from '@/modules/admin/composables/useUserQuery'

export function useDeleteUserMutation(): UseMutationReturnType<void, Error, number, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteUserAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: userQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['users'] })
    },
  })
}
