<script setup lang="ts">
import { computed, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { RouterView } from 'vue-router'
import AppShell from '@/modules/common/components/AppShell.vue'
import type { SidebarGroup } from '@/modules/common/components/Sidebar.vue'
import { useMeQuery } from '@/modules/auth/composables/useMeQuery'
import { useAuthStore } from '@/modules/auth/stores/auth.store'

const meQuery = useMeQuery()
const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

watch(
  () => meQuery.data.value,
  (u) => {
    if (u) authStore.setUser(u)
  },
  { immediate: true },
)

const isAdmin = computed(() => {
  const roles = user.value?.roles ?? []
  return roles.includes('admin') || roles.includes('super-admin')
})

const navGroups = computed<SidebarGroup[]>(() => {
  const groups: SidebarGroup[] = [
    {
      title: 'Principal',
      items: [
        { label: 'Dashboard', to: { name: 'dashboard' }, icon: 'ri-home-2-line' },
        { label: 'Mediciones', to: { name: 'measurements' }, icon: 'bx bx-list-ul' },
        { label: 'Metas', to: { name: 'goals' }, icon: 'bx bx-target-lock' },
        { label: 'Perfil', to: { name: 'profile' }, icon: 'ri-user-line' },
      ],
    },
  ]

  if (isAdmin.value) {
    groups.push({
      title: 'Administración',
      items: [
        {
          label: 'Panel admin',
          to: { name: 'admin-dashboard' },
          icon: 'ri-shield-keyhole-line',
        },
        { label: 'Usuarios', to: { name: 'admin-users' }, icon: 'ri-team-line' },
      ],
    })
  }

  return groups
})
</script>

<template>
  <AppShell :groups="navGroups" brand="Omron" brand-label="OMRON" brand-highlight="MGMT">
    <RouterView />
  </AppShell>
</template>
