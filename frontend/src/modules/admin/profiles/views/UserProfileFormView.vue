<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useUserProfileQuery } from '@/modules/admin/profiles/composables/useUserProfileQuery'
import { useCreateUserProfileMutation } from '@/modules/admin/profiles/composables/useCreateUserProfileMutation'
import { useUpdateUserProfileMutation } from '@/modules/admin/profiles/composables/useUpdateUserProfileMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import UserSelectField from '@/modules/common/components/UserSelectField.vue'
import type {
  ActivityLevel,
  CreateUserProfilePayload,
  Sex,
  UpdateUserProfilePayload,
} from '@/types/api/UserProfile'

interface UserProfileFormValues {
  user_id: string
  sex: Sex | ''
  birth_date: string
  height_reference_cm: string
  activity_level: ActivityLevel | ''
}

const route = useRoute()
const router = useRouter()

const profileId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => profileId.value !== null)
const profileQuery = useUserProfileQuery(profileId)

const createMutation = useCreateUserProfileMutation()
const updateMutation = useUpdateUserProfileMutation(profileId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    user_id: yup
      .string()
      .required()
      .test('not-zero', 'Selecciona un usuario', (v) => !!v && v !== '0'),
    sex: yup.string<Sex>().oneOf(['male', 'female']).required().label('sexo'),
    birth_date: yup.string().required().label('fecha de nacimiento'),
    height_reference_cm: yup
      .string()
      .nullable()
      .transform((v) => (v === '' ? null : v))
      .test(
        'range',
        'Debe estar entre 0 y 300',
        (v) => v === null || (Number(v) >= 0 && Number(v) <= 300),
      )
      .label('estatura'),
    activity_level: yup
      .string<ActivityLevel>()
      .oneOf(['sedentary', 'light', 'moderate', 'active', 'very_active'])
      .label('actividad'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } =
  useForm<UserProfileFormValues>({
    validationSchema: schema,
    initialValues: {
      user_id: '',
      sex: '',
      birth_date: '',
      height_reference_cm: '',
      activity_level: '',
    },
  })

const { value: userId } = useField<string>('user_id')
const { value: sex } = useField<Sex | ''>('sex')
const { value: birthDate } = useField<string>('birth_date')
const { value: heightCm } = useField<string>('height_reference_cm')
const { value: activityLevel } = useField<ActivityLevel | ''>('activity_level')

watch(
  () => profileQuery.data.value,
  (profile) => {
    if (profile) {
      setValues({
        user_id: String(profile.user_id),
        sex: profile.sex,
        birth_date: profile.birth_date ?? '',
        height_reference_cm:
          profile.height_reference_cm !== null ? String(profile.height_reference_cm) : '',
        activity_level: profile.activity_level ?? '',
      })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next)
    resetForm({
      values: { user_id: '', sex: '', birth_date: '', height_reference_cm: '', activity_level: '' },
    })
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar el perfil.')
})

const isBusy = computed(
  () =>
    isSubmitting.value ||
    createMutation.isPending.value ||
    updateMutation.isPending.value ||
    (isEdit.value && profileQuery.isFetching.value),
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
    void router.push({ name: 'admin-profiles' })
  }
  const basePayload = {
    sex: values.sex as Sex,
    birth_date: values.birth_date,
    height_reference_cm: values.height_reference_cm ? Number(values.height_reference_cm) : null,
    activity_level: (values.activity_level || null) as ActivityLevel | null,
  }
  if (isEdit.value) {
    const payload: UpdateUserProfilePayload = basePayload
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateUserProfilePayload = { user_id: Number(values.user_id), ...basePayload }
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-2xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar perfil' : 'Nuevo perfil' }}
        </h1>
        <p class="text-sm text-stone-500">Datos demográficos del usuario.</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-profiles' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && profileQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(profileQuery.error.value, 'No pudimos cargar el perfil.') }}
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

      <FormField label="Sexo" required>
        <template #default="{ id, describedBy, invalid, disabled }">
          <select
            :id="id"
            v-model="sex"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
          >
            <option value="">Selecciona</option>
            <option value="male">Masculino</option>
            <option value="female">Femenino</option>
          </select>
        </template>
      </FormField>

      <FormField label="Fecha de nacimiento" required>
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="birthDate"
            type="date"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
          />
        </template>
      </FormField>

      <FormField
        label="Estatura de referencia (cm)"
        helper-text="Entre 0 y 300 cm. Déjala vacía si no aplica."
      >
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="heightCm"
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

      <FormField label="Nivel de actividad" helper-text="Opcional.">
        <template #default="{ id, describedBy, invalid, disabled }">
          <select
            :id="id"
            v-model="activityLevel"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 bg-white disabled:bg-stone-100 disabled:cursor-not-allowed"
          >
            <option value="">Sin especificar</option>
            <option value="sedentary">Sedentario</option>
            <option value="light">Ligero</option>
            <option value="moderate">Moderado</option>
            <option value="active">Activo</option>
            <option value="very_active">Muy activo</option>
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
          :to="{ name: 'admin-profiles' }"
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
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear perfil' }}
        </button>
      </div>
    </form>
  </section>
</template>
