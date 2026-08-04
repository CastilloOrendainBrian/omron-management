<script setup lang="ts">
import { computed, watch } from 'vue'
import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'
import { useForm, useField } from 'vee-validate'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useDeviceQuery } from '@/modules/admin/devices/composables/useDeviceQuery'
import { useCreateDeviceMutation } from '@/modules/admin/devices/composables/useCreateDeviceMutation'
import { useUpdateDeviceMutation } from '@/modules/admin/devices/composables/useUpdateDeviceMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import FormField from '@/modules/common/components/FormField.vue'
import UserSelectField from '@/modules/common/components/UserSelectField.vue'
import type { CreateDevicePayload, UpdateDevicePayload } from '@/types/api/Device'

interface DeviceFormValues {
  user_id: string
  brand: string
  model: string
  serial_number: string
}

const route = useRoute()
const router = useRouter()

const deviceId = computed<number | null>(() => {
  const raw = route.params.id
  if (raw === undefined) return null
  const parsed = Number(Array.isArray(raw) ? raw[0] : raw)
  return Number.isFinite(parsed) ? parsed : null
})

const isEdit = computed(() => deviceId.value !== null)
const deviceQuery = useDeviceQuery(deviceId)

const createMutation = useCreateDeviceMutation()
const updateMutation = useUpdateDeviceMutation(deviceId.value ?? 0)

const schema = toTypedSchema(
  yup.object({
    user_id: yup.string().label('usuario'),
    brand: yup.string().required().max(255).label('marca'),
    model: yup.string().required().max(255).label('modelo'),
    serial_number: yup.string().max(255).nullable().label('número de serie'),
  }),
)

const { handleSubmit, isSubmitting, setErrors, setValues, resetForm } = useForm<DeviceFormValues>({
  validationSchema: schema,
  initialValues: { user_id: '', brand: '', model: '', serial_number: '' },
})

const { value: userId } = useField<string>('user_id')
const { value: brand } = useField<string>('brand')
const { value: model } = useField<string>('model')
const { value: serialNumber } = useField<string>('serial_number')

watch(
  () => deviceQuery.data.value,
  (device) => {
    if (device) {
      setValues({
        user_id: device.user_id !== null ? String(device.user_id) : '',
        brand: device.brand,
        model: device.model,
        serial_number: device.serial_number ?? '',
      })
    }
  },
  { immediate: true },
)

watch(isEdit, (next) => {
  if (!next) resetForm({ values: { user_id: '', brand: '', model: '', serial_number: '' } })
})

const submitError = computed<string | null>(() => {
  const err = createMutation.error.value ?? updateMutation.error.value
  if (!err) return null
  return getApiErrorMessage(err, 'No pudimos guardar el dispositivo.')
})

const isBusy = computed(
  () =>
    isSubmitting.value ||
    createMutation.isPending.value ||
    updateMutation.isPending.value ||
    (isEdit.value && deviceQuery.isFetching.value),
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
    void router.push({ name: 'admin-devices' })
  }
  const basePayload = {
    user_id: values.user_id ? Number(values.user_id) : null,
    brand: values.brand,
    model: values.model,
    serial_number: values.serial_number || null,
  }
  if (isEdit.value) {
    const payload: UpdateDevicePayload = basePayload
    updateMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  } else {
    const payload: CreateDevicePayload = basePayload
    createMutation.mutate(payload, { onSuccess: goBack, onError: applyFieldErrors })
  }
})
</script>

<template>
  <section class="space-y-6 max-w-2xl">
    <header class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-stone-800">
          {{ isEdit ? 'Editar dispositivo' : 'Nuevo dispositivo' }}
        </h1>
        <p class="text-sm text-stone-500">Báscula, monitor u otro dispositivo de medición.</p>
      </div>
      <RouterLink
        :to="{ name: 'admin-devices' }"
        class="inline-flex items-center gap-1 text-sm font-medium text-stone-600 hover:text-stone-800"
      >
        <i class="ri-arrow-left-line" /> Volver
      </RouterLink>
    </header>

    <div
      v-if="isEdit && deviceQuery.isError.value"
      class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
      role="alert"
    >
      {{ getApiErrorMessage(deviceQuery.error.value, 'No pudimos cargar el dispositivo.') }}
    </div>

    <form
      novalidate
      class="bg-white rounded-md border border-stone-200 shadow-sm shadow-stone-200/60 p-6 space-y-4"
      @submit.prevent="onSubmit"
    >
      <FormField label="Usuario" helper-text="Opcional. Déjalo sin asignar si aún no tiene dueño.">
        <template #default="{ id, describedBy, invalid, disabled }">
          <UserSelectField
            v-model="userId"
            :input-id="id"
            :described-by="describedBy"
            :invalid="invalid"
            :disabled="disabled"
          />
        </template>
      </FormField>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <FormField label="Marca" required>
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model="brand"
              type="text"
              maxlength="255"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="Omron"
            />
          </template>
        </FormField>
        <FormField label="Modelo" required>
          <template #default="{ id, describedBy, invalid, disabled }">
            <input
              :id="id"
              v-model="model"
              type="text"
              maxlength="255"
              :aria-invalid="invalid ? 'true' : 'false'"
              :aria-describedby="describedBy"
              :disabled="disabled"
              class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed"
              placeholder="HBF-514"
            />
          </template>
        </FormField>
      </div>

      <FormField label="Número de serie" helper-text="Opcional.">
        <template #default="{ id, describedBy, invalid, disabled }">
          <input
            :id="id"
            v-model="serialNumber"
            type="text"
            maxlength="255"
            :aria-invalid="invalid ? 'true' : 'false'"
            :aria-describedby="describedBy"
            :disabled="disabled"
            class="w-full text-sm py-2 px-2.5 border border-stone-200 rounded-lg outline-none focus:border-stone-400 placeholder:text-stone-500/60 disabled:bg-stone-100 disabled:cursor-not-allowed font-mono"
            placeholder="SN-0000-0000"
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
          :to="{ name: 'admin-devices' }"
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
          {{ isBusy ? 'Guardando…' : isEdit ? 'Guardar cambios' : 'Crear dispositivo' }}
        </button>
      </div>
    </form>
  </section>
</template>
