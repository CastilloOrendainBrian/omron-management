import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createUserProfileAction } from '@/modules/admin/profiles/actions/createUserProfileAction'
import type { CreateUserProfilePayload, UserProfile } from '@/types/api/UserProfile'

export function useCreateUserProfileMutation(): UseMutationReturnType<
  UserProfile,
  Error,
  CreateUserProfilePayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateUserProfilePayload) => createUserProfileAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['user-profiles'] })
    },
  })
}
