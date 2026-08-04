import type { User } from './User'

export type Sex = 'male' | 'female'
export type ActivityLevel = 'sedentary' | 'light' | 'moderate' | 'active' | 'very_active'

export interface UserProfile {
  id: number
  user_id: number
  sex: Sex
  birth_date: string | null
  height_reference_cm: number | null
  activity_level: ActivityLevel | null
  user?: User
  created_at: string | null
  updated_at: string | null
}

export interface UserProfileListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface UserProfileListResponse {
  data: UserProfile[]
  meta: UserProfileListMeta
}

export interface CreateUserProfilePayload {
  user_id: number
  sex: Sex
  birth_date: string
  height_reference_cm?: number | null
  activity_level?: ActivityLevel | null
}

export type UpdateUserProfilePayload = Partial<Omit<CreateUserProfilePayload, 'user_id'>>

export interface ListUserProfilesParams {
  page?: number
  per_page?: number
  sex?: Sex
  activity_level?: ActivityLevel
  height_min?: number
  height_max?: number
}
