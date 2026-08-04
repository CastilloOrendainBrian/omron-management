<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useGoalsQuery } from '@/modules/admin/goals/composables/useGoalsQuery'
import { useDeleteGoalMutation } from '@/modules/admin/goals/composables/useDeleteGoalMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import type { Goal, GoalStatus, ListGoalsParams } from '@/types/api/Goal'

const router = useRouter()
const filterStatus = ref<GoalStatus | ''>('')
const page = ref(1)
const perPage = ref(15)

const params = computed<ListGoalsParams>(() => ({
  page: page.value,
  per_page: perPage.value,
  status: filterStatus.value || undefined,
}))

const goalsQuery = useGoalsQuery(params)
const deleteMutation = useDeleteGoalMutation()

const goals = computed<Goal[]>(() => goalsQuery.data.value?.data ?? [])
const meta = computed(() => goalsQuery.data.value?.meta)
const totalPages = computed(() => meta.value?.last_page ?? 1)

const isSearching = computed(() => filterStatus.value !== '')

const deleteError = computed<string | null>(() => {
  if (!deleteMutation.isError.value) return null
  return getApiErrorMessage(deleteMutation.error.value, 'No pudimos eliminar la meta.')
})

const statusLabels: Record<GoalStatus, string> = {
  active: 'Activo',
  achieved: 'Logrado',
  abandoned: 'Abandonado',
}

const statusClasses: Record<GoalStatus, string> = {
  active: 'bg-emerald-500/10 text-emerald-600',
  achieved: 'bg-blue-500/10 text-blue-600',
  abandoned: 'bg-stone-200 text-stone-600',
}

function onSearch(): void {
  page.value = 1
}
function clearSearch(): void {
  filterStatus.value = ''
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
function onEdit(item: Goal): void {
  void router.push({ name: 'admin-goals-edit', params: { id: item.id } })
}
function onDelete(item: Goal): void {
  const confirmed = window.confirm(
    `¿Eliminar la meta #${item.id}? Esta acción no se puede deshacer.`,
  )
  if (!confirmed) return
  deleteMutation.mutate(item.id)
}
</script>

<template>
  <section class="space-y-4 md:space-y-5">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">Metas</h1>
        <p class="text-sm text-stone-500">{{ meta?.total ?? 0 }} meta(s) registrada(s).</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-goals-new' }"
        class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
      >
        <i class="bx bx-target-lock" /> Nueva meta
      </RouterLink>
    </header>

    <div class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-4">
      <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" @submit.prevent="onSearch">
        <div class="space-y-1">
          <label for="filter-status" class="text-xs font-medium text-stone-600">Estado</label>
          <select
            id="filter-status"
            v-model="filterStatus"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white"
          >
            <option value="">Todos</option>
            <option v-for="(label, value) in statusLabels" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
        </div>
        <div class="flex items-end gap-2 lg:col-span-3">
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
      <div v-if="goalsQuery.isLoading.value" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-loader-4-line animate-spin text-2xl block mx-auto mb-2" />
        Cargando metas…
      </div>
      <div
        v-else-if="goalsQuery.isError.value"
        class="p-10 text-center text-sm text-rose-600"
        role="alert"
      >
        {{ getApiErrorMessage(goalsQuery.error.value, 'Error al cargar las metas.') }}
      </div>
      <div v-else-if="goals.length === 0" class="p-10 text-center text-sm text-stone-500">
        <i class="bx bx-target-lock text-3xl block mx-auto mb-2 text-stone-400" />
        No se encontraron metas
        <span v-if="isSearching"> con los filtros aplicados</span>.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-stone-50 text-stone-500 uppercase text-[11px] tracking-wide">
            <tr>
              <th scope="col" class="text-left font-medium py-3 px-4">Usuario</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Peso objetivo</th>
              <th scope="col" class="text-left font-medium py-3 px-4">% grasa</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Inicio</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Meta</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Estado</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="goal in goals"
              :key="goal.id"
              class="border-t border-stone-100 hover:bg-stone-50/60"
            >
              <td class="py-3 px-4 align-middle text-stone-800">#{{ goal.user_id }}</td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{ goal.target_weight_kg !== null ? `${goal.target_weight_kg} kg` : '—' }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{
                  goal.target_body_fat_percentage !== null
                    ? `${goal.target_body_fat_percentage}%`
                    : '—'
                }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{ formatDate(goal.start_date) }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600">
                {{ formatDate(goal.target_date) }}
              </td>
              <td class="py-3 px-4 align-middle">
                <span
                  :class="[
                    'inline-block px-2 py-0.5 rounded text-[11px] font-semibold leading-none',
                    statusClasses[goal.status],
                  ]"
                >
                  {{ statusLabels[goal.status] }}
                </span>
              </td>
              <td class="py-3 px-4 align-middle">
                <div class="flex items-center justify-end gap-2">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50"
                    @click="onEdit(goal)"
                  >
                    <i class="ri-edit-line" /> Editar
                  </button>
                  <button
                    type="button"
                    :disabled="
                      deleteMutation.isPending.value && deleteMutation.variables.value === goal.id
                    "
                    class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="onDelete(goal)"
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
