<script setup lang="ts">
import { useSkinfoldProtocolsQuery } from '@/modules/admin/skinfold/composables/useSkinfoldProtocolsQuery'

interface Props {
  modelValue: number | string | null | undefined
  inputId: string
  describedBy?: string
  invalid?: boolean
  disabled?: boolean
  required?: boolean
}

withDefaults(defineProps<Props>(), {
  describedBy: undefined,
  invalid: false,
  disabled: false,
  required: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: number]
}>()

const protocolsQuery = useSkinfoldProtocolsQuery({ per_page: 100 })
</script>

<template>
  <select
    :id="inputId"
    :value="modelValue ?? ''"
    :aria-invalid="invalid ? 'true' : 'false'"
    :aria-describedby="describedBy"
    :disabled="disabled"
    :required="required"
    class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
    @change="
      (event) => emit('update:modelValue', Number((event.target as HTMLSelectElement).value) || 0)
    "
  >
    <option value="">Selecciona un protocolo</option>
    <option v-for="p in protocolsQuery.data.value?.data ?? []" :key="p.id" :value="p.id">
      {{ p.name }} ({{ p.sites_count }} sitios)
    </option>
  </select>
</template>
