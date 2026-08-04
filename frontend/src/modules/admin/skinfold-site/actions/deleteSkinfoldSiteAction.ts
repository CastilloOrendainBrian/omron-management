import { apiClient } from '@/api/apiClient'

export async function deleteSkinfoldSiteAction(id: number): Promise<void> {
  await apiClient.delete(`/skinfold-sites/${id}`)
}
