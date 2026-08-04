import { apiClient } from '@/api/apiClient'
import type { Goal } from '@/types/api/Goal'

export async function getGoalAction(id: number): Promise<Goal> {
  const { data } = await apiClient.get<{ success: boolean; data: Goal }>(`/goals/${id}`)
  return data.data
}
