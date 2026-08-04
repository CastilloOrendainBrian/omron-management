import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { createSkinfoldSiteAction } from '@/modules/admin/skinfold-site/actions/createSkinfoldSiteAction'
import type { CreateSkinfoldSitePayload, SkinfoldSite } from '@/types/api/SkinfoldSite'

export function useCreateSkinfoldSiteMutation(): UseMutationReturnType<
  SkinfoldSite,
  Error,
  CreateSkinfoldSitePayload,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: CreateSkinfoldSitePayload) => createSkinfoldSiteAction(payload),
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['skinfold-sites'] })
    },
  })
}
