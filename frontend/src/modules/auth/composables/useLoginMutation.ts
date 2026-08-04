import { useMutation, type UseMutationReturnType } from '@tanstack/vue-query'
import { useRouter } from 'vue-router'
import { loginAction } from '@/modules/auth/actions/loginAction'
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
      await router.push({ name: 'home' })
    },
  })
}
