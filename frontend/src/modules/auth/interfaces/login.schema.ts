import { toTypedSchema } from '@vee-validate/yup'
import * as yup from 'yup'

export const loginSchema = toTypedSchema(
  yup.object({
    email: yup.string().required().email().max(255).trim().lowercase().label('correo electrónico'),
    password: yup.string().required().label('contraseña'),
  }),
)
