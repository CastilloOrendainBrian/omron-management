<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

function goHome(): void {
  void router.replace({ name: 'home' })
}
</script>

<template>
  <main
    class="flex min-h-screen flex-col items-center justify-center gap-4 bg-slate-50 px-4 text-center dark:bg-slate-900"
  >
    <p class="text-7xl font-bold text-slate-300 dark:text-slate-700">404</p>
    <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Página no encontrada</h1>
    <p class="max-w-sm text-sm text-slate-500 dark:text-slate-400">
      La ruta que intentas abrir no existe o fue movida.
    </p>
    <button
      type="button"
      class="mt-2 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-300"
      @click="goHome"
    >
      Volver al inicio
    </button>
  </main>
</template>
