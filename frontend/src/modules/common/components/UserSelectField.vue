<script setup lang="ts">
import { computed } from 'vue'
import { useUsersQuery } from '@/modules/admin/composables/useUsersQuery'

interface Props {
  modelValue: string
  disabled?: boolean
  invalid?: boolean
  describedBy?: string
  inputId: string
  emptyLabel?: string
  showEmpty?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  invalid: false,
  describedBy: undefined,
  emptyLabel: 'Sin asignar',
  showEmpty: true,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const usersQuery = useUsersQuery({ per_page: 100 })
const users = computed(() => usersQuery.data.value?.data ?? [])

function onChange(event: Event): void {
  const target = event.target as HTMLSelectElement
  emit('update:modelValue', target.value)
}
</script>

<template>
  <select
    :id="inputId"
    :value="modelValue"
    :aria-invalid="invalid ? 'true' : 'false'"
    :aria-describedby="describedBy"
    :disabled="disabled"
    class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
    @change="onChange"
  >
    <option v-if="showEmpty" value="">{{ emptyLabel }}</option>
    <option v-for="u in users" :key="u.id" :value="String(u.id)">
      #{{ u.id }} — {{ u.name }} ({{ u.email }})
    </option>
  </select>
</template>
