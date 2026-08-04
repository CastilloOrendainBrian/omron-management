<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, useRoute, type RouteLocationRaw } from 'vue-router'

export interface SidebarItem {
  label: string
  to: RouteLocationRaw
  icon?: string
  badge?: string | number
  badgeClass?: string
  children?: SidebarItem[]
}

export interface SidebarGroup {
  title?: string
  items: SidebarItem[]
}

interface Props {
  groups: SidebarGroup[]
  brandLabel?: string
  brandHighlight?: string
  mobileOpen?: boolean
}

withDefaults(defineProps<Props>(), {
  brandLabel: 'OMRON',
  brandHighlight: 'MGMT',
  mobileOpen: false,
})

const emit = defineEmits<{
  'update:mobileOpen': [value: boolean]
  navigate: []
}>()

const openItems = ref<Set<string>>(new Set())
const route = useRoute()

function toggleItem(label: string): void {
  if (openItems.value.has(label)) {
    openItems.value.delete(label)
  } else {
    openItems.value.clear()
    openItems.value.add(label)
  }
}

function isItemActive(item: SidebarItem): boolean {
  if (item.children?.length) {
    return item.children.some((child) => isItemActive(child))
  }
  if (typeof item.to === 'object' && item.to !== null && 'name' in item.to) {
    return route.name === item.to.name
  }
  if (typeof item.to === 'string') {
    return route.path === item.to
  }
  return false
}

function closeMobile(): void {
  emit('update:mobileOpen', false)
  emit('navigate')
}
</script>

<template>
  <aside
    :class="[
      'fixed left-0 top-0 w-64 h-full bg-surface-sidebar p-4 z-50 transition-transform overflow-y-auto',
      mobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
    ]"
  >
    <RouterLink
      :to="{ name: 'home' }"
      class="flex items-center pb-4 border-b border-stone-300"
      @click="closeMobile"
    >
      <h2 class="font-bold text-2xl text-stone-800">
        {{ brandLabel }}
        <span class="bg-brand-500 text-white px-2 rounded-md">{{ brandHighlight }}</span>
      </h2>
    </RouterLink>

    <div v-for="(group, gi) in groups" :key="gi" class="mt-4">
      <span v-if="group.title" class="text-stone-400 font-bold text-xs uppercase tracking-wide">
        {{ group.title }}
      </span>

      <ul class="mt-2">
        <li
          v-for="item in group.items"
          :key="item.label"
          class="mb-1"
          :class="{
            selected: openItems.has(item.label) || isItemActive(item),
            active: isItemActive(item) && !openItems.has(item.label),
          }"
        >
          <component
            :is="item.children?.length ? 'button' : RouterLink"
            :to="item.children?.length ? undefined : item.to"
            type="button"
            class="w-full flex font-semibold items-center py-2 px-4 text-stone-800 hover:bg-stone-800 hover:text-stone-100 rounded-md group-[.active]:bg-stone-800 group-[.active]:text-white group-[.selected]:bg-stone-900 group-[.selected]:text-stone-100"
            :class="{ 'bg-stone-900 text-stone-100': openItems.has(item.label) }"
            @click="item.children?.length ? toggleItem(item.label) : closeMobile()"
          >
            <i v-if="item.icon" :class="item.icon" class="mr-3 text-lg" />
            <span class="text-sm">{{ item.label }}</span>

            <span
              v-if="item.badge"
              :class="[
                'ml-auto px-2 py-0.5 text-xs font-medium rounded-full',
                item.badgeClass || 'bg-brand-500/10 text-brand-500',
              ]"
            >
              {{ item.badge }}
            </span>

            <i
              v-if="item.children?.length"
              class="ri-arrow-right-s-line ml-auto transition-transform"
              :class="{ 'rotate-90': openItems.has(item.label) }"
            />
          </component>

          <ul v-if="item.children?.length && openItems.has(item.label)" class="pl-7 mt-2">
            <li v-for="child in item.children" :key="child.label" class="mb-4">
              <RouterLink
                :to="child.to"
                class="text-stone-800 text-sm flex items-center hover:text-brand-500 before:content-[''] before:w-1 before:h-1 before:rounded-full before:bg-stone-300 before:mr-3"
                @click="closeMobile"
              >
                {{ child.label }}
              </RouterLink>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </aside>
</template>
