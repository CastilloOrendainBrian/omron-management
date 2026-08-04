<script setup lang="ts">
import { useRoute, RouterLink } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()

const title = computed(() => {
  const name = String(route.name ?? '')
  if (name === 'register') return 'Registro'
  if (name === 'forgot-password') return 'Recuperar contraseña'
  return 'Próximamente'
})
</script>

<template>
  <div class="bg-white rounded-xl shadow-lg p-8 text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ title }}</h2>
    <p class="text-sm text-gray-600">
      Esta pantalla aún no está disponible. Estamos trabajando en ella.
    </p>
    <RouterLink
      :to="{ name: 'login' }"
      class="mt-6 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline"
    >
      ← Volver a iniciar sesión
    </RouterLink>
  </div>
</template>
