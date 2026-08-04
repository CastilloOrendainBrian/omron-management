import { apiClient } from '@/api/apiClient'

export async function deleteSkinfoldProtocolAction(id: number): Promise<void> {
  await apiClient.delete(`/skinfold-protocols/${id}`)
}
