import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createSkinfoldProtocolAction } from '@/modules/admin/skinfold-protocol/actions/createSkinfoldProtocolAction'
import type { CreateSkinfoldProtocolPayload, SkinfoldProtocol } from '@/types/api/SkinfoldProtocol'

export function useCreateSkinfoldProtocolMutation(): UseMutationReturnType<
  SkinfoldProtocol,
  Error,
  CreateSkinfoldProtocolPayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateSkinfoldProtocolPayload) => createSkinfoldProtocolAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['skinfold-protocols'] })
    },
  })
}
