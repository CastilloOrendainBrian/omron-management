<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useSkinfoldSiteQuery } from '@/modules/admin/skinfold-site/composables/useSkinfoldSiteQuery'
import { useCreateSkinfoldSiteMutation } from '@/modules/admin/skinfold-site/composables/useCreateSkinfoldSiteMutation'
import { useUpdateSkinfoldSiteMutation } from '@/modules/admin/skinfold-site/composables/useUpdateSkinfoldSiteMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import type {
  CreateSkinfoldSitePayload,
  SkinfoldSite,
  UpdateSkinfoldSitePayload,
} from '@/types/api/SkinfoldSite'

interface FormValues {
  code: string
  name: string
}

const route = useRoute()
const router = useRouter()

const siteId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => siteId.value !== null)
const siteQuery = useSkinfoldSiteQuery(siteId)

const createMutation = useCreateSkinfoldSiteMutation()
const updateMutation = useUpdateSkinfoldSiteMutation(siteId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    code: yup
      .string()
      .required()
      .max(50)
      .matches(/^[a-z0-9_]+$/, 'Solo minúsculas, números y guiones bajos (snake_case)')
      .label('código'),
    name: yup.string().required().max(255).label('nombre'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<FormValues>({
  validationSchema: schema,
  initialValues: { code: '', name: '' },
})

const { value: code, errorMessage: codeError } = useField<string>('code')
const { value: name, errorMessage: nameError } = useField<string>('name')

watch(
  () => siteQuery.data.value,
  (s) => {
    if (s) setValues({ code: s.code, name: s.name })
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) resetForm({ values: { code: '', name: '' } })
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar el sitio.')
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
  const goBack = (): void => {
    void router.push({ name: 'admin-skinfold-sites' })
  }
  if (isEdit.value) {
    const payload: UpdateSkinfoldSitePayload = { code: values.code, name: values.name }
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateSkinfoldSitePayload = { code: values.code, name: values.name }
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-2xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar sitio' : 'Nuevo sitio' }}
        </h1>
        <p class="text-sm text-stone-500">Sitio anatómico donde se toma el pliegue cutáneo.</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-skinfold-sites' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && siteQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(siteQuery.error.value, 'No pudimos cargar el sitio.') }}
    </div>

    <form
      novalidate
      class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-6 space-y-4"
      @submit.prevent="onSubmit"
    >
      <FormField
        label="Código"
        required
        helper-text="Identificador único en snake_case (ej. triceps, subescapular)."
        :error-message="codeError"
      >
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="code"
            type="text"
            maxlength="50"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed font-mono"
            placeholder="triceps"
          />
        </template>
      </FormField>

      <FormField label="Nombre" required :error-message="nameError">
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
            placeholder="Tríceps"
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
          :to="{ name: 'admin-skinfold-sites' }"
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
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear sitio' }}
        </button>
      </div>
    </form>
  </section>
</template>
