export interface SkinfoldSite {
  id: number
  code: string
  name: string
}

export interface SkinfoldSiteListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface SkinfoldSiteListResponse {
  data: SkinfoldSite[]
  meta: SkinfoldSiteListMeta
}

export interface CreateSkinfoldSitePayload {
  code: string
  name: string
}

export type UpdateSkinfoldSitePayload = Partial<CreateSkinfoldSitePayload>

export interface ListSkinfoldSitesParams {
  page?: number
  per_page?: number
  code?: string
  name?: string
}
