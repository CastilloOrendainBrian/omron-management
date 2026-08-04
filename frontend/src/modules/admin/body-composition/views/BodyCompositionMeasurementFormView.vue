<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useBodyCompositionMeasurementQuery } from '@/modules/admin/body-composition/composables/useBodyCompositionMeasurementQuery'
import { useCreateBodyCompositionMeasurementMutation } from '@/modules/admin/body-composition/composables/useCreateBodyCompositionMeasurementMutation'
import { useUpdateBodyCompositionMeasurementMutation } from '@/modules/admin/body-composition/composables/useUpdateBodyCompositionMeasurementMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import MeasurementSessionInput from '@/modules/common/components/MeasurementSessionInput.vue'
import type {
  BodyCompositionMeasurement,
  CreateBodyCompositionMeasurementPayload,
  UpdateBodyCompositionMeasurementPayload,
} from '@/types/api/BodyCompositionMeasurement'

interface FormValues {
  measurement_session_id: number
  body_fat_percentage: number | null
  muscle_percentage: number | null
  visceral_fat_level: number | null
  metabolic_age: number | null
  bmr_kcal: number | null
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
const measurementQuery = useBodyCompositionMeasurementQuery(measurementId)

const createMutation = useCreateBodyCompositionMeasurementMutation()
const updateMutation = useUpdateBodyCompositionMeasurementMutation(measurementId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    measurement_session_id: yup
      .number()
      .typeError('Ingresa un ID de sesión')
      .required()
      .positive()
      .integer()
      .label('sesión de medición'),
    body_fat_percentage: yup
      .number()
      .nullable()
      .transform((v) => (Number.isNaN(v) ? null : v))
      .min(0)
      .max(99.9)
      .label('% grasa'),
    muscle_percentage: yup
      .number()
      .nullable()
      .transform((v) => (Number.isNaN(v) ? null : v))
      .min(0)
      .max(99.9)
      .label('% músculo'),
    visceral_fat_level: yup
      .number()
      .nullable()
      .transform((v) => (Number.isNaN(v) ? null : v))
      .min(0)
      .max(100)
      .label('grasa visceral'),
    metabolic_age: yup
      .number()
      .nullable()
      .transform((v) => (Number.isNaN(v) ? null : v))
      .min(0)
      .max(150)
      .label('edad metabólica'),
    bmr_kcal: yup
      .number()
      .nullable()
      .transform((v) => (Number.isNaN(v) ? null : v))
      .min(0)
      .max(10000)
      .label('BMR'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<FormValues>({
  validationSchema: schema,
  initialValues: {
    measurement_session_id: 0,
    body_fat_percentage: null,
    muscle_percentage: null,
    visceral_fat_level: null,
    metabolic_age: null,
    bmr_kcal: null,
  },
})

const { value: sessionId } = useField<number>('measurement_session_id')
const { value: bodyFat } = useField<number | null>('body_fat_percentage')
const { value: muscle } = useField<number | null>('muscle_percentage')
const { value: visceral } = useField<number | null>('visceral_fat_level')
const { value: metabolicAge } = useField<number | null>('metabolic_age')
const { value: bmr } = useField<number | null>('bmr_kcal')

watch(
  () => measurementQuery.data.value,
  (m) => {
    if (m) {
      setValues({
        measurement_session_id: m.measurement_session_id,
        body_fat_percentage: m.body_fat_percentage,
        muscle_percentage: m.muscle_percentage,
        visceral_fat_level: m.visceral_fat_level,
        metabolic_age: m.metabolic_age,
        bmr_kcal: m.bmr_kcal,
      })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) {
    resetForm({
      values: {
        measurement_session_id: 0,
        body_fat_percentage: null,
        muscle_percentage: null,
        visceral_fat_level: null,
        metabolic_age: null,
        bmr_kcal: null,
      },
    })
  }
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
    void router.push({ name: 'admin-body-composition' })
  }
  const basePayload = {
    body_fat_percentage: values.body_fat_percentage,
    muscle_percentage: values.muscle_percentage,
    visceral_fat_level: values.visceral_fat_level,
    metabolic_age: values.metabolic_age,
    bmr_kcal: values.bmr_kcal,
  }
  if (isEdit.value) {
    const payload: UpdateBodyCompositionMeasurementPayload = basePayload
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateBodyCompositionMeasurementPayload = {
      measurement_session_id: values.measurement_session_id,
      ...basePayload,
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
          {{ isEdit ? 'Editar composición corporal' : 'Nueva composición corporal' }}
        </h1>
        <p class="text-sm text-stone-500">Datos de bioimpedancia: grasa, músculo, visceral, BMR.</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-body-composition' }"
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

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <FormField label="% grasa" helper-text="0 a 99.9. Opcional.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="bodyFat"
              type="number"
              step="0.1"
              min="0"
              max="99.9"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="22.5"
            />
          </template>
        </FormField>
        <FormField label="% músculo" helper-text="0 a 99.9. Opcional.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="muscle"
              type="number"
              step="0.1"
              min="0"
              max="99.9"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="35.0"
            />
          </template>
        </FormField>
        <FormField label="Grasa visceral" helper-text="0 a 100. Opcional.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="visceral"
              type="number"
              step="1"
              min="0"
              max="100"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="8"
            />
          </template>
        </FormField>
        <FormField label="Edad metabólica" helper-text="0 a 150. Opcional.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="metabolicAge"
              type="number"
              step="1"
              min="0"
              max="150"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="30"
            />
          </template>
        </FormField>
        <FormField label="BMR (kcal)" helper-text="0 a 10000. Opcional.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model.number="bmr"
              type="number"
              step="1"
              min="0"
              max="10000"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="1650"
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
          :to="{ name: 'admin-body-composition' }"
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
