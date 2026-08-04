<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import StatCard from '@/modules/common/components/StatCard.vue'

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)
</script>

<template>
  <section class="space-y-6">
    <header>
      <h1 class="text-2xl font-semibold text-stone-800">Panel de administración</h1>
      <p class="text-sm text-stone-500">
        Bienvenido, <strong>{{ user?.name ?? 'administrador' }}</strong
        >. Aquí verás las métricas globales del sistema.
      </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <StatCard title="Usuarios totales" :value="0" />
      <StatCard title="Mediciones registradas" :value="0" />
      <StatCard title="Metas activas" :value="0" />
    </div>
  </section>
</template>
