import { apiClient } from '@/api/apiClient'
import type { GoalListResponse, ListGoalsParams } from '@/types/api/Goal'

export async function listGoalsAction(params: ListGoalsParams = {}): Promise<GoalListResponse> {
  const { data } = await apiClient.get<{
    success: boolean
    data: GoalListResponse['data']
    meta: GoalListResponse['meta']
  }>('/goals', { params })
  return { data: data.data, meta: data.meta }
}
