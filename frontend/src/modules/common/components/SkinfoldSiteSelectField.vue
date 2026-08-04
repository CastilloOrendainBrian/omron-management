<script setup lang="ts">
import type { SkinfoldSite } from '@/types/api/SkinfoldSite'

interface Props {
  modelValue: number | string | null | undefined
  inputId: string
  sites?: SkinfoldSite[]
  describedBy?: string
  invalid?: boolean
  disabled?: boolean
}

withDefaults(defineProps<Props>(), {
  sites: () => [],
  describedBy: undefined,
  invalid: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: number]
}>()
</script>

<template>
  <select
    :id="inputId"
    :value="modelValue ?? ''"
    :aria-invalid="invalid ? 'true' : 'false'"
    :aria-describedby="describedBy"
    :disabled="disabled"
    class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
    @change="
      (event) => emit('update:modelValue', Number((event.target as HTMLSelectElement).value) || 0)
    "
  >
    <option value="">Sitio</option>
    <option v-for="site in sites" :key="site.id" :value="site.id">
      {{ site.name }} ({{ site.code }})
    </option>
  </select>
</template>
