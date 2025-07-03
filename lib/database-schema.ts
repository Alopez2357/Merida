export interface DatabaseSchema {
  usuarios: Usuario[]
  ocupacion_hotelera: OcupacionHotelera[]
  pernoctas: Pernoctas[]
  arribos_aeropuerto: ArribosAeropuerto[]
  movimiento_operacional: MovimientoOperacional[]
  inpc_merida: INPCMerida[]
  empleo_merida: EmpleoMerida[]
  airbnb_listings: AirbnbListing[]
  origen_destino_detalle: OrigenDestinoDetalle[]
}

export interface Usuario {
  id: number
  email: string
  password: string
  nombre: string
  activo: boolean
  created_at: string
}

export interface OcupacionHotelera {
  id: number
  año: number
  mes: string
  porcentaje_ocupacion: number
  created_at: string
}

export interface Pernoctas {
  id: number
  año: number
  mes: string
  nacionales: number
  extranjeros: number
  total: number
  created_at: string
}

export interface ArribosAeropuerto {
  id: number
  año: number
  mes: string
  tipo_pasajero: "Domestic" | "International" | "Total"
  cantidad: number
  created_at: string
}

export interface MovimientoOperacional {
  id: number
  año: number
  mes: string
  tipo_movimiento: "Llegada" | "Salida"
  origen_destino: string
  porcentaje: number
  created_at: string
}

export interface INPCMerida {
  id: number
  fecha: string
  valor: number
  created_at: string
}

export interface EmpleoMerida {
  id: number
  categoria: string
  subcategoria: string
  total: number
  hombres: number
  mujeres: number
  created_at: string
}

export interface AirbnbListing {
  id: number
  property_id: string
  property_type: string
  listing_type: string
  bedrooms: number
  reporting_month: string
  occupancy_rate: number
  active_listing_nights: number
  revenue_usd: number
  adr_usd: number
  number_reservations: number
  country: string
  state: string
  city: string
  neighborhood: string
  latitude: number
  longitude: number
  created_at: string
}

export interface OrigenDestinoDetalle {
  id: number
  año: number
  mes: string
  tipo_movimiento: "Llegada" | "Salida"
  pais: string
  aeropuerto: string
  codigo_aeropuerto: string
  pasajeros: number
  porcentaje_pasajeros: number
  numero_operaciones: number
  created_at: string
}
