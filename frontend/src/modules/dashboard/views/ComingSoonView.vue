<script setup lang="ts">
import { useRoute, RouterLink } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()

const title = computed(() => {
  const meta = route.meta as { title?: string } | undefined
  if (meta?.title) return meta.title
  const name = String(route.name ?? '')
  if (name === 'measurements') return 'Mediciones'
  if (name === 'goals') return 'Metas'
  if (name === 'profile') return 'Perfil'
  return 'Próximamente'
})
</script>

<template>
  <div
    class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-8 text-center"
  >
    <div
      class="mx-auto mb-4 w-12 h-12 rounded-full bg-brand-500/10 text-brand-500 flex items-center justify-center"
    >
      <i class="ri-tools-line text-2xl" />
    </div>
    <h1 class="text-2xl font-semibold text-stone-800 mb-2">{{ title }}</h1>
    <p class="text-sm text-stone-500 max-w-md mx-auto">
      Estamos construyendo esta sección. Mientras tanto, vuelve al dashboard para ver un resumen.
    </p>
    <RouterLink
      :to="{ name: 'dashboard' }"
      class="mt-6 inline-flex items-center gap-1 text-sm font-medium text-brand-500 hover:text-brand-600"
    >
      <i class="ri-arrow-left-line" /> Volver al dashboard
    </RouterLink>
  </div>
</template>
