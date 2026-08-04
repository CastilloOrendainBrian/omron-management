<script setup lang="ts">
import { computed, useId } from 'vue'

interface Props {
  label: string
  modelValue?: string | number | null
  errorMessage?: string | null
  helperText?: string | null
  required?: boolean
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  errorMessage: null,
  helperText: null,
  required: false,
  disabled: false,
})

defineEmits<{
  'update:modelValue': [value: string]
}>()

const inputId = useId()
const helperId = useId()
const errorId = useId()

const describedBy = computed(() => {
  const ids: string[] = []
  if (props.errorMessage) ids.push(errorId)
  else if (props.helperText) ids.push(helperId)
  return ids.length > 0 ? ids.join(' ') : undefined
})
</script>

<template>
  <div class="space-y-1">
    <label :for="inputId" class="block mb-1 text-sm font-semibold antialiased text-stone-800">
      {{ label }}
      <span v-if="required" aria-hidden="true" class="text-brand-500">*</span>
    </label>

    <slot
      :id="inputId"
      :described-by="describedBy"
      :invalid="!!errorMessage"
      :disabled="disabled"
    />

    <p v-if="helperText && !errorMessage" :id="helperId" class="text-xs text-stone-500">
      {{ helperText }}
    </p>

    <p v-if="errorMessage" :id="errorId" class="text-xs text-rose-600" role="alert">
      {{ errorMessage }}
    </p>
  </div>
</template>
