<script setup lang="ts">
import { ref } from 'vue'
import Sidebar, { type SidebarGroup } from '@/modules/common/components/Sidebar.vue'
import Navbar from '@/modules/common/components/Navbar.vue'

interface Props {
  groups: SidebarGroup[]
  brand?: string
  brandLabel?: string
  brandHighlight?: string
}

withDefaults(defineProps<Props>(), {
  brand: 'Omron',
  brandLabel: 'OMRON',
  brandHighlight: 'MGMT',
})

const mobileOpen = ref(false)

function toggleMobile(): void {
  mobileOpen.value = !mobileOpen.value
}

function closeMobile(): void {
  mobileOpen.value = false
}
</script>

<template>
  <div class="h-screen bg-surface-content text-stone-800 font-sans overflow-hidden">
    <Sidebar
      :groups="groups"
      :brand-label="brandLabel"
      :brand-highlight="brandHighlight"
      v-model:mobile-open="mobileOpen"
    />

    <div
      v-if="mobileOpen"
      class="fixed inset-0 bg-black/50 z-40 md:hidden"
      aria-hidden="true"
      @click="closeMobile"
    />

    <div class="md:ml-64 bg-surface-content h-screen transition-all flex flex-col">
      <Navbar :brand="brand" @toggle-menu="toggleMobile" @logout="closeMobile" />

      <div class="flex-1 min-h-0 overflow-y-auto p-4 md:p-6 scrollbar-stable">
        <slot />
      </div>
    </div>
  </div>
</template>
