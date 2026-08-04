import { apiClient } from '@/api/apiClient'
import type { CreateGoalPayload, Goal } from '@/types/api/Goal'

export async function createGoalAction(payload: CreateGoalPayload): Promise<Goal> {
  const { data } = await apiClient.post<{ success: boolean; data: Goal }>('/goals', payload)
  return data.data
}
