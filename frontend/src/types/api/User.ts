export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  roles: string[]
  permissions: string[]
  created_at: string | null
  updated_at: string | null
}
