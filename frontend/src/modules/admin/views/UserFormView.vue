<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useUserQuery } from '@/modules/admin/composables/useUserQuery'
import { useCreateUserMutation } from '@/modules/admin/composables/useCreateUserMutation'
import { useUpdateUserMutation } from '@/modules/admin/composables/useUpdateUserMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import type { CreateUserPayload, UpdateUserPayload } from '@/types/api/User'

interface UserFormValues {
  name: string
  email: string
  password: string
}

const route = useRoute()
const router = useRouter()

const userId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => userId.value !== null)
const userQuery = useUserQuery(userId)

const createMutation = useCreateUserMutation()
const updateMutation = useUpdateUserMutation(userId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    name: yup.string().required().max(255).label('nombre'),
    email: yup.string().required().email().max(255).label('correo electrónico'),
    password: yup
      .string()
      .transform((value) => (value === '' ? null : value))
      .nullable()
      .min(8, 'Debe tener al menos 8 caracteres')
      .label('contraseña'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<UserFormValues>({
  validationSchema: schema,
  initialValues: { name: '', email: '', password: '' },
})

const { value: name } = useField<string>('name')
const { value: email } = useField<string>('email')
const { value: password, errorMessage: passwordError } = useField<string>('password')

watch(
  () => userQuery.data.value,
  (user) => {
    if (user) {
      setValues({ name: user.name, email: user.email, password: '' })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) {
    resetForm({ values: { name: '', email: '', password: '' } })
  }
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar el usuario.')
})

const isBusy = computed(
  () =>
    isSubmitting.value ||
    createMutation.isPending.value ||
    updateMutation.isPending.value ||
    (isEdit.value && userQuery.isFetching.value),
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
  if (!isEdit.value && !values.password) {
    setErrors({ password: 'La contraseña es requerida.' })
    return
  }

  const goBack = (): void => {
    void router.push({ name: 'admin-users' })
  }

  if (isEdit.value) {
    const payload: UpdateUserPayload = {
      name: values.name,
      email: values.email,
    }
    if (values.password) payload.password = values.password
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateUserPayload = {
      name: values.name,
      email: values.email,
      password: values.password,
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
          {{ isEdit ? 'Editar usuario' : 'Nuevo usuario' }}
        </h1>
        <p class="text-sm text-stone-500">
          {{
            isEdit
              ? 'Modifica los datos del usuario. Deja la contraseña vacía para conservarla.'
              : 'Crea una cuenta nueva en el sistema.'
          }}
        </p>
      </div>
      <RouterLink
        :to="{ name: 'admin-users' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && userQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(userQuery.error.value, 'No pudimos cargar al usuario.') }}
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
            autocomplete="name"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="Nombre completo"
          />
        </template>
      </FormField>

      <FormField label="Correo electrónico" required>
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="email"
            type="email"
            autocomplete="email"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="usuario@ejemplo.com"
          />
        </template>
      </FormField>

      <FormField
        label="Contraseña"
        :helper-text="isEdit ? 'Déjala vacía para no cambiarla.' : 'Mínimo 8 caracteres.'"
        :error-message="passwordError"
        :required="!isEdit"
      >
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="password"
            type="password"
            autocomplete="new-password"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
            placeholder="••••••••"
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
          :to="{ name: 'admin-users' }"
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
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear usuario' }}
        </button>
      </div>
    </form>
  </section>
</template>
