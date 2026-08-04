import { useQuery, type UseQueryReturnType } from '@tanstack/vue-query'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import { fetchMeAction } from '@/modules/auth/actions/fetchMeAction'
import type { User } from '@/types/api/User'

export const meQueryKey = (): unknown[] => ['auth', 'me']

export function useMeQuery(): UseQueryReturnType<User, Error> {
  const authStore = useAuthStore()
  return useQuery({
    queryKey: meQueryKey(),
    queryFn: fetchMeAction,
    enabled: () => authStore.isAuthenticated,
    staleTime: 5 * 60 * 1000,
  })
}
