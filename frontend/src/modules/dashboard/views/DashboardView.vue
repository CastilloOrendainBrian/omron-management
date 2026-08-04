<script setup lang="ts">
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import StatCard from '@/modules/common/components/StatCard.vue'

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

const firstName = computed(() => {
  const name = user.value?.name ?? 'usuario'
  return name.split(/\s+/)[0] ?? name
})

const isAdmin = computed(() => {
  const roles = user.value?.roles ?? []
  return roles.includes('admin') || roles.includes('super-admin')
})

const stats = computed(() => {
  if (isAdmin.value) {
    return [
      { title: 'Usuarios totales', value: 0, icon: 'ri-team-line' },
      { title: 'Mediciones registradas', value: 0, icon: 'bx bx-pulse' },
      { title: 'Metas activas', value: 0, icon: 'bx bx-target-lock' },
    ]
  }
  return [
    { title: 'Mis mediciones', value: 0, icon: 'bx bx-pulse' },
    { title: 'Peso actual', value: '—', icon: 'bx bx-scales' },
    { title: 'Metas activas', value: 0, icon: 'bx bx-target-lock' },
  ]
})
</script>

<template>
  <section class="space-y-4 md:space-y-5">
    <header
      class="rounded-md border border-stone-200 bg-gradient-to-br from-white to-surface-sidebar p-4 md:p-5 shadow-sm shadow-stone-200/60"
    >
      <p class="text-[11px] font-semibold uppercase tracking-wide text-brand-500">Dashboard</p>
      <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-800">
        Hola, {{ firstName }} 👋
      </h1>
      <p class="mt-1 text-xs md:text-sm text-stone-600">
        {{
          isAdmin
            ? 'Aquí tienes una vista general del sistema.'
            : 'Registra tus mediciones y sigue tu progreso a lo largo del tiempo.'
        }}
      </p>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <StatCard v-for="stat in stats" :key="stat.title" :title="stat.title" :value="stat.value">
        <template #action>
          <i :class="stat.icon" class="text-xl text-stone-300" />
        </template>
      </StatCard>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div
        class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-4 md:p-5"
      >
        <h2 class="text-sm font-semibold text-stone-800 mb-1">Acciones rápidas</h2>
        <p class="text-xs text-stone-500 mb-3">
          {{
            isAdmin
              ? 'Accede a las herramientas de administración.'
              : 'Empieza a registrar tu progreso.'
          }}
        </p>

        <div class="space-y-2">
          <RouterLink
            v-if="!isAdmin"
            :to="{ name: 'measurements' }"
            class="flex items-center justify-between gap-3 rounded-lg border border-stone-200 p-2.5 hover:bg-stone-50 transition-colors"
          >
            <div class="flex items-center gap-3 min-w-0">
              <span
                class="w-8 h-8 rounded-md bg-brand-500/10 text-brand-500 flex items-center justify-center shrink-0"
              >
                <i class="ri-add-line text-base" />
              </span>
              <div class="min-w-0">
                <p class="text-sm font-medium text-stone-800 truncate">Registrar medición</p>
                <p class="text-xs text-stone-500 truncate">
                  Captura peso, BMI y composición corporal.
                </p>
              </div>
            </div>
            <i class="ri-arrow-right-s-line text-stone-400 shrink-0" />
          </RouterLink>

          <RouterLink
            v-if="!isAdmin"
            :to="{ name: 'goals' }"
            class="flex items-center justify-between gap-3 rounded-lg border border-stone-200 p-2.5 hover:bg-stone-50 transition-colors"
          >
            <div class="flex items-center gap-3 min-w-0">
              <span
                class="w-8 h-8 rounded-md bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0"
              >
                <i class="bx bx-target-lock text-base" />
              </span>
              <div class="min-w-0">
                <p class="text-sm font-medium text-stone-800 truncate">Definir una meta</p>
                <p class="text-xs text-stone-500 truncate">
                  Establece un objetivo de peso o composición.
                </p>
              </div>
            </div>
            <i class="ri-arrow-right-s-line text-stone-400 shrink-0" />
          </RouterLink>

          <RouterLink
            v-if="isAdmin"
            :to="{ name: 'admin-users' }"
            class="flex items-center justify-between gap-3 rounded-lg border border-stone-200 p-2.5 hover:bg-stone-50 transition-colors"
          >
            <div class="flex items-center gap-3 min-w-0">
              <span
                class="w-8 h-8 rounded-md bg-brand-500/10 text-brand-500 flex items-center justify-center shrink-0"
              >
                <i class="ri-team-line text-base" />
              </span>
              <div class="min-w-0">
                <p class="text-sm font-medium text-stone-800 truncate">Gestionar usuarios</p>
                <p class="text-xs text-stone-500 truncate">Crear, editar o eliminar cuentas.</p>
              </div>
            </div>
            <i class="ri-arrow-right-s-line text-stone-400 shrink-0" />
          </RouterLink>

          <RouterLink
            v-if="isAdmin"
            :to="{ name: 'admin-dashboard' }"
            class="flex items-center justify-between gap-3 rounded-lg border border-stone-200 p-2.5 hover:bg-stone-50 transition-colors"
          >
            <div class="flex items-center gap-3 min-w-0">
              <span
                class="w-8 h-8 rounded-md bg-stone-800 text-white flex items-center justify-center shrink-0"
              >
                <i class="ri-shield-keyhole-line text-base" />
              </span>
              <div class="min-w-0">
                <p class="text-sm font-medium text-stone-800 truncate">Panel de administración</p>
                <p class="text-xs text-stone-500 truncate">Métricas globales del sistema.</p>
              </div>
            </div>
            <i class="ri-arrow-right-s-line text-stone-400 shrink-0" />
          </RouterLink>
        </div>
      </div>

      <div
        class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-4 md:p-5"
      >
        <h2 class="text-sm font-semibold text-stone-800 mb-1">Próximamente</h2>
        <p class="text-xs text-stone-500 mb-3">
          Funcionalidades que se conectarán a esta pantalla.
        </p>
        <ul class="space-y-2 text-sm text-stone-600">
          <li class="flex items-start gap-2">
            <i class="ri-bar-chart-2-line text-brand-500 mt-0.5" />
            <span>Gráficas de tendencia de peso, BMI y % grasa.</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="ri-heart-pulse-line text-brand-500 mt-0.5" />
            <span>Resumen de composición corporal (grasa visceral, músculo, RM).</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="ri-calendar-check-line text-brand-500 mt-0.5" />
            <span>Recordatorios para registrar mediciones periódicas.</span>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>
