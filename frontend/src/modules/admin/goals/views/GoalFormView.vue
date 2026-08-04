<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useGoalQuery } from '@/modules/admin/goals/composables/useGoalQuery'
import { useCreateGoalMutation } from '@/modules/admin/goals/composables/useCreateGoalMutation'
import { useUpdateGoalMutation } from '@/modules/admin/goals/composables/useUpdateGoalMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import UserSelectField from '@/modules/common/components/UserSelectField.vue'
import type { CreateGoalPayload, GoalStatus, UpdateGoalPayload } from '@/types/api/Goal'

interface GoalFormValues {
  user_id: string
  target_weight_kg: string
  target_body_fat_percentage: string
  start_date: string
  target_date: string
  status: GoalStatus
}

const route = useRoute()
const router = useRouter()

const goalId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => goalId.value !== null)
const goalQuery = useGoalQuery(goalId)

const createMutation = useCreateGoalMutation()
const updateMutation = useUpdateGoalMutation(goalId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    user_id: yup
      .string()
      .required()
      .test('not-zero', 'Selecciona un usuario', (v) => !!v && v !== '0'),
    target_weight_kg: yup
      .string()
      .nullable()
      .transform((v) => (v === '' ? null : v))
      .test(
        'range',
        'Debe estar entre 0 y 700',
        (v) => v === null || (Number(v) >= 0 && Number(v) <= 700),
      )
      .label('peso objetivo'),
    target_body_fat_percentage: yup
      .string()
      .nullable()
      .transform((v) => (v === '' ? null : v))
      .test(
        'range',
        'Debe estar entre 0 y 99.9',
        (v) => v === null || (Number(v) >= 0 && Number(v) <= 99.9),
      )
      .label('% grasa objetivo'),
    start_date: yup.string().required().label('fecha de inicio'),
    target_date: yup
      .string()
      .nullable()
      .transform((v) => (v === '' ? null : v))
      .label('fecha meta'),
    status: yup
      .string<GoalStatus>()
      .oneOf(['active', 'achieved', 'abandoned'])
      .required()
      .label('estado'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<GoalFormValues>({
  validationSchema: schema,
  initialValues: {
    user_id: '',
    target_weight_kg: '',
    target_body_fat_percentage: '',
    start_date: '',
    target_date: '',
    status: 'active',
  },
})

const { value: userId } = useField<string>('user_id')
const { value: targetWeight } = useField<string>('target_weight_kg')
const { value: targetFat } = useField<string>('target_body_fat_percentage')
const { value: startDate } = useField<string>('start_date')
const { value: targetDate } = useField<string>('target_date')
const { value: status } = useField<GoalStatus>('status')

watch(
  () => goalQuery.data.value,
  (goal) => {
    if (goal) {
      setValues({
        user_id: String(goal.user_id),
        target_weight_kg: goal.target_weight_kg !== null ? String(goal.target_weight_kg) : '',
        target_body_fat_percentage:
          goal.target_body_fat_percentage !== null ? String(goal.target_body_fat_percentage) : '',
        start_date: goal.start_date ?? '',
        target_date: goal.target_date ?? '',
        status: goal.status,
      })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) {
    resetForm({
      values: {
        user_id: '',
        target_weight_kg: '',
        target_body_fat_percentage: '',
        start_date: '',
        target_date: '',
        status: 'active',
      },
    })
  }
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar la meta.')
})

const isBusy = computed(
  () =>
    isSubmitting.value ||
    createMutation.isPending.value ||
    updateMutation.isPending.value ||
    (isEdit.value && goalQuery.isFetching.value),
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
    void router.push({ name: 'admin-goals' })
  }
  const basePayload = {
    target_weight_kg: values.target_weight_kg ? Number(values.target_weight_kg) : null,
    target_body_fat_percentage: values.target_body_fat_percentage
      ? Number(values.target_body_fat_percentage)
      : null,
    start_date: values.start_date,
    target_date: values.target_date || null,
    status: values.status,
  }
  if (isEdit.value) {
    const payload: UpdateGoalPayload = basePayload
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateGoalPayload = { user_id: Number(values.user_id), ...basePayload }
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-2xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar meta' : 'Nueva meta' }}
        </h1>
        <p class="text-sm text-stone-500">Define un objetivo de peso o composición corporal.</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-goals' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && goalQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(goalQuery.error.value, 'No pudimos cargar la meta.') }}
    </div>

    <form
      novalidate
      class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-6 space-y-4"
      @submit.prevent="onSubmit"
    >
      <FormField label="Usuario" required>
        <template #default="{ id, describedBy, invalid, disabled }">
          <UserSelectField
            v-model="userId"
            :input-id="id"
            :described-by="describedBy"
            :invalid="invalid"
            :disabled="disabled"
            empty-label="Selecciona un usuario"
          />
        </template>
      </FormField>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <FormField label="Peso objetivo (kg)" helper-text="Entre 0 y 700.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model="targetWeight"
              type="number"
              step="0.1"
              min="0"
              max="700"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="70"
            />
          </template>
        </FormField>
        <FormField label="% grasa objetivo" helper-text="Entre 0 y 99.9.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model="targetFat"
              type="number"
              step="0.1"
              min="0"
              max="99.9"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="18"
            />
          </template>
        </FormField>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <FormField label="Fecha de inicio" required>
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model="startDate"
              type="date"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
            />
          </template>
        </FormField>
        <FormField label="Fecha meta" helper-text="Opcional. Debe ser posterior al inicio.">
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model="targetDate"
              type="date"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
            />
          </template>
        </FormField>
      </div>

      <FormField label="Estado" required>
        <template #default="{ id, describedBy, invalid, disabled }">
          <select
            :id="id"
            v-model="status"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
          >
            <option value="active">Activo</option>
            <option value="achieved">Logrado</option>
            <option value="abandoned">Abandonado</option>
          </select>
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
          :to="{ name: 'admin-goals' }"
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
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear meta' }}
        </button>
      </div>
    </form>
  </section>
</template>
