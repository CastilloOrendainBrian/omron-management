<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useAnthropometricMeasurementQuery } from '@/modules/admin/anthropometric/composables/useAnthropometricMeasurementQuery'
import { useCreateAnthropometricMeasurementMutation } from '@/modules/admin/anthropometric/composables/useCreateAnthropometricMeasurementMutation'
import { useUpdateAnthropometricMeasurementMutation } from '@/modules/admin/anthropometric/composables/useUpdateAnthropometricMeasurementMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import MeasurementSessionInput from '@/modules/common/components/MeasurementSessionInput.vue'
import type {
  AnthropometricMeasurement,
  CreateAnthropometricMeasurementPayload,
  UpdateAnthropometricMeasurementPayload,
} from '@/types/api/AnthropometricMeasurement'

interface FormValues {
  measurement_session_id: number
  height_cm: number
  weight_kg: number
  bmi: number
}

const route = useRoute()
const router = useRouter()

const measurementId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => measurementId.value !== null)
const measurementQuery = useAnthropometricMeasurementQuery(measurementId)

const createMutation = useCreateAnthropometricMeasurementMutation()
const updateMutation = useUpdateAnthropometricMeasurementMutation(measurementId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    measurement_session_id: yup
      .number()
      .typeError('Ingresa un ID de sesión')
      .required()
      .positive()
      .integer()
      .label('sesión de medición'),
    height_cm: yup
      .number()
      .typeError('Ingresa la estatura')
      .required()
      .min(0)
      .max(300)
      .label('estatura'),
    weight_kg: yup.number().typeError('Ingresa el peso').required().min(0).max(700).label('peso'),
    bmi: yup.number().typeError('Ingresa el BMI').required().min(0).max(99.9).label('BMI'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<FormValues>({
  validationSchema: schema,
  initialValues: { measurement_session_id: 0, height_cm: 0, weight_kg: 0, bmi: 0 },
})

const { value: sessionId } = useField<number>('measurement_session_id')
const { value: heightCm } = useField<number>('height_cm')
const { value: weightKg } = useField<number>('weight_kg')
const { value: bmi } = useField<number>('bmi')

watch(
  () => measurementQuery.data.value,
  (m) => {
    if (m) {
      setValues({
        measurement_session_id: m.measurement_session_id,
        height_cm: m.height_cm,
        weight_kg: m.weight_kg,
        bmi: m.bmi,
      })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next)
    resetForm({ values: { measurement_session_id: 0, height_cm: 0, weight_kg: 0, bmi: 0 } })
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar la medición.')
})

const isBusy = computed(
  () =>
    isSubmitting.value ||
    createMutation.isPending.value ||
    updateMutation.isPending.value ||
    (isEdit.value && measurementQuery.isFetching.value),
)

function applyFieldErrors(error: unknown): void {
  if (!error || typeof error !== 'object' || !('response' in error)) return
  const fieldErrors = (error as { response?: { data?: { errors?: Record<string, string[]> } } })
    .response?.data?.errors
  if (!fieldErrors) return
  const mapped: Record<string, string> = {}
  for (const [key, msgs] of Object.entries(fieldErrors)) {
    if (msgs[0]) mapped[key] = msgs[0]
  }
  setErrors(mapped)
}

const onSubmit = handleSubmit((values) => {
  const goBack = (): void => {
    void router.push({ name: 'admin-anthropometric' })
  }
  if (isEdit.value) {
    const payload: UpdateAnthropometricMeasurementPayload = {
      height_cm: values.height_cm,
      weight_kg: values.weight_kg,
      bmi: values.bmi,
    }
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateAnthropometricMeasurementPayload = {
      measurement_session_id: values.measurement_session_id,
      height_cm: values.height_cm,
      weight_kg: values.weight_kg,
      bmi: values.bmi,
    }
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-2xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar medición antropométrica' : 'Nueva medición antropométrica' }}
        </h1>
        <p class="text-sm text-stone-500">
          Estatura, peso y BMI asociados a una sesión de medición.
        </p>
      </div>
      <RouterLink
        :to="{ name: 'admin-anthropometric' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && measurementQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(measurementQuery.error.value, 'No pudimos cargar la medición.') }}
    </div>

    <form
      novalidate
      class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-6 space-y-4"
      @submit.prevent="onSubmit"
    >
      <FormField
        label="ID de sesión de medición"
        helper-text="La sesión debe existir. El backend no expone un endpoint para crearlas todavía."
        required
      >
        <template #default="{ id, describedBy, invalid, disabled }">
          <MeasurementSessionInput
            v-model="sessionId"
            :input-id="id"
            :described-by="describedBy"
            :invalid="invalid"
            :disabled="disabled"
            :required="!isEdit"
          />
        </template>
      </FormField>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <FormField label="Estatura (cm)" required helper-text="0 a 300.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="heightCm"
              type="number"
              step="0.1"
              min="0"
              max="300"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="175"
            />
          </template>
        </FormField>
        <FormField label="Peso (kg)" required helper-text="0 a 700.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="weightKg"
              type="number"
              step="0.01"
              min="0"
              max="700"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="72.5"
            />
          </template>
        </FormField>
        <FormField label="BMI" required helper-text="0 a 99.9.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="bmi"
              type="number"
              step="0.1"
              min="0"
              max="99.9"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="23.5"
            />
          </template>
        </FormField>
      </div>

      <div
        v-if="submitError"
        class="rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700"
        role="alert"
        aria-live="assertive"
      >
        {{ submitError }}
      </div>

      <div class="flex items-center justify-end gap-2 pt-2">
        <RouterLink
          :to="{ name: 'admin-anthropometric' }"
          class="inline-flex items-center rounded-md border border-stone-200 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50"
        >
          Cancelar
        </RouterLink>
        <button
          type="submit"
          :disabled="isBusy"
          class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-brand-200"
        >
          <span
            v-if="isBusy"
            aria-hidden="true"
            class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"
          />
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear medición' }}
        </button>
      </div>
    </form>
  </section>
</template>
