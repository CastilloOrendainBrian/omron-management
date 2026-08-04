import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateUserProfileAction } from '@/modules/admin/profiles/actions/updateUserProfileAction'
import { userProfileQueryKey } from '@/modules/admin/profiles/composables/useUserProfileQuery'
import type { UpdateUserProfilePayload, UserProfile } from '@/types/api/UserProfile'

export function useUpdateUserProfileMutation(
  id: number,
): UseMutationReturnType<UserProfile, Error, UpdateUserProfilePayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateUserProfilePayload) => updateUserProfileAction(id, payload),
    onSuccess: (profile) => {
      queryClient.setQueryData(userProfileQueryKey(id), profile)
      void queryClient.invalidateQueries({ queryKey: ['user-profiles'] })
    },
  })
}
