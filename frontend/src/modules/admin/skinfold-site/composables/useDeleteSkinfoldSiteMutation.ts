import { useMutation, useQueryClient, type UseMutationReturnType } from '@tanstack/vue-query'
import { deleteSkinfoldSiteAction } from '@/modules/admin/skinfold-site/actions/deleteSkinfoldSiteAction'
import { skinfoldSiteQueryKey } from '@/modules/admin/skinfold-site/composables/useSkinfoldSiteQuery'

export function useDeleteSkinfoldSiteMutation(): UseMutationReturnType<
  void,
  Error,
  number,
  unknown
> {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: number) => deleteSkinfoldSiteAction(id),
    onSuccess: (_data, id) => {
      queryClient.removeQueries({ queryKey: skinfoldSiteQueryKey(id) })
      void queryClient.invalidateQueries({ queryKey: ['skinfold-sites'] })
    },
  })
}
