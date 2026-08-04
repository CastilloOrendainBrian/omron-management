<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useSkinfoldMeasurementQuery } from '@/modules/admin/skinfold/composables/useSkinfoldMeasurementQuery'
import { useCreateSkinfoldMeasurementMutation } from '@/modules/admin/skinfold/composables/useCreateSkinfoldMeasurementMutation'
import { useUpdateSkinfoldMeasurementMutation } from '@/modules/admin/skinfold/composables/useUpdateSkinfoldMeasurementMutation'
import { useSkinfoldProtocolsQuery } from '@/modules/admin/skinfold-protocol/composables/useSkinfoldProtocolsQuery'
import { useSkinfoldSitesQuery } from '@/modules/admin/skinfold-site/composables/useSkinfoldSitesQuery'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import MeasurementSessionInput from '@/modules/common/components/MeasurementSessionInput.vue'
import SkinfoldProtocolSelectField from '@/modules/common/components/SkinfoldProtocolSelectField.vue'
import SkinfoldSiteSelectField from '@/modules/common/components/SkinfoldSiteSelectField.vue'
import type {
  CreateSkinfoldMeasurementPayload,
  SkinfoldMeasurementDetailInput,
  UpdateSkinfoldMeasurementPayload,
} from '@/types/api/SkinfoldMeasurement'

interface DetailRow extends SkinfoldMeasurementDetailInput {}

interface FormValues {
  measurement_session_id: number
  skinfold_protocol_id: number
  estimated_body_fat_percentage: number | null
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
const measurementQuery = useSkinfoldMeasurementQuery(measurementId)

const createMutation = useCreateSkinfoldMeasurementMutation()
const updateMutation = useUpdateSkinfoldMeasurementMutation(measurementId.value ?? 0)

const protocolsQuery = useSkinfoldProtocolsQuery({ per_page: 100 })
const sitesQuery = useSkinfoldSitesQuery({ per_page: 100 })
const protocols = computed(() => protocolsQuery.data.value?.data ?? [])
const sites = computed(() => sitesQuery.data.value?.data ?? [])

const details = reactive<DetailRow[]>([{ skinfold_site_id: 0, value_mm: 0 }])

function addDetail(): void {
  details.push({ skinfold_site_id: 0, value_mm: 0 })
}
function removeDetail(index: number): void {
  if (details.length > 1) details.splice(index, 1)
}

const schema = toTypedSchema(
  yup.object({
    measurement_session_id: yup
      .number()
      .typeError('Ingresa un ID de sesión')
      .required()
      .positive()
      .integer()
      .label('sesión de medición'),
    skinfold_protocol_id: yup
      .number()
      .typeError('Selecciona un protocolo')
      .required()
      .positive()
      .integer()
      .label('protocolo'),
    estimated_body_fat_percentage: yup
      .number()
      .nullable()
      .transform((v) => (Number.isNaN(v) ? null : v))
      .min(0)
      .max(99.9)
      .label('% grasa estimado'),
    // details se valida manualmente con detailsValid (ver handler)
  }),
)

const initialDetails = (): DetailRow[] => [{ skinfold_site_id: 0, value_mm: 0 }]

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<FormValues>({
  validationSchema: schema,
  initialValues: {
    measurement_session_id: 0,
    skinfold_protocol_id: 0,
    estimated_body_fat_percentage: null,
  },
})

const { value: sessionId, errorMessage: sessionIdError } =
  useField<number>('measurement_session_id')
const { value: protocolId, errorMessage: protocolIdError } =
  useField<number>('skinfold_protocol_id')
const { value: estimatedFat, errorMessage: estimatedFatError } = useField<number | null>(
  'estimated_body_fat_percentage',
)

function syncDetailsToForm(rows: DetailRow[]): void {
  details.splice(0, details.length, ...rows)
}

watch(
  () => measurementQuery.data.value,
  (m) => {
    if (m) {
      setValues({
        measurement_session_id: m.measurement_session_id,
        skinfold_protocol_id: m.skinfold_protocol_id,
        estimated_body_fat_percentage: m.estimated_body_fat_percentage,
      })
      if (m.details && m.details.length > 0) {
        syncDetailsToForm(
          m.details.map((d) => ({ skinfold_site_id: d.skinfold_site_id, value_mm: d.value_mm })),
        )
      }
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) {
    syncDetailsToForm(initialDetails())
    resetForm({
      values: {
        measurement_session_id: 0,
        skinfold_protocol_id: 0,
        estimated_body_fat_percentage: null,
      },
    })
  }
})

const detailsValid = computed(() =>
  details.every((d) => d.skinfold_site_id > 0 && d.value_mm >= 0 && d.value_mm <= 999.9),
)

const detailErrors = computed<(string | null)[]>(() =>
  details.map((d) => {
    if (d.skinfold_site_id <= 0) return 'Selecciona un sitio'
    if (d.value_mm < 0) return 'Debe ser ≥ 0'
    if (d.value_mm > 999.9) return 'Debe ser ≤ 999.9'
    return null
  }),
)

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar la medición.')
})

