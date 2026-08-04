import type { User } from './User'

export type GoalStatus = 'active' | 'achieved' | 'abandoned'

export interface Goal {
  id: number
  user_id: number
  target_weight_kg: number | null
  target_body_fat_percentage: number | null
  start_date: string | null
  target_date: string | null
  status: GoalStatus
  user?: User
  created_at: string | null
  updated_at: string | null
}

export interface GoalListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface GoalListResponse {
  data: Goal[]
  meta: GoalListMeta
}

export interface CreateGoalPayload {
  user_id: number
  start_date: string
  target_weight_kg?: number | null
  target_body_fat_percentage?: number | null
  target_date?: string | null
  status?: GoalStatus
}

export type UpdateGoalPayload = Partial<Omit<CreateGoalPayload, 'user_id'>>

export interface ListGoalsParams {
  page?: number
  per_page?: number
  user_id?: number
  status?: GoalStatus
}
