<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useSkinfoldProtocolQuery } from '@/modules/admin/skinfold-protocol/composables/useSkinfoldProtocolQuery'
import { useCreateSkinfoldProtocolMutation } from '@/modules/admin/skinfold-protocol/composables/useCreateSkinfoldProtocolMutation'
import { useUpdateSkinfoldProtocolMutation } from '@/modules/admin/skinfold-protocol/composables/useUpdateSkinfoldProtocolMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import type {
  CreateSkinfoldProtocolPayload,
  SkinfoldProtocol,
  UpdateSkinfoldProtocolPayload,
} from '@/types/api/SkinfoldProtocol'

interface FormValues {
  name: string
  sites_count: number
  description: string
}

const route = useRoute()
const router = useRouter()

const protocolId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => protocolId.value !== null)
const protocolQuery = useSkinfoldProtocolQuery(protocolId)

const createMutation = useCreateSkinfoldProtocolMutation()
const updateMutation = useUpdateSkinfoldProtocolMutation(protocolId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    name: yup.string().required().max(255).label('nombre'),
    sites_count: yup
      .number()
      .typeError('Ingresa la cantidad de sitios')
      .required()
      .min(1)
      .max(50)
      .integer()
      .label('cantidad de sitios'),
    description: yup.string().nullable().label('descripción'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<FormValues>({
  validationSchema: schema,
  initialValues: { name: '', sites_count: 1, description: '' },
})

const { value: name } = useField<string>('name')
const { value: sitesCount } = useField<number>('sites_count')
const { value: description } = useField<string>('description')

watch(
  () => protocolQuery.data.value,
  (p) => {
    if (p) {
      setValues({
        name: p.name,
        sites_count: p.sites_count,
        description: p.description ?? '',
      })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) resetForm({ values: { name: '', sites_count: 1, description: '' } })
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar el protocolo.')
})

const isBusy = computed(
  () =>
    isSubmitting.value ||
    createMutation.isPending.value ||
    updateMutation.isPending.value ||
    (isEdit.value && protocolQuery.isFetching.value),
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
    void router.push({ name: 'admin-skinfold-protocols' })
  }
  const basePayload = {
    sites_count: values.sites_count,
    description: values.description || null,
  }
  if (isEdit.value) {
    const payload: UpdateSkinfoldProtocolPayload = { name: values.name, ...basePayload }
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateSkinfoldProtocolPayload = { name: values.name, ...basePayload }
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-2xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar protocolo' : 'Nuevo protocolo' }}
        </h1>
        <p class="text-sm text-stone-500">
          Catálogo de protocolos de plicometría (Jackson-Pollock, Durnin-Womersley, etc.).
        </p>
      </div>
      <RouterLink
        :to="{ name: 'admin-skinfold-protocols' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && protocolQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(protocolQuery.error.value, 'No pudimos cargar el protocolo.') }}
    </div>

    <form
      novalidate
      class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-6 space-y-4"
      @submit.prevent="onSubmit"
    >
      <FormField label="Nombre" required>
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="name"
            type="text"
            maxlength="255"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="Jackson-Pollock 3 sitios"
          />
        </template>
      </FormField>

      <FormField label="Cantidad de sitios" required helper-text="Entre 1 y 50.">
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model.number="sitesCount"
            type="number"
            min="1"
            max="50"
            step="1"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="3"
          />
        </template>
      </FormField>

      <FormField label="Descripción" helper-text="Opcional.">
        <template #default="{ id, describedBy, invalid, disabled }">
          <textarea
            :id="id"
            v-model="description"
            rows="3"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="Notas sobre el protocolo, ecuaciones, fuentes..."
          />
        </template>
      </FormField>

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
          :to="{ name: 'admin-skinfold-protocols' }"
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
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear protocolo' }}
        </button>
      </div>
    </form>
  </section>
</template>
