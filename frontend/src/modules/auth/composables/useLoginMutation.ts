import { useMutation, type UseMutationReturnType } from '@tanstack/vue-query'
import { useRouter } from 'vue-router'
import { loginAction } from '@/modules/auth/actions/loginAction'
import { fetchMeAction } from '@/modules/auth/actions/fetchMeAction'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import type { AuthToken } from '@/types/api/AuthToken'
import type { LoginCredentials } from '@/modules/auth/interfaces/login.interface'

export function useLoginMutation(): UseMutationReturnType<
  AuthToken,
  Error,
  LoginCredentials,
  unknown
> {
  const authStore = useAuthStore()
  const router = useRouter()

  return useMutation({
    mutationFn: (credentials: LoginCredentials) => loginAction(credentials),
    onSuccess: async (token) => {
      authStore.setSession(token)
      try {
        const user = await fetchMeAction()
        authStore.setUser(user)
      } catch {
        // If /me fails, keep the session but continue without user data.
      }
      await router.push({ name: 'dashboard' })
    },
  })
}
