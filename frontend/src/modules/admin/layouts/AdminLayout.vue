<script setup lang="ts">
import AppShell from '@/modules/common/components/AppShell.vue'
import type { SidebarGroup } from '@/modules/common/components/Sidebar.vue'
import { RouterView } from 'vue-router'

const adminNav: SidebarGroup[] = [
  {
    title: 'Administración',
    items: [
      { label: 'Dashboard', to: { name: 'admin-dashboard' }, icon: 'ri-home-2-line' },
      { label: 'Usuarios', to: { name: 'admin-users' }, icon: 'bx bx-user' },
      { label: 'Mediciones', to: { name: 'admin-measurements' }, icon: 'bx bx-list-ul' },
      { label: 'Metas', to: { name: 'admin-goals' }, icon: 'bx bx-target-lock' },
    ],
  },
]
</script>

<template>
  <AppShell :groups="adminNav" brand="Omron Admin" brand-label="OMRON" brand-highlight="ADMIN">
    <RouterView />
  </AppShell>
</template>
