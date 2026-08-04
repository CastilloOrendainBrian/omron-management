import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteGoalAction } from '@/modules/admin/goals/actions/deleteGoalAction'
import { goalQueryKey } from '@/modules/admin/goals/composables/useGoalQuery'

export function useDeleteGoalMutation(): UseMutationReturnType<void, Error, number, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteGoalAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: goalQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['goals'] })
    },
  })
}
