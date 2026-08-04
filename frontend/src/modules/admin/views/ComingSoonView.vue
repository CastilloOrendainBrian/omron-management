<script setup lang="ts">
import { useRoute, RouterLink } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()

const title = computed(() => {
  const name = String(route.name ?? '')
  if (name === 'admin-measurements') return 'Mediciones'
  if (name === 'admin-goals') return 'Metas'
  return 'Próximamente'
})
</script>

<template>
  <div
    class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-8 text-center"
  >
    <h1 class="text-2xl font-semibold text-stone-800 mb-2">{{ title }}</h1>
    <p class="text-sm text-stone-500">
      Esta sección aún no está disponible. Estamos trabajando en ella.
    </p>
    <RouterLink
      :to="{ name: 'admin-dashboard' }"
      class="mt-6 inline-flex items-center text-sm font-medium text-brand-500 hover:text-brand-600"
    >
      ← Volver al panel
    </RouterLink>
  </div>
</template>
