<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useUserProfilesQuery } from '@/modules/admin/profiles/composables/useUserProfilesQuery'
import { useDeleteUserProfileMutation } from '@/modules/admin/profiles/composables/useDeleteUserProfileMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import type {
  ActivityLevel,
  ListUserProfilesParams,
  Sex,
  UserProfile,
} from '@/types/api/UserProfile'

const router = useRouter()
const filterSex = ref<Sex | ''>('')
const filterActivity = ref<ActivityLevel | ''>('')
const page = ref(1)
const perPage = ref(15)

const params = computed<ListUserProfilesParams>(() => ({
  page: page.value,
  per_page: perPage.value,
  sex: filterSex.value || undefined,
  activity_level: filterActivity.value || undefined,
}))

const profilesQuery = useUserProfilesQuery(params)
const deleteMutation = useDeleteUserProfileMutation()

const profiles = computed<UserProfile[]>(() => profilesQuery.data.value?.data ?? [])
const meta = computed(() => profilesQuery.data.value?.meta)
const totalPages = computed(() => meta.value?.last_page ?? 1)

const isSearching = computed(() => filterSex.value !== '' || filterActivity.value !== '')

const deleteError = computed<string | null>(() => {
  if (!deleteMutation.isError.value) return null
  return getApiErrorMessage(deleteMutation.error.value, 'No pudimos eliminar el perfil.')
})

const sexLabels: Record<Sex, string> = { male: 'Masculino', female: 'Femenino' }
const activityLabels: Record<ActivityLevel, string> = {
  sedentary: 'Sedentario',
  light: 'Ligero',
  moderate: 'Moderado',
  active: 'Activo',
  very_active: 'Muy activo',
}

function onSearch(): void {
  page.value = 1
}
function clearSearch(): void {
  filterSex.value = ''
  filterActivity.value = ''
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
function onEdit(item: UserProfile): void {
  void router.push({ name: 'admin-profiles-edit', params: { id: item.id } })
}
function onDelete(item: UserProfile): void {
  const confirmed = window.confirm(
    `¿Eliminar el perfil #${item.id}? Esta acción no se puede deshacer.`,
  )
  if (!confirmed) return
  deleteMutation.mutate(item.id)
}
</script>

<template>
  <section class="space-y-4 md:space-y-5">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">Perfiles</h1>
        <p class="text-sm text-stone-500">{{ meta?.total ?? 0 }} perfil(es) registrado(s).</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-profiles-new' }"
        class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
      >
        <i class="ri-user-settings-line" /> Nuevo perfil
      </RouterLink>
    </header>

    <div class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-4">
      <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" @submit.prevent="onSearch">
        <div class="space-y-1">
          <label for="filter-sex" class="text-xs font-medium text-stone-600">Sexo</label>
          <select
            id="filter-sex"
            v-model="filterSex"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white"
          >
            <option value="">Todos</option>
            <option value="male">Masculino</option>
            <option value="female">Femenino</option>
          </select>
        </div>
        <div class="space-y-1">
          <label for="filter-activity" class="text-xs font-medium text-stone-600">Actividad</label>
          <select
            id="filter-activity"
            v-model="filterActivity"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white"
          >
            <option value="">Todos</option>
            <option v-for="(label, value) in activityLabels" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
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
      <div v-if="profilesQuery.isLoading.value" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-loader-4-line animate-spin text-2xl block mx-auto mb-2" />
        Cargando perfiles…
      </div>
      <div
        v-else-if="profilesQuery.isError.value"
        class="p-10 text-center text-sm text-rose-600"
        role="alert"
      >
        {{ getApiErrorMessage(profilesQuery.error.value, 'Error al cargar los perfiles.') }}
      </div>
      <div v-else-if="profiles.length === 0" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-user-search-line text-3xl block mx-auto mb-2 text-stone-400" />
        No se encontraron perfiles
        <span v-if="isSearching"> con los filtros aplicados</span>.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-stone-50 text-stone-500 uppercase text-[11px] tracking-wide">
            <tr>
              <th scope="col" class="text-left font-medium py-3 px-4">Usuario</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Sexo</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Nacimiento</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Estatura</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Actividad</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="profile in profiles"
              :key="profile.id"
              class="border-t border-stone-100 hover:bg-stone-50/60"
            >
              <td class="py-3 px-4 align-middle text-stone-800">#{{ profile.user_id }}</td>
              <td class="py-3 px-4 align-middle text-stone-600">{{ sexLabels[profile.sex] }}</td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{ formatDate(profile.birth_date) }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{
                  profile.height_reference_cm !== null ? `${profile.height_reference_cm} cm` : '—'
                }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{ profile.activity_level ? activityLabels[profile.activity_level] : '—' }}
              </td>
              <td class="py-3 px-4 align-middle">
                <div class="flex items-center justify-end gap-2">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50"
                    @click="onEdit(profile)"
                  >
                    <i class="ri-edit-line" /> Editar
                  </button>
                  <button
                    type="button"
                    :disabled="
                      deleteMutation.isPending.value &&
                      deleteMutation.variables.value === profile.id
                    "
                    class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="onDelete(profile)"
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
