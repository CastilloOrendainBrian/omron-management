<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useSkinfoldProtocolsQuery } from '@/modules/admin/skinfold-protocol/composables/useSkinfoldProtocolsQuery'
import { useDeleteSkinfoldProtocolMutation } from '@/modules/admin/skinfold-protocol/composables/useDeleteSkinfoldProtocolMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import type { ListSkinfoldProtocolsParams, SkinfoldProtocol } from '@/types/api/SkinfoldProtocol'

const router = useRouter()
const searchName = ref('')
const searchSitesCount = ref('')
const page = ref(1)
const perPage = ref(15)

const params = computed<ListSkinfoldProtocolsParams>(() => ({
  page: page.value,
  per_page: perPage.value,
  name: searchName.value.trim() || undefined,
  sites_count: searchSitesCount.value ? Number(searchSitesCount.value) : undefined,
}))

const protocolsQuery = useSkinfoldProtocolsQuery(params)
const deleteMutation = useDeleteSkinfoldProtocolMutation()

const protocols = computed<SkinfoldProtocol[]>(() => protocolsQuery.data.value?.data ?? [])
const meta = computed(() => protocolsQuery.data.value?.meta)
const totalPages = computed(() => meta.value?.last_page ?? 1)
const isSearching = computed(
  () => searchName.value.trim().length > 0 || searchSitesCount.value.trim().length > 0,
)

const deleteError = computed<string | null>(() => {
  if (!deleteMutation.isError.value) return null
  return getApiErrorMessage(deleteMutation.error.value, 'No pudimos eliminar el protocolo.')
})

function onSearch(): void {
  page.value = 1
}
function clearSearch(): void {
  searchName.value = ''
  searchSitesCount.value = ''
  page.value = 1
}
function goToPage(next: number): void {
  if (next < 1 || next > totalPages.value) return
  page.value = next
}
function onEdit(item: SkinfoldProtocol): void {
  void router.push({ name: 'admin-skinfold-protocols-edit', params: { id: item.id } })
}
function onDelete(item: SkinfoldProtocol): void {
  const confirmed = window.confirm(
    `¿Eliminar el protocolo "${item.name}"? Las mediciones que lo usen podrían dejar de mostrar el nombre.`,
  )
  if (!confirmed) return
  deleteMutation.mutate(item.id)
}
</script>

<template>
  <section class="space-y-4 md:space-y-5">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">Protocolos de plicometría</h1>
        <p class="text-sm text-stone-500">{{ meta?.total ?? 0 }} protocolo(s) registrado(s).</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-skinfold-protocols-new' }"
        class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
      >
        <i class="ri-add-line" /> Nuevo protocolo
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
            placeholder="Ej. Jackson-Pollock 3"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60"
          />
        </div>
        <div class="space-y-1">
          <label for="filter-sites" class="text-xs font-medium text-stone-600">N° de sitios</label>
          <input
            id="filter-sites"
            v-model="searchSitesCount"
            type="number"
            min="1"
            max="50"
            placeholder="3, 7, 10..."
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
      <div v-if="protocolsQuery.isLoading.value" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-loader-4-line animate-spin text-2xl block mx-auto mb-2" />
        Cargando protocolos…
      </div>
      <div
        v-else-if="protocolsQuery.isError.value"
        class="p-10 text-center text-sm text-rose-600"
        role="alert"
      >
        {{ getApiErrorMessage(protocolsQuery.error.value, 'Error al cargar los protocolos.') }}
      </div>
      <div v-else-if="protocols.length === 0" class="p-10 text-center text-sm text-stone-500">
        <i class="ri-book-mark-line text-3xl block mx-auto mb-2 text-stone-400" />
        No se encontraron protocolos
        <span v-if="isSearching"> con los filtros aplicados</span>.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-stone-50 text-stone-500 uppercase text-[11px] tracking-wide">
            <tr>
              <th scope="col" class="text-left font-medium py-3 px-4">ID</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Nombre</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Sitios</th>
              <th scope="col" class="text-left font-medium py-3 px-4">Descripción</th>
              <th scope="col" class="text-right font-medium py-3 px-4">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="p in protocols"
              :key="p.id"
              class="border-t border-stone-100 hover:bg-stone-50/60"
            >
              <td class="py-3 px-4 align-middle text-stone-500">#{{ p.id }}</td>
              <td class="py-3 px-4 align-middle text-stone-800 font-medium">{{ p.name }}</td>
              <td class="py-3 px-4 align-middle text-stone-600 text-right">{{ p.sites_count }}</td>
              <td class="py-3 px-4 align-middle text-stone-500 max-w-md truncate">
                {{ p.description ?? '—' }}
              </td>
              <td class="py-3 px-4 align-middle">
                <div class="flex items-center justify-end gap-2">
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50"
                    @click="onEdit(p)"
                  >
                    <i class="ri-edit-line" /> Editar
                  </button>
                  <button
                    type="button"
                    :disabled="
                      deleteMutation.isPending.value && deleteMutation.variables.value === p.id
                    "
                    class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="onDelete(p)"
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
