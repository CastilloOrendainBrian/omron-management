import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { updateSkinfoldSiteAction } from '@/modules/admin/skinfold-site/actions/updateSkinfoldSiteAction'
import { skinfoldSiteQueryKey } from '@/modules/admin/skinfold-site/composables/useSkinfoldSiteQuery'
import type { SkinfoldSite, UpdateSkinfoldSitePayload } from '@/types/api/SkinfoldSite'

export function useUpdateSkinfoldSiteMutation(
  id: number,
): UseMutationReturnType<SkinfoldSite, Error, UpdateSkinfoldSitePayload, unknown> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (payload: UpdateSkinfoldSitePayload) => updateSkinfoldSiteAction(id, payload),
    onSuccess: (site) => {
      queryClient.setQueryData(skinfoldSiteQueryKey(id), site)
      void queryClient.invalidateQueries({ queryKey: ['skinfold-sites'] })
    },
  })
}
