import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateSkinfoldProtocolAction } from '@/modules/admin/skinfold-protocol/actions/updateSkinfoldProtocolAction'
import { skinfoldProtocolQueryKey } from '@/modules/admin/skinfold-protocol/composables/useSkinfoldProtocolQuery'
import type { SkinfoldProtocol, UpdateSkinfoldProtocolPayload } from '@/types/api/SkinfoldProtocol'

export function useUpdateSkinfoldProtocolMutation(
  id: number,
): UseMutationReturnType<SkinfoldProtocol, Error, UpdateSkinfoldProtocolPayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateSkinfoldProtocolPayload) =>
      updateSkinfoldProtocolAction(id, payload),
    onSuccess: (protocol) => {
      queryClient.setQueryData(skinfoldProtocolQueryKey(id), protocol)
      void queryClient.invalidateQueries({ queryKey: ['skinfold-protocols'] })
    },
  })
}
