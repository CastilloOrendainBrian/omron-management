import { apiClient } from '@/api/apiClient'

export async function deleteGoalAction(id: number): Promise<void> {
  await apiClient.delete(`/goals/${id}`)
}