const isBusy = computed(
  () => isSubmitting.value || createMutation.isPending.value || updateMutation.isPending.value,
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
  if (!detailsValid.value) return

  const goBack = (): void => {
    void router.push({ name: 'admin-skinfold' })
  }
  const cleanDetails = details
    .filter((d) => d.skinfold_site_id > 0)
    .map((d) => ({ skinfold_site_id: d.skinfold_site_id, value_mm: d.value_mm }))

  if (isEdit.value) {
    const payload: UpdateSkinfoldMeasurementPayload = {
      skinfold_protocol_id: values.skinfold_protocol_id,
      estimated_body_fat_percentage: values.estimated_body_fat_percentage,
      details: cleanDetails,
    }
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateSkinfoldMeasurementPayload = {
      measurement_session_id: values.measurement_session_id,
      skinfold_protocol_id: values.skinfold_protocol_id,
      estimated_body_fat_percentage: values.estimated_body_fat_percentage,
      details: cleanDetails,
    }
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-3xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar plicometría' : 'Nueva plicometría' }}
        </h1>
        <p class="text-sm text-stone-500">Medición de pliegues cutáneos por sitio anatómico.</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-skinfold' }"
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
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <FormField
          label="ID de sesión de medición"
          helper-text="La sesión debe existir."
          required
          :error-message="sessionIdError"
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

        <FormField label="Protocolo" required :error-message="protocolIdError">
          <template #default="{ id, describedBy, invalid, disabled }">
            <SkinfoldProtocolSelectField
              v-model="protocolId"
              :input-id="id"
              :protocols="protocols"
              :described-by="describedBy"
              :invalid="invalid"
              :disabled="disabled"
              :required="!isEdit"
            />
          </template>
        </FormField>
      </div>

      <FormField
        label="% grasa estimado"
        helper-text="0 a 99.9. Opcional."
        :error-message="estimatedFatError"
      >
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model.number="estimatedFat"
            type="number"
            step="0.1"
            min="0"
            max="99.9"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="18.5"
          />
        </template>
      </FormField>

      <div>
        <div class="flex items-center justify-between mb-2">
          <h2 class="text-sm font-semibold text-stone-800">Pliegues medidos</h2>
          <button
            type="button"
            class="inline-flex items-center gap-1 text-xs font-medium text-brand-500 hover:text-brand-600"
            @click="addDetail"
          >
            <i class="ri-add-line" /> Agregar sitio
          </button>
        </div>

        <div v-if="details.length === 0" class="text-sm text-stone-500 italic">
          Agrega al menos un pliegue.
        </div>

        <ul class="space-y-2">
          <li
            v-for="(detail, index) in details"
            :key="index"
            class="p-3 rounded-lg border border-stone-200 bg-stone-50/40 space-y-2"
          >
            <div class="flex flex-col sm:flex-row sm:items-end gap-2">
              <div class="flex-1 space-y-1">
                <label :for="`site-${index}`" class="text-xs font-medium text-stone-600"
                  >Sitio</label
                >
                <SkinfoldSiteSelectField
                  :input-id="`site-${index}`"
                  v-model="detail.skinfold_site_id"
                  :sites="sites"
                  :invalid="detailErrors[index] !== null"
                />
              </div>
              <div class="flex-1 space-y-1">
                <label :for="`value-${index}`" class="text-xs font-medium text-stone-600"
                  >Valor (mm)</label
                >
                <input
                  :id="`value-${index}`"
                  v-model.number="detail.value_mm"
                  type="number"
                  step="0.1"
                  min="0"
                  max="999.9"
                  :aria-invalid="detailErrors[index] !== null ? 'true' : 'false'"
                  class="w-full text-sm py-2 px-2.5 border rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 bg-white"
                  :class="
                    detailErrors[index] !== null
                      ? 'border-rose-400 focus:border-rose-500'
                      : 'border-stone-200'
                  "
                  placeholder="12.5"
                />
              </div>
              <button
                type="button"
                :disabled="details.length === 1"
                class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-rose-200 text-rose-500 hover:bg-rose-50 disabled:opacity-30 disabled:cursor-not-allowed"
                :aria-label="`Quitar sitio ${index + 1}`"
                @click="removeDetail(index)"
              >
                <i class="ri-close-line text-lg" />
              </button>
            </div>
            <p v-if="detailErrors[index]" class="text-xs text-rose-600" role="alert">
              {{ detailErrors[index] }}
            </p>
          </li>
        </ul>
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
          :to="{ name: 'admin-skinfold' }"
          class="inline-flex items-center rounded-md border border-stone-200 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50"
        >
          Cancelar
        </RouterLink>
        <button
          type="submit"
          :disabled="isBusy || !detailsValid"
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
