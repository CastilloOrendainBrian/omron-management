<script setup lang="ts">
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

defineEmits<{
  'update:modelValue': [value: number]
}>()

function onInput(event: Event): void {
  const target = event.target as HTMLInputElement
  const value = target.value === '' ? 0 : Number(target.value)
  // Re-emit as number; the parent uses v-model.
  target.value = String(value)
  target.dispatchEvent(new Event('input', { bubbles: true }))
}
</script>

<template>
  <input
    :id="inputId"
    type="number"
    min="1"
    step="1"
    :value="modelValue ?? ''"
    :aria-invalid="invalid ? 'true' : 'false'"
    :aria-describedby="describedBy"
    :disabled="disabled"
    :required="required"
    placeholder="ID de la sesión de medición"
    class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
    @change="
      (event) => $emit('update:modelValue', Number((event.target as HTMLInputElement).value) || 0)
    "
  />
</template>
