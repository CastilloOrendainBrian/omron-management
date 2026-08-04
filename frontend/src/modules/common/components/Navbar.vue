<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

interface Props {
  brand?: string
  showMenuToggle?: boolean
}

withDefaults(defineProps<Props>(), {
  brand: 'Omron',
  showMenuToggle: true,
})

const emit = defineEmits<{
  'toggle-menu': []
  logout: []
}>()

const router = useRouter()
const authStore = useAuthStore()
const { user, isAuthenticated } = storeToRefs(authStore)

const userMenuOpen = ref(false)

function toggleUserMenu(): void {
  userMenuOpen.value = !userMenuOpen.value
}

function closeUserMenu(): void {
  userMenuOpen.value = false
}

async function onLogout(): Promise<void> {
  userMenuOpen.value = false
  authStore.clearSession()
  emit('logout')
  await router.push({ name: 'login' })
}

function getInitials(name: string | null | undefined): string {
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length === 1) return parts[0]!.charAt(0).toUpperCase()
  return (parts[0]!.charAt(0) + parts[parts.length - 1]!.charAt(0)).toUpperCase()
}
</script>

<template>
  <div
    class="py-2 px-6 bg-surface-sidebar flex items-center shadow-sm shadow-stone-300/40 shrink-0 z-30"
  >
    <button
      v-if="showMenuToggle"
      type="button"
      class="text-lg text-stone-800 font-semibold md:hidden"
      :aria-label="userMenuOpen ? 'Cerrar menú' : 'Abrir menú'"
      @click="emit('toggle-menu')"
    >
      <i class="ri-menu-line" />
    </button>

    <RouterLink
      :to="isAuthenticated ? { name: 'dashboard' } : { name: 'home' }"
      class="ml-2 md:ml-0 font-semibold text-stone-800 text-sm"
    >
      {{ brand }}
    </RouterLink>

    <ul class="ml-auto flex items-center">
      <li class="relative">
        <button
          type="button"
          class="flex items-center"
          :aria-expanded="userMenuOpen"
          aria-haspopup="menu"
          @click="toggleUserMenu"
        >
          <div class="flex-shrink-0 w-10 h-10 relative">
            <div
              class="w-10 h-10 rounded-full bg-stone-200 text-stone-700 flex items-center justify-center text-sm font-semibold ring-2 ring-white"
              aria-hidden="true"
            >
              {{ getInitials(user?.name) }}
            </div>
            <span
              class="top-0 left-7 absolute w-3 h-3 bg-lime-500 border-2 border-white rounded-full"
              aria-hidden="true"
            />
          </div>
          <div class="p-2 hidden xl:block text-left min-w-0 max-w-[14rem] truncate">
            <h2 class="text-sm font-semibold text-stone-800">
              {{ user?.name ?? 'Invitado' }}
            </h2>
            <p class="text-xs text-stone-500">
              {{ user?.email ?? 'Sin sesión' }}
            </p>
          </div>
        </button>

        <ul
          v-if="userMenuOpen"
          class="absolute right-0 mt-2 py-1.5 rounded-md bg-white border border-stone-200 w-44 shadow-lg shadow-stone-300/40 z-40"
          role="menu"
          @click="closeUserMenu"
        >
          <li role="none">
            <button
              type="button"
              class="w-full text-left flex items-center text-[13px] py-1.5 px-4 text-stone-600 hover:text-brand-500 hover:bg-stone-50"
              role="menuitem"
            >
              <i class="ri-user-line mr-2" /> Perfil
            </button>
          </li>
          <li role="none">
            <button
              type="button"
              class="w-full text-left flex items-center text-[13px] py-1.5 px-4 text-stone-600 hover:text-brand-500 hover:bg-stone-50"
              role="menuitem"
            >
              <i class="ri-settings-3-line mr-2" /> Configuración
            </button>
          </li>
          <li role="none">
            <button
              type="button"
              class="w-full text-left flex items-center text-[13px] py-1.5 px-4 text-stone-600 hover:text-brand-500 hover:bg-stone-50"
              role="menuitem"
              @click="onLogout"
            >
              <i class="ri-logout-box-r-line mr-2" /> Cerrar sesión
            </button>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>
