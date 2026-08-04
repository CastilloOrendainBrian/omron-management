<script setup lang="ts">
import { computed, ref } from 'vue'
import { useForm, useField } from 'vee-validate'
import { useLoginMutation } from '@/modules/auth/composables/useLoginMutation'
import { getApiErrorMessage } from '@/api/apiClient'
import { useAuthStore } from '@/modules/auth/stores/auth.store'
import { loginSchema } from '@/modules/auth/interfaces/login.schema'
import type { LoginCredentials } from '@/modules/auth/interfaces/login.interface'

const authStore = useAuthStore()
const loginMutation = useLoginMutation()
const rememberMe = ref(false)

const { handleSubmit, isSubmitting, setErrors } = useForm<LoginCredentials>({
  validationSchema: loginSchema,
  initialValues: {
    email: '',
    password: '',
    device_name: 'web',
  },
})

const { value: email, errorMessage: emailError } = useField<string>('email')
const { value: password, errorMessage: passwordError } = useField<string>('password')

const submitError = computed<string | null>(() => {
  if (!loginMutation.isError.value) return null
  return getApiErrorMessage(
    loginMutation.error.value,
    'No pudimos iniciar sesión. Verifica tus credenciales e inténtalo de nuevo.',
  )
})

const isBusy = computed(() => isSubmitting.value || loginMutation.isPending.value)

const onSubmit = handleSubmit((values) => {
  loginMutation.mutate(values, {
    onError: (error) => {
      if (error && typeof error === 'object' && 'response' in error) {
        const fieldErrors = (
          error as { response?: { data?: { errors?: Record<string, string[]> } } }
        ).response?.data?.errors
        if (fieldErrors) {
          setErrors(fieldErrors)
        }
      }
    },
  })
})
</script>

<template>
  <div class="bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Iniciar sesión</h2>

    <form novalidate class="space-y-4" @submit.prevent="onSubmit">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
          Correo electrónico
        </label>
        <input
          id="email"
          v-model="email"
          type="email"
          name="email"
          autocomplete="email"
          inputmode="email"
          required
          :aria-invalid="emailError ? 'true' : 'false'"
          aria-describedby="email-error"
          :disabled="isBusy"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all disabled:cursor-not-allowed disabled:bg-gray-100"
          placeholder="tunombre@ejemplo.com"
        />
        <p v-if="emailError" id="email-error" class="mt-1 text-sm text-rose-600" role="alert">
          {{ emailError }}
        </p>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
          Contraseña
        </label>
        <input
          id="password"
          v-model="password"
          type="password"
          name="password"
          autocomplete="current-password"
          required
          :aria-invalid="passwordError ? 'true' : 'false'"
          aria-describedby="password-error"
          :disabled="isBusy"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all disabled:cursor-not-allowed disabled:bg-gray-100"
          placeholder="••••••••"
        />
        <p v-if="passwordError" id="password-error" class="mt-1 text-sm text-rose-600" role="alert">
          {{ passwordError }}
        </p>
      </div>

      <div class="flex items-center justify-between">
        <label class="flex items-center">
          <input
            v-model="rememberMe"
            type="checkbox"
            :disabled="isBusy"
            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:cursor-not-allowed"
          />
          <span class="ml-2 text-sm text-gray-600">Recordarme</span>
        </label>
        <RouterLink
          :to="{ name: 'forgot-password' }"
          class="text-sm text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline"
        >
          ¿Olvidaste tu contraseña?
        </RouterLink>
      </div>

      <div
        v-if="submitError"
        class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700"
        role="alert"
        aria-live="assertive"
      >
        {{ submitError }}
      </div>

      <button
        type="submit"
        :disabled="isBusy"
        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors disabled:cursor-not-allowed disabled:opacity-60 inline-flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
      >
        <span
          v-if="isBusy"
          aria-hidden="true"
          class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"
        />
        {{ isBusy ? 'Ingresando…' : 'Ingresar' }}
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
      ¿Aún no tienes cuenta?
      <RouterLink
        :to="{ name: 'register' }"
        class="text-indigo-600 hover:text-indigo-500 font-medium focus:outline-none focus:underline"
      >
        Regístrate
      </RouterLink>
    </div>

    <p
      v-if="authStore.isAuthenticated"
      class="mt-4 text-center text-xs text-gray-400"
      aria-live="polite"
    >
      Sesión activa.
    </p>
  </div>
</template>
