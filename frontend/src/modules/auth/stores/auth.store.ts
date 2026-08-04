import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import type { AuthToken } from '@/types/api/AuthToken'
import type { User } from '@/types/api/User'

export type AuthStatus = 'unauthenticated' | 'authenticated'

const STORAGE_KEY = 'omron-auth'

interface PersistedAuth {
  token: string | null
  user: User | null
}

function loadPersisted(): PersistedAuth {
  if (typeof sessionStorage === 'undefined') {
    return { token: null, user: null }
  }
  try {
    const raw = sessionStorage.getItem(STORAGE_KEY)
    if (!raw) return { token: null, user: null }
    const data = JSON.parse(raw) as Partial<PersistedAuth>
    return {
      token: typeof data.token === 'string' ? data.token : null,
      user: data.user ?? null,
    }
  } catch {
    return { token: null, user: null }
  }
}

function savePersisted(token: string | null, user: User | null): void {
  if (typeof sessionStorage === 'undefined') return
  try {
    if (token) {
      sessionStorage.setItem(STORAGE_KEY, JSON.stringify({ token, user }))
    } else {
      sessionStorage.removeItem(STORAGE_KEY)
    }
  } catch {
    // Ignore quota / privacy mode errors
  }
}

export const useAuthStore = defineStore('auth', () => {
  const initial = loadPersisted()
  const token = ref<string | null>(initial.token)
  const tokenMeta = ref<Omit<AuthToken, 'access_token'> | null>(null)
  const user = ref<User | null>(initial.user)
  const status = ref<AuthStatus>(initial.token ? 'authenticated' : 'unauthenticated')

  const isAuthenticated = computed(() => status.value === 'authenticated' && token.value !== null)

  function setSession(receivedToken: AuthToken): void {
    const { access_token, ...meta } = receivedToken
    token.value = access_token
    tokenMeta.value = meta
    status.value = 'authenticated'
    savePersisted(access_token, user.value)
  }

  function setUser(nextUser: User | null): void {
    user.value = nextUser
    savePersisted(token.value, nextUser)
  }

  function clearSession(): void {
    token.value = null
    tokenMeta.value = null
    user.value = null
    status.value = 'unauthenticated'
    savePersisted(null, null)
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
