import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import type { AuthToken } from '@/types/api/AuthToken'
import type { User } from '@/types/api/User'

export type AuthStatus = 'unauthenticated' | 'authenticated'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(null)
  const tokenMeta = ref<Omit<AuthToken, 'access_token'> | null>(null)
  const user = ref<User | null>(null)
  const status = ref<AuthStatus>('unauthenticated')

  const isAuthenticated = computed(() => status.value === 'authenticated' && token.value !== null)

  function setSession(receivedToken: AuthToken): void {
    const { access_token, ...meta } = receivedToken
    token.value = access_token
    tokenMeta.value = meta
    status.value = 'authenticated'
  }

  function setUser(nextUser: User | null): void {
    user.value = nextUser
  }

  function clearSession(): void {
    token.value = null
    tokenMeta.value = null
    user.value = null
    status.value = 'unauthenticated'
  }

  return {
    token,
    tokenMeta,
    user,
    status,
    isAuthenticated,
    setSession,
    setUser,
    clearSession,
  }
})
