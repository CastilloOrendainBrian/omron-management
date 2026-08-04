import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import { createMemoryHistory, createRouter } from 'vue-router'
import { defineComponent, h } from 'vue'
import { QueryClient, VueQueryPlugin } from '@tanstack/vue-query'
import { useLoginMutation } from '@/modules/auth/composables/useLoginMutation'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

vi.mock('@/api/apiClient', () => ({
  apiClient: {
    post: vi.fn(),
  },
}))

import { apiClient } from '@/api/apiClient'

function createTestRouter() {
  return createRouter({
    history: createMemoryHistory(),
    routes: [
      { path: '/', name: 'home', component: { template: '<div />' } },
      { path: '/login', name: 'login', component: { template: '<div />' } },
    ],
  })
}

function mountMutationHost() {
  const router = createTestRouter()
  const queryClient = new QueryClient({
    defaultOptions: { mutations: { retry: false } },
  })

  const Host = defineComponent({
    setup() {
      const mutation = useLoginMutation()
      return () =>
        h(
          'button',
          {
            type: 'button',
            onClick: () =>
              mutation.mutate({
                email: 'user@example.com',
                password: 'secret',
              }),
          },
          'submit',
        )
    },
  })

  const wrapper = mount(Host, {
    global: {
      plugins: [createPinia(), [VueQueryPlugin, { queryClient }], router],
    },
  })

  return { wrapper, router, queryClient }
}

describe('useLoginMutation', () => {
  beforeEach(async () => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  afterEach(() => {
    vi.restoreAllMocks()
  })

  it('stores the access token in the auth store on success', async () => {
    const router = createTestRouter()
    await router.push('/login')
    await router.isReady()

    vi.mocked(apiClient.post).mockResolvedValueOnce({
      data: {
        data: {
          access_token: 'plain-token-xyz',
          token_type: 'Bearer',
          token_id: 42,
          name: 'web',
          abilities: ['*'],
          expires_at: null,
        },
      },
      status: 200,
      statusText: 'OK',
      headers: {},
      config: {} as never,
    })

    const { wrapper } = mountMutationHost()
    const authStore = useAuthStore()

    expect(authStore.token).toBeNull()

    await wrapper.find('button').trigger('click')
    await flushPromises()

    expect(apiClient.post).toHaveBeenCalledWith('/auth/login', {
      email: 'user@example.com',
      password: 'secret',
    })
    expect(authStore.token).toBe('plain-token-xyz')
    expect(authStore.isAuthenticated).toBe(true)

    wrapper.unmount()
  })

  it('does not store the token when the request fails', async () => {
    const router = createTestRouter()
    await router.push('/login')
    await router.isReady()

    vi.mocked(apiClient.post).mockRejectedValueOnce(new Error('Invalid credentials'))

    const { wrapper } = mountMutationHost()
    const authStore = useAuthStore()

    await wrapper.find('button').trigger('click')
    await flushPromises()

    expect(authStore.token).toBeNull()
    expect(authStore.isAuthenticated).toBe(false)
    expect(apiClient.post).toHaveBeenCalledTimes(1)

    wrapper.unmount()
  })
})
