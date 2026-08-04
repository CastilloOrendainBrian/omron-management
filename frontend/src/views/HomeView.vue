<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

const authStore = useAuthStore()
const { isAuthenticated, user } = storeToRefs(authStore)

const features = [
  {
    icon: 'ri-scales-2-line',
    title: 'Registro de mediciones',
    description:
      'Captura peso, BMI, % grasa, % músculo, grasa visceral, edad corporal y RM desde tu báscula Omron.',
  },
  {
    icon: 'ri-line-chart-line',
    title: 'Tendencias y gráficas',
    description: 'Visualiza la evolución de cada métrica a lo largo del tiempo y detecta patrones.',
  },
  {
    icon: 'bx bx-target-lock',
    title: 'Metas de peso',
    description:
      'Define objetivos de peso o composición corporal y sigue tu progreso hasta alcanzarlos.',
  },
]
</script>

<template>
  <main class="min-h-screen bg-surface-content text-stone-800 font-sans">
    <header class="bg-surface-sidebar border-b border-stone-300/40">
      <nav class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <RouterLink :to="{ name: 'home' }" class="font-bold text-xl text-stone-800">
          OMRON<span class="bg-brand-500 text-white px-2 rounded-md ml-1">MGMT</span>
        </RouterLink>
        <div class="flex items-center gap-2">
          <template v-if="isAuthenticated">
            <span class="text-sm text-stone-600 hidden sm:inline">
              Hola, <strong>{{ user?.name ?? 'usuario' }}</strong>
            </span>
            <RouterLink
              :to="{ name: 'dashboard' }"
              class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600"
            >
              Ir al dashboard <i class="ri-arrow-right-line" />
            </RouterLink>
          </template>
          <template v-else>
            <RouterLink
              :to="{ name: 'login' }"
              class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-stone-700 hover:text-stone-900"
            >
              Iniciar sesión
            </RouterLink>
            <RouterLink
              :to="{ name: 'login' }"
              class="inline-flex items-center rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600"
            >
              Empezar
            </RouterLink>
          </template>
        </div>
      </nav>
    </header>

    <section class="max-w-6xl mx-auto px-6 py-16 lg:py-24 text-center">
      <span
        class="inline-flex items-center gap-1 rounded-full bg-brand-500/10 px-3 py-1 text-xs font-semibold text-brand-600"
      >
        <i class="ri-heart-pulse-line" /> Control de peso corporal
      </span>
      <h1 class="mt-6 text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-stone-800">
        Sigue tu progreso
        <span class="text-brand-500">con datos reales</span>
      </h1>
      <p class="mt-6 text-base sm:text-lg text-stone-600 max-w-2xl mx-auto">
        Centraliza las mediciones de tu báscula Omron, identifica tendencias y trabaja hacia tus
        metas de composición corporal con un sistema pensado para ti.
      </p>
      <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
        <RouterLink
          v-if="!isAuthenticated"
          :to="{ name: 'login' }"
          class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
        >
          Iniciar sesión <i class="ri-arrow-right-line" />
        </RouterLink>
        <RouterLink
          v-else
          :to="{ name: 'dashboard' }"
          class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-200"
        >
          Ir al dashboard <i class="ri-arrow-right-line" />
        </RouterLink>
        <a
          href="#features"
          class="inline-flex items-center gap-2 rounded-md border border-stone-200 bg-white px-6 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-50"
        >
          Ver funcionalidades
        </a>
      </div>
    </section>

    <section id="features" class="max-w-6xl mx-auto px-6 pb-16 lg:pb-24">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="feature in features"
          :key="feature.title"
          class="bg-white rounded-xl border border-stone-200 shadow-sm shadow-stone-200/60 p-6"
        >
          <div
            class="w-12 h-12 rounded-md bg-brand-500/10 text-brand-500 flex items-center justify-center mb-4"
          >
            <i :class="feature.icon" class="text-2xl" />
          </div>
          <h2 class="text-base font-semibold text-stone-800 mb-1">{{ feature.title }}</h2>
          <p class="text-sm text-stone-600">{{ feature.description }}</p>
        </div>
      </div>
    </section>

    <footer class="border-t border-stone-300/40 bg-surface-sidebar">
      <div
        class="max-w-6xl mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-stone-500"
      >
        <span>© Omron Management</span>
        <span class="flex items-center gap-1">
          <i class="ri-shield-check-line" /> Tus datos se mantienen privados y seguros.
        </span>
      </div>
    </footer>
  </main>
</template>
