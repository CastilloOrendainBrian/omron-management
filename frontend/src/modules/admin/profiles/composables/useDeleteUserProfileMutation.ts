import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteUserProfileAction } from '@/modules/admin/profiles/actions/deleteUserProfileAction'
import { userProfileQueryKey } from '@/modules/admin/profiles/composables/useUserProfileQuery'

export function useDeleteUserProfileMutation(): UseMutationReturnType<
  void,
  Error,
  number,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteUserProfileAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: userProfileQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['user-profiles'] })
    },
  })
}
