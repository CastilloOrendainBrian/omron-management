import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateGoalAction } from '@/modules/admin/goals/actions/updateGoalAction'
import { goalQueryKey } from '@/modules/admin/goals/composables/useGoalQuery'
import type { Goal, UpdateGoalPayload } from '@/types/api/Goal'

export function useUpdateGoalMutation(
  id: number,
): UseMutationReturnType<Goal, Error, UpdateGoalPayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateGoalPayload) => updateGoalAction(id, payload),
    onSuccess: (goal) => {
      queryClient.setQueryData(goalQueryKey(id), goal)
      void queryClient.invalidateQueries({ queryKey: ['goals'] })
    },
  })
}
