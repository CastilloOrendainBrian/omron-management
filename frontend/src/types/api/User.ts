export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  roles?: string[]
  permissions?: string[]
  profile?: unknown
  created_at: string | null
  updated_at: string | null
}

export interface UserListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface UserListResponse {
  data: User[]
  meta: UserListMeta
}

export interface CreateUserPayload {
  name: string
  email: string
  password: string
}

export type UpdateUserPayload = Partial<CreateUserPayload>

export interface ListUsersParams {
  page?: number
  per_page?: number
  name?: string
  email?: string
}
