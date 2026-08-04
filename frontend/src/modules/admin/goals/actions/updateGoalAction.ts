import { apiClient } from '@/api/apiClient'
import type { Goal, UpdateGoalPayload } from '@/types/api/Goal'

export async function updateGoalAction(id: number, payload: UpdateGoalPayload): Promise<Goal> {
  const { data } = await apiClient.put<{ success: boolean; data: Goal }>(`/goals/${id}`, payload)
  return data.data
}
