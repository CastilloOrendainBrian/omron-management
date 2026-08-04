<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

const authStore = useAuthStore()
const { user, isAuthenticated } = storeToRefs(authStore)
const router = useRouter()

function logout(): void {
  authStore.clearSession()
  void router.push({ name: 'login' })
}
</script>

<template>
  <main class="min-h-screen bg-slate-50 px-4 py-12 dark:bg-slate-900">
    <div class="mx-auto max-w-2xl space-y-6">
      <header
        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800"
      >
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">
          Sistema de control de peso corporal
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
          Próximamente: registro de mediciones, tendencias y metas.
        </p>
      </header>

      <section
        v-if="isAuthenticated"
        class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200"
      >
        <p>
          Sesión iniciada como
          <strong>{{ user?.name ?? 'usuario' }}</strong>
          ({{ user?.email ?? '—' }}).
        </p>
        <button
          type="button"
          class="mt-3 inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-300"
          @click="logout"
        >
          Cerrar sesión
        </button>
      </section>

      <section
        v-else
        class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
      >
        No has iniciado sesión.
        <RouterLink
          :to="{ name: 'login' }"
          class="ml-1 font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
        >
          Iniciar sesión
        </RouterLink>
      </section>
    </div>
  </main>
</template>
