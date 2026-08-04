export interface SkinfoldProtocol {
  id: number
  name: string
  sites_count: number
  description: string | null
}

export interface SkinfoldProtocolListMeta {
  page: number
  per_page: number
  total: number
  last_page: number
}

export interface SkinfoldProtocolListResponse {
  data: SkinfoldProtocol[]
  meta: SkinfoldProtocolListMeta
}

export interface ListSkinfoldProtocolsParams {
  page?: number
  per_page?: number
}
