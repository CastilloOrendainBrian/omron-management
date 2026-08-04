import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createGoalAction } from '@/modules/admin/goals/actions/createGoalAction'
import type { CreateGoalPayload, Goal } from '@/types/api/Goal'

export function useCreateGoalMutation(): UseMutationReturnType<
  Goal,
  Error,
  CreateGoalPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateGoalPayload) => createGoalAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['goals'] })
    },
  })
}
