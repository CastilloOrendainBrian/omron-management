<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUsersQuery } from '@/modules/admin/composables/useUsersQuery'
import { useDeleteUserMutation } from '@/modules/admin/composables/useDeleteUserMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import type { ListUsersParams, User } from '@/types/api/User'

const router = useRouter()
const authStore = useAuthStore()
const { user: currentUser } = storeToRefs(authStore)

const searchName = ref('')
const searchEmail = ref('')
const page = ref(1)
const perPage = ref(15)

const params = computed<ListUsersParams>(() => ({
  page: page.value,
  per_page: perPage.value,
  name: searchName.value.trim() || undefined,
  email: searchEmail.value.trim() || undefined,
}))

const usersQuery = useUsersQuery(params)
const deleteMutation = useDeleteUserMutation()

const users = computed<User[]>(() => usersQuery.data.value?.data ?? [])
const meta = computed(() => usersQuery.data.value?.meta)
const totalPages = computed(() => meta.value?.last_page ?? 1)

const deleteError = computed<string | null>(() => {
  if (!deleteMutation.isError.value) return null
  return getApiErrorMessage(
    deleteMutation.error.value,
    'No pudimos eliminar al usuario. Inténtalo de nuevo.',
  )
})

const isSearching = computed(
  () => searchName.value.trim().length > 0 || searchEmail.value.trim().length > 0,
)

function onSearch(): void {
  page.value = 1
}

function clearSearch(): void {
  searchName.value = ''
  searchEmail.value = ''
  page.value = 1
}

function goToPage(next: number): void {
  if (next < 1 || next > totalPages.value) return
  page.value = next
}

function formatDate(value: string | null): string {
  if (!value) return '—'
  return new Date(value).toLocaleDateString('es-MX', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
  })
}

function onEdit(user: User): void {
  void router.push({ name: 'admin-users-edit', params: { id: user.id } })
}

function onDelete(user: User): void {
  if (currentUser.value?.id === user.id) {
    window.alert('No puedes eliminar tu propio usuario desde aquí.')
    return
  }
  const confirmed = window.confirm(
    `¿Eliminar al usuario "${user.name}" (${user.email})? Esta acción no se puede deshacer.`,
  )
  if (!confirmed) return
  deleteMutation.mutate(user.id)
}
</script>

<template>
  <section class="space-y-6">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">Usuarios</h1>
        <p class="text-sm text-stone-500">{{ meta?.total ?? 0 }} usuario(s) registrado(s).</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-users-new' }"
        class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
      >
        <i class="ri-user-add-line" /> Nuevo usuario
      </RouterLink>
    </header>

    <div class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-4">
      <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" @submit.prevent="onSearch">
        <div class="space-y-1">
          <label for="filter-name" class="text-xs font-medium text-stone-600">Nombre</label>
          <input
            id="filter-name"
            v-model="searchName"
            type="text"
            placeholder="Buscar por nombre"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60"
          />
        </div>
        <div class="space-y-1">
          <label for="filter-email" class="text-xs font-medium text-stone-600">Correo</label>
          <input
            id="filter-email"
            v-model="searchEmail"
            type="email"
            placeholder="Buscar por correo"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60"
          />
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-2">
          <button
            type="submit"
            class="inline-flex items-center gap-2 rounded-md bg-stone-800 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-300"
          >
            <i class="ri-search-line" /> Buscar
          </button>
          <button
            v-if="isSearching"
            type="button"
            class="inline-flex items-center gap-2 rounded-md border border-stone-200 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50"
            @click="clearSearch"
          >
            <i class="ri-close-line" /> Limpiar
          </button>
        </div>
      </form>
    </div>

    <div
      v-if="deleteError"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
      aria-live="assertive"
    >
      {{ deleteError }}
    </div>

    <div
      class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 overflow-hidden"
    >
      <div v-if="usersQuery.isLoading.value" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-loader-4-line animate-spin text-2xl block mx-auto mb-2" />
        Cargando usuarios…
      </div>

      <div
        v-else-if="usersQuery.isError.value"
        class="p-10 text-center text-sm text-rose-600"
        role="alert"
      >
        {{ getApiErrorMessage(usersQuery.error.value, 'Error al cargar los usuarios.') }}
      </div>

      <div v-else-if="users.length === 0" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-user-search-line text-3xl block mx-auto mb-2 text-stone-400" />
        No se encontraron usuarios
        <span v-if="isSearching"> con los filtros aplicados</span>.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-stone-50 text-stone-500 uppercase text-[11px] tracking-wide">
            <tr>
              <th scope="col" class="text-left font-medium py-3 px-4">Nombre</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Correo</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Verificado</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Creado</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in users"
              :key="user.id"
              class="border-t border-stone-100 hover:bg-stone-50/60"
            >
              <td class="py-3 px-4 align-middle">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-full bg-stone-200 text-stone-700 flex items-center justify-center text-xs font-semibold"
                    aria-hidden="true"
                  >
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <div class="font-medium text-stone-800">{{ user.name }}</div>
                    <div class="text-xs text-stone-500">#{{ user.id }}</div>
                  </div>
                </div>
              </td>
              <td class="py-3 px-4 align-middle text-stone-600">{{ user.email }}</td>
              <td class="py-3 px-4 align-middle">
                <span
                  v-if="user.email_verified_at"
                  class="inline-block px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-600 text-[11px] font-semibold leading-none"
                >
                  Verificado
                </span>
                <span
                  v-else
                  class="inline-block px-2 py-0.5 rounded bg-stone-200 text-stone-600 text-[11px] font-semibold leading-none"
                >
                  Pendiente
                </span>
              </td>
              <td class="py-3 px-4 align-middle text-stone-500">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="py-3 px-4 align-middle">
                <div class="flex items-center justify-end gap-2">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50"
                    @click="onEdit(user)"
                  >
                    <i class="ri-edit-line" /> Editar
                  </button>
                  <button
                    type="button"
                    :disabled="
                      deleteMutation.isPending.value && deleteMutation.variables.value === user.id
                    "
                    class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="onDelete(user)"
                  >
                    <i class="ri-delete-bin-line" /> Eliminar
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div
        v-if="meta && meta.last_page > 1"
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-t border-stone-100 px-4 py-3 text-sm text-stone-600"
      >
        <span>
          Página <strong>{{ meta.page }}</strong> de <strong>{{ meta.last_page }}</strong>
          <span class="text-stone-400">({{ meta.per_page }} por página)</span>
        </span>
        <div class="flex items-center gap-2">
          <button
            type="button"
            :disabled="meta.page <= 1"
            class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-3 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed"
            @click="goToPage(meta.page - 1)"
          >
            <i class="ri-arrow-left-s-line" /> Anterior
          </button>
          <button
            type="button"
            :disabled="meta.page >= meta.last_page"
            class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-3 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed"
            @click="goToPage(meta.page + 1)"
          >
            Siguiente <i class="ri-arrow-right-s-line" />
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
