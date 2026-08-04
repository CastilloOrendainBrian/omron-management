export interface AuthToken {
  access_token: string
  token_type: 'Bearer'
  token_id: number
  name: string
  abilities: string[]
  expires_at: string | null
}
