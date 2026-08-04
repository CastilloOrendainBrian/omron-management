<script setup lang="ts">
import AppShell from '@/modules/common/components/AppShell.vue'
import type { SidebarGroup } from '@/modules/common/components/Sidebar.vue'
import { RouterView } from 'vue-router'

const adminNav: SidebarGroup[] = [
  {
    title: 'Administración',
    items: [
      { label: 'Dashboard', to: { name: 'admin-dashboard' }, icon: 'ri-home-2-line' },
      { label: 'Usuarios', to: { name: 'admin-users' }, icon: 'ri-team-line' },
      { label: 'Perfiles', to: { name: 'admin-profiles' }, icon: 'ri-user-settings-line' },
      { label: 'Metas', to: { name: 'admin-goals' }, icon: 'bx bx-target-lock' },
      { label: 'Dispositivos', to: { name: 'admin-devices' }, icon: 'ri-scales-2-line' },
    ],
  },
  {
    title: 'Mediciones',
    items: [
      {
        label: 'Antropometría',
        to: { name: 'admin-anthropometric' },
        icon: 'ri-scales-2-line',
      },
      {
        label: 'Composición corporal',
        to: { name: 'admin-body-composition' },
        icon: 'ri-heart-pulse-line',
      },
      {
        label: 'Plicometría',
        to: { name: 'admin-skinfold' },
        icon: 'ri-pencil-ruler-2-line',
      },
    ],
  },
]
</script>

<template>
  <AppShell :groups="adminNav" brand="Omron Admin" brand-label="OMRON" brand-highlight="ADMIN">
    <RouterView />
  </AppShell>
</template>
