<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useBodyCompositionMeasurementsQuery } from '@/modules/admin/body-composition/composables/useBodyCompositionMeasurementsQuery'
import { useDeleteBodyCompositionMeasurementMutation } from '@/modules/admin/body-composition/composables/useDeleteBodyCompositionMeasurementMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import type {
  BodyCompositionMeasurement,
  ListBodyCompositionMeasurementsParams,
} from '@/types/api/BodyCompositionMeasurement'

const router = useRouter()
const searchSessionId = ref('')
const page = ref(1)
const perPage = ref(15)

const params = computed<ListBodyCompositionMeasurementsParams>(() => ({
  page: page.value,
  per_page: perPage.value,
  measurement_session_id: searchSessionId.value ? Number(searchSessionId.value) : undefined,
}))

const measurementsQuery = useBodyCompositionMeasurementsQuery(params)
const deleteMutation = useDeleteBodyCompositionMeasurementMutation()

const measurements = computed<BodyCompositionMeasurement[]>(
  () => measurementsQuery.data.value?.data ?? [],
)
const meta = computed(() => measurementsQuery.data.value?.meta)
const totalPages = computed(() => meta.value?.last_page ?? 1)
const isSearching = computed(() => searchSessionId.value.trim().length > 0)

const deleteError = computed<string | null>(() => {
  if (!deleteMutation.isError.value) return null
  return getApiErrorMessage(deleteMutation.error.value, 'No pudimos eliminar la medición.')
})

function onSearch(): void {
  page.value = 1
}
function clearSearch(): void {
  searchSessionId.value = ''
  page.value = 1
}
function goToPage(next: number): void {
  if (next < 1 || next > totalPages.value) return
  page.value = next
}
function onEdit(item: BodyCompositionMeasurement): void {
  void router.push({ name: 'admin-body-composition-edit', params: { id: item.id } })
}
function onDelete(item: BodyCompositionMeasurement): void {
  const confirmed = window.confirm(`¿Eliminar la medición de composición corporal #${item.id}?`)
  if (!confirmed) return
  deleteMutation.mutate(item.id)
}
</script>

<template>
  <section class="space-y-4 md:space-y-5">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">Composición corporal</h1>
        <p class="text-sm text-stone-500">
          {{ meta?.total ?? 0 }} registro(s) de grasa, músculo, visceral, edad metabólica y BMR.
        </p>
      </div>
      <RouterLink
        :to="{ name: 'admin-body-composition-new' }"
        class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
      >
        <i class="ri-heart-pulse-line" /> Nueva medición
      </RouterLink>
    </header>

    <div class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-4">
      <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" @submit.prevent="onSearch">
        <div class="space-y-1 lg:col-span-2">
          <label for="filter-session" class="text-xs font-medium text-stone-600"
            >ID de sesión</label
          >
          <input
            id="filter-session"
            v-model="searchSessionId"
            type="number"
            min="1"
            placeholder="Filtrar por sesión de medición"
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
      <div v-if="measurementsQuery.isLoading.value" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-loader-4-line animate-spin text-2xl block mx-auto mb-2" />
        Cargando mediciones…
      </div>
      <div
        v-else-if="measurementsQuery.isError.value"
        class="p-10 text-center text-sm text-rose-600"
        role="alert"
      >
        {{ getApiErrorMessage(measurementsQuery.error.value, 'Error al cargar las mediciones.') }}
      </div>
      <div v-else-if="measurements.length === 0" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-heart-pulse-line text-3xl block mx-auto mb-2 text-stone-400" />
        No se encontraron mediciones
        <span v-if="isSearching"> con los filtros aplicados</span>.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-stone-50 text-stone-500 uppercase text-[11px] tracking-wide">
            <tr>
              <th scope="col" class="text-left font-medium py-3 px-4">ID</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Sesión</th>
              <th scope="col" class="text-right font-medium py-3 px-4">% grasa</th>
              <th scope="col" class="text-right font-medium py-3 px-4">% músculo</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Grasa visceral</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Edad metab.</th>
              <th scope="col" class="text-right font-medium py-3 px-4">BMR</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="m in measurements"
              :key="m.id"
              class="border-t border-stone-100 hover:bg-stone-50/60"
            >
              <td class="py-3 px-4 align-middle text-stone-500">#{{ m.id }}</td>
              <td class="py-3 px-4 align-middle text-stone-800">#{{ m.measurement_session_id }}</td>
              <td class="py-3 px-4 align-middle text-stone-600 text-right">
                {{ m.body_fat_percentage !== null ? `${m.body_fat_percentage}%` : '—' }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600 text-right">
                {{ m.muscle_percentage !== null ? `${m.muscle_percentage}%` : '—' }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600 text-right">
                {{ m.visceral_fat_level ?? '—' }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600 text-right">
                {{ m.metabolic_age ?? '—' }}
              </td>
              <td class="py-3 px-4 align-middle text-stone-600 text-right">
                {{ m.bmr_kcal !== null ? `${m.bmr_kcal} kcal` : '—' }}
              </td>
              <td class="py-3 px-4 align-middle">
                <div class="flex items-center justify-end gap-2">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50"
                    @click="onEdit(m)"
                  >
                    <i class="ri-edit-line" /> Editar
                  </button>
                  <button
                    type="button"
                    :disabled="
                      deleteMutation.isPending.value && deleteMutation.variables.value === m.id
                    "
                    class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="onDelete(m)"
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
