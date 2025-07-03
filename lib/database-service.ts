import type {
  DatabaseSchema,
  AirbnbListing,
  OcupacionHotelera,
  EmpleoMerida,
  ArribosAeropuerto,
  Pernoctas,
  INPCMerida,
} from "./database-schema"

export class DatabaseService {
  private static STORAGE_KEY = "merida_database"
  private static MAX_RECORDS_PER_TABLE = 1000 // Limitar registros por tabla
  private static STORAGE_QUOTA_MB = 5 // Límite de 5MB para localStorage
  private static memoryStorage: Partial<DatabaseSchema> = {}

  // Verificar espacio disponible en localStorage
  private static checkStorageQuota(): boolean {
    try {
      const testKey = "storage_test"
      const testData = "x".repeat(1024 * 1024) // 1MB de datos de prueba
      localStorage.setItem(testKey, testData)
      localStorage.removeItem(testKey)
      return true
    } catch (e) {
      return false
    }
  }

  // Obtener tamaño actual del almacenamiento
  private static getStorageSize(): number {
    let total = 0
    for (const key in localStorage) {
      if (localStorage.hasOwnProperty(key)) {
        total += localStorage[key].length + key.length
      }
    }
    return total
  }

  // Comprimir datos (simple JSON minification)
  private static compressData(data: any): string {
    return JSON.stringify(data, null, 0) // Sin espacios ni indentación
  }

  // Limpiar datos antiguos si es necesario
  private static cleanupOldData(): void {
    const db = this.getDatabase()
    let hasChanges = false

    // Limitar registros por tabla
    Object.keys(db).forEach((tableName) => {
      const table = db[tableName as keyof DatabaseSchema] as any[]
      if (table && table.length > this.MAX_RECORDS_PER_TABLE) {
        // Mantener solo los registros más recientes
        const sortedTable = table.sort(
          (a, b) => new Date(b.created_at || 0).getTime() - new Date(a.created_at || 0).getTime(),
        )
        db[tableName as keyof DatabaseSchema] = sortedTable.slice(0, this.MAX_RECORDS_PER_TABLE) as any
        hasChanges = true
      }
    })

    if (hasChanges) {
      try {
        localStorage.setItem(this.STORAGE_KEY, this.compressData(db))
      } catch (error) {
        console.warn("Error al limpiar datos antiguos:", error)
      }
    }
  }

  // Inicializar base de datos
  static initializeDatabase(): void {
    if (typeof window === "undefined") return

    const existingData = localStorage.getItem(this.STORAGE_KEY)
    if (!existingData) {
      const initialSchema: Partial<DatabaseSchema> = {
        usuarios: [],
        ocupacion_hotelera: [],
        pernoctas: [],
        arribos_aeropuerto: [],
        movimiento_operacional: [],
        inpc_merida: [],
        empleo_merida: [],
        airbnb_listings: [],
        origen_destino_detalle: [],
      }
      try {
        localStorage.setItem(this.STORAGE_KEY, this.compressData(initialSchema))
      } catch (error) {
        console.error("Error inicializando base de datos:", error)
      }
    }
  }

  // Obtener toda la base de datos
  static getDatabase(): Partial<DatabaseSchema> {
    if (typeof window === "undefined") return {}
    this.initializeDatabase()

    try {
      const data = localStorage.getItem(this.STORAGE_KEY)
      return data ? JSON.parse(data) : {}
    } catch (error) {
      console.error("Error leyendo base de datos:", error)
      return {}
    }
  }

  // Guardar tabla específica con manejo de errores
  static saveTable<T>(tableName: keyof DatabaseSchema, data: T[]): void {
    if (typeof window === "undefined") return

    try {
      const db = this.getDatabase()

      // Limitar datos si es necesario
      const limitedData = data.slice(0, this.MAX_RECORDS_PER_TABLE)
      db[tableName] = limitedData as any

      const compressedData = this.compressData(db)

      // Verificar tamaño antes de guardar
      if (compressedData.length > this.STORAGE_QUOTA_MB * 1024 * 1024) {
        console.warn(`Datos demasiado grandes para ${tableName}, limpiando...`)
        this.cleanupOldData()

        // Intentar de nuevo con datos limpios
        const cleanDb = this.getDatabase()
        cleanDb[tableName] = limitedData.slice(0, Math.floor(this.MAX_RECORDS_PER_TABLE / 2)) as any
        localStorage.setItem(this.STORAGE_KEY, this.compressData(cleanDb))
      } else {
        localStorage.setItem(this.STORAGE_KEY, compressedData)
      }
    } catch (error) {
      if (error instanceof Error && error.name === "QuotaExceededError") {
        console.warn("Cuota de almacenamiento excedida, limpiando datos...")
        this.handleQuotaExceeded(tableName, data)
      } else {
        console.error("Error guardando tabla:", error)
        throw error
      }
    }
  }

  // Manejar cuota excedida
  private static handleQuotaExceeded<T>(tableName: keyof DatabaseSchema, data: T[]): void {
    try {
      // Limpiar todas las tablas primero
      this.clearDatabase()
      this.initializeDatabase()

      // Guardar solo una muestra de los datos nuevos
      const sampleSize = Math.min(500, data.length)
      const sampleData = data.slice(0, sampleSize)

      const db = this.getDatabase()
      db[tableName] = sampleData as any
      localStorage.setItem(this.STORAGE_KEY, this.compressData(db))

      // Notificar al usuario
      window.dispatchEvent(
        new CustomEvent("storageWarning", {
          detail: {
            message: `Almacenamiento lleno. Solo se guardaron ${sampleSize} registros de ${data.length}.`,
            type: "warning",
          },
        }),
      )
    } catch (error) {
      console.error("Error manejando cuota excedida:", error)
      // Como último recurso, usar datos en memoria
      this.useMemoryStorage(tableName, data)
    }
  }

  // Almacenamiento en memoria como respaldo
  private static useMemoryStorage<T>(tableName: keyof DatabaseSchema, data: T[]): void {
    console.warn("Usando almacenamiento en memoria para", tableName)
    this.memoryStorage[tableName] = data.slice(0, 100) as any // Solo 100 registros en memoria

    window.dispatchEvent(
      new CustomEvent("storageWarning", {
        detail: {
          message: "Usando almacenamiento temporal. Los datos se perderán al recargar la página.",
          type: "error",
        },
      }),
    )
  }

  // Obtener tabla específica
  static getTable<T>(tableName: keyof DatabaseSchema): T[] {
    // Primero intentar localStorage
    const db = this.getDatabase()
    const localData = (db[tableName] as T[]) || []

    // Si no hay datos en localStorage, verificar memoria
    if (localData.length === 0 && this.memoryStorage[tableName]) {
      return (this.memoryStorage[tableName] as T[]) || []
    }

    return localData
  }

  // Insertar datos en tabla con optimización
  static insertIntoTable<T extends { id?: number; created_at?: string }>(
    tableName: keyof DatabaseSchema,
    records: Omit<T, "id" | "created_at">[],
  ): void {
    const existingData = this.getTable<T>(tableName)
    const maxId = existingData.length > 0 ? Math.max(...existingData.map((r) => r.id || 0)) : 0

    // Limitar registros nuevos si son demasiados
    const maxNewRecords = Math.min(records.length, this.MAX_RECORDS_PER_TABLE - existingData.length)
    const limitedRecords = records.slice(0, maxNewRecords)

    const newRecords = limitedRecords.map((record, index) => ({
      ...record,
      id: maxId + index + 1,
      created_at: new Date().toISOString(),
    })) as T[]

    const updatedData = [...existingData, ...newRecords]
    this.saveTable(tableName, updatedData)

    if (records.length > maxNewRecords) {
      window.dispatchEvent(
        new CustomEvent("storageWarning", {
          detail: {
            message: `Solo se guardaron ${maxNewRecords} de ${records.length} registros debido a limitaciones de espacio.`,
            type: "warning",
          },
        }),
      )
    }
  }

  // Procesar datos de Airbnb con optimización
  static processAirbnbData(rawData: any[]): void {
    // Limitar datos de entrada
    const limitedData = rawData.slice(0, this.MAX_RECORDS_PER_TABLE)

    const processedData: Omit<AirbnbListing, "id" | "created_at">[] = limitedData.map((row) => ({
      property_id: String(row["Property ID"] || row["property_id"] || ""),
      property_type: String(row["Property Type"] || row["property_type"] || ""),
      listing_type: String(row["Listing Type"] || row["listing_type"] || ""),
      bedrooms: Number(row["Bedrooms"] || row["bedrooms"] || 0),
      reporting_month: String(row["Reporting Month"] || row["reporting_month"] || ""),
      occupancy_rate: Number(row["Occupancy Rate"] || row["occupancy_rate"] || 0),
      active_listing_nights: Number(row["Active Listing Nights"] || row["active_listing_nights"] || 0),
      revenue_usd: Number(row["Revenue (USD)"] || row["revenue_usd"] || 0),
      adr_usd: Number(row["ADR (USD)"] || row["adr_usd"] || 0),
      number_reservations: Number(row["Number of Reservations"] || row["number_reservations"] || 0),
      country: String(row["Country"] || row["country"] || ""),
      state: String(row["State"] || row["state"] || ""),
      city: String(row["City"] || row["city"] || ""),
      neighborhood: String(row["Neighborhood"] || row["neighborhood"] || ""),
      latitude: Number(row["Latitude"] || row["latitude"] || 0),
      longitude: Number(row["Longitude"] || row["longitude"] || 0),
    }))

    // Limpiar tabla existente y insertar nuevos datos
    this.saveTable("airbnb_listings", [])
    this.insertIntoTable("airbnb_listings", processedData)
  }

  // Procesar datos de ocupación hotelera
  static processHotelOccupancyData(rawData: any[]): void {
    const limitedData = rawData.slice(0, this.MAX_RECORDS_PER_TABLE)

    const processedData: Omit<OcupacionHotelera, "id" | "created_at">[] = limitedData.map((row) => ({
      año: Number(row["Año"] || row["año"] || row["Year"] || new Date().getFullYear()),
      mes: String(row["Mes"] || row["mes"] || row["Month"] || ""),
      porcentaje_ocupacion: Number(
        row["Ocupación"] || row["ocupacion"] || row["Occupancy"] || row["Porcentaje_Ocupacion"] || 0,
      ),
    }))

    this.saveTable("ocupacion_hotelera", [])
    this.insertIntoTable("ocupacion_hotelera", processedData)
  }

  // Procesar datos de empleo
  static processEmploymentData(rawData: any[]): void {
    const limitedData = rawData.slice(0, this.MAX_RECORDS_PER_TABLE)

    const processedData: Omit<EmpleoMerida, "id" | "created_at">[] = limitedData.map((row) => ({
      categoria: String(row["Categoría"] || row["categoria"] || row["Category"] || ""),
      subcategoria: String(row["Subcategoría"] || row["subcategoria"] || row["Subcategory"] || ""),
      total: Number(row["Total"] || row["total"] || 0),
      hombres: Number(row["Hombres"] || row["hombres"] || row["Men"] || 0),
      mujeres: Number(row["Mujeres"] || row["mujeres"] || row["Women"] || 0),
    }))

    this.saveTable("empleo_merida", [])
    this.insertIntoTable("empleo_merida", processedData)
  }

  // Obtener estadísticas de tabla
  static getTableStats(tableName: keyof DatabaseSchema): { count: number; lastUpdate: string | null } {
    const data = this.getTable(tableName)
    const lastUpdate =
      data.length > 0
        ? data.reduce((latest: any, current: any) =>
            new Date(current.created_at || 0) > new Date(latest.created_at || 0) ? current : latest,
          ).created_at
        : null

    return {
      count: data.length,
      lastUpdate,
    }
  }

  // Consultas específicas para el dashboard
  static getAirbnbByPropertyType(): { [key: string]: number } {
    const listings = this.getTable<AirbnbListing>("airbnb_listings")
    return listings.reduce(
      (acc, listing) => {
        const type = listing.property_type || "Sin especificar"
        acc[type] = (acc[type] || 0) + 1
        return acc
      },
      {} as { [key: string]: number },
    )
  }

  static getHotelOccupancyTrend(): { mes: string; ocupacion: number }[] {
    const data = this.getTable<OcupacionHotelera>("ocupacion_hotelera")
    return data.map((item) => ({
      mes: item.mes,
      ocupacion: item.porcentaje_ocupacion,
    }))
  }

  static getEmploymentByGender(): { categoria: string; hombres: number; mujeres: number }[] {
    const data = this.getTable<EmpleoMerida>("empleo_merida")
    return data.map((item) => ({
      categoria: item.categoria,
      hombres: item.hombres,
      mujeres: item.mujeres,
    }))
  }

  // Exportar datos a CSV
  static exportTableToCSV(tableName: keyof DatabaseSchema): string {
    const data = this.getTable(tableName)
    if (data.length === 0) return ""

    const headers = Object.keys(data[0])
    const csvContent = [
      headers.join(","),
      ...data.map((row) =>
        headers
          .map((header) => {
            const value = (row as any)[header]
            return typeof value === "string" && value.includes(",") ? `"${value}"` : value
          })
          .join(","),
      ),
    ].join("\n")

    return csvContent
  }

  // Limpiar base de datos
  static clearDatabase(): void {
    if (typeof window === "undefined") return
    localStorage.removeItem(this.STORAGE_KEY)
    this.memoryStorage = {}
    this.initializeDatabase()
  }

  // Método para procesar datos según el tipo y guardarlos en la tabla correcta
  static processAndSaveData(dataType: string, rawData: any[], fileName: string): void {
    try {
      switch (dataType) {
        case "empleo":
          this.processEmploymentData(rawData)
          break
        case "hoteles":
          this.processHotelOccupancyData(rawData)
          break
        case "aeropuerto":
          this.processAirportData(rawData)
          break
        case "airbnb":
          this.processAirbnbData(rawData)
          break
        case "inflacion":
          this.processInflationData(rawData)
          break
        case "turistas":
          this.processTouristData(rawData)
          break
        default:
          console.warn(`Tipo de datos no reconocido: ${dataType}`)
      }

      // Notificar a todas las vistas que los datos han cambiado
      this.notifyAllViews(dataType)
    } catch (error) {
      console.error(`Error procesando datos de tipo ${dataType}:`, error)
      throw error
    }
  }

  // Procesar datos de aeropuerto
  static processAirportData(rawData: any[]): void {
    const limitedData = rawData.slice(0, this.MAX_RECORDS_PER_TABLE)

    const processedData: Omit<ArribosAeropuerto, "id" | "created_at">[] = limitedData.map((row) => ({
      año: Number(row["Año"] || row["año"] || row["Year"] || new Date().getFullYear()),
      mes: String(row["Mes"] || row["mes"] || row["Month"] || ""),
      tipo_pasajero: String(row["Tipo_Pasajero"] || row["tipo_pasajero"] || row["Type"] || "Domestic"),
      cantidad: Number(row["Cantidad"] || row["cantidad"] || row["Passengers"] || 0),
    }))

    this.saveTable("arribos_aeropuerto", [])
    this.insertIntoTable("arribos_aeropuerto", processedData)
  }

  // Procesar datos de turistas
  static processTouristData(rawData: any[]): void {
    const limitedData = rawData.slice(0, this.MAX_RECORDS_PER_TABLE)

    const processedData: Omit<Pernoctas, "id" | "created_at">[] = limitedData.map((row) => ({
      año: Number(row["Año"] || row["año"] || row["Year"] || new Date().getFullYear()),
      mes: String(row["Mes"] || row["mes"] || row["Month"] || ""),
      nacionales: Number(row["Nacionales"] || row["nacionales"] || row["National"] || row["Turistas_Nacionales"] || 0),
      extranjeros: Number(
        row["Extranjeros"] || row["extranjeros"] || row["International"] || row["Turistas_Extranjeros"] || 0,
      ),
      total: Number(row["Total"] || row["total"] || 0),
    }))

    this.saveTable("pernoctas", [])
    this.insertIntoTable("pernoctas", processedData)
  }

  // Procesar datos de inflación
  static processInflationData(rawData: any[]): void {
    const limitedData = rawData.slice(0, this.MAX_RECORDS_PER_TABLE)

    const processedData: Omit<INPCMerida, "id" | "created_at">[] = limitedData.map((row) => ({
      fecha: String(row["Fecha"] || row["fecha"] || row["Date"] || new Date().toISOString().split("T")[0]),
      valor: Number(row["INPC"] || row["valor"] || row["Value"] || row["Variacion_Anual"] || 0),
    }))

    this.saveTable("inpc_merida", [])
    this.insertIntoTable("inpc_merida", processedData)
  }

  // Notificar a todas las vistas
  static notifyAllViews(dataType: string): void {
    // Disparar evento global
    window.dispatchEvent(
      new CustomEvent("databaseUpdated", {
        detail: {
          type: dataType,
          timestamp: new Date().toISOString(),
        },
      }),
    )

    // También disparar el evento específico para compatibilidad
    window.dispatchEvent(new CustomEvent("dataUpdated", { detail: { type: dataType } }))
  }

  // Obtener datos para gráficas desde la base de datos
  static getChartData(chartType: string): any[] {
    switch (chartType) {
      case "employment":
        return this.getEmploymentChartData()
      case "hotel":
        return this.getHotelChartData()
      case "airport":
        return this.getAirportChartData()
      case "airbnb":
        return this.getAirbnbChartData()
      case "inflation":
        return this.getInflationChartData()
      case "tourist":
        return this.getTouristChartData()
      default:
        return []
    }
  }

  // Métodos específicos para obtener datos de gráficas
  static getEmploymentChartData(): any[] {
    const data = this.getTable<EmpleoMerida>("empleo_merida")
    if (data.length === 0) {
      return [
        { sector: "Servicios", empleados: 342000, porcentaje: 68.2 },
        { sector: "Comercio", empleados: 98000, porcentaje: 18.5 },
        { sector: "Industria", empleados: 67000, porcentaje: 13.3 },
      ]
    }

    return data.map((item) => ({
      sector: item.categoria,
      empleados: item.total,
      porcentaje: (item.total / data.reduce((sum, d) => sum + d.total, 0)) * 100,
    }))
  }

  static getHotelChartData(): any[] {
    const data = this.getTable<OcupacionHotelera>("ocupacion_hotelera")
    if (data.length === 0) {
      return [
        { mes: "Ene", ocupacion2024: 65, ocupacion2025: 70 },
        { mes: "Feb", ocupacion2024: 68, ocupacion2025: 73 },
        { mes: "Mar", ocupacion2024: 72, ocupacion2025: 78.5 },
      ]
    }

    const groupedData = data.reduce((acc: any, item) => {
      const mes = item.mes
      if (!acc[mes]) acc[mes] = {}
      acc[mes][item.año] = item.porcentaje_ocupacion
      return acc
    }, {})

    return Object.keys(groupedData).map((mes) => ({
      mes,
      ocupacion2024: groupedData[mes][2024] || null,
      ocupacion2025: groupedData[mes][2025] || null,
    }))
  }

  static getAirportChartData(): any[] {
    const data = this.getTable<ArribosAeropuerto>("arribos_aeropuerto")
    if (data.length === 0) {
      return [
        { mes: "Ene", nacionales: 180000, internacionales: 45000 },
        { mes: "Feb", nacionales: 195000, internacionales: 48000 },
        { mes: "Mar", nacionales: 191730, internacionales: 53950 },
      ]
    }

    const groupedData = data.reduce((acc: any, item) => {
      const mes = item.mes
      if (!acc[mes]) acc[mes] = {}
      acc[mes][item.tipo_pasajero] = item.cantidad
      return acc
    }, {})

    return Object.keys(groupedData).map((mes) => ({
      mes,
      nacionales: groupedData[mes]["Domestic"] || 0,
      internacionales: groupedData[mes]["International"] || 0,
    }))
  }

  static getAirbnbChartData(): any[] {
    const data = this.getTable<AirbnbListing>("airbnb_listings")
    if (data.length === 0) {
      return [
        { tipo: "Entire home/apt", cantidad: 45 },
        { tipo: "Private room", cantidad: 32 },
        { tipo: "Shared room", cantidad: 18 },
      ]
    }

    const groupedByType = data.reduce((acc: any, item) => {
      const tipo = item.property_type || "Sin especificar"
      acc[tipo] = (acc[tipo] || 0) + 1
      return acc
    }, {})

    return Object.keys(groupedByType).map((tipo) => ({
      tipo,
      cantidad: groupedByType[tipo],
    }))
  }

  static getInflationChartData(): any[] {
    const data = this.getTable<INPCMerida>("inpc_merida")
    if (data.length === 0) {
      return [
        { mes: "Ene", inpc: 5.2, fecha: "2025-01-01" },
        { mes: "Feb", inpc: 5.1, fecha: "2025-02-01" },
        { mes: "Mar", inpc: 4.8, fecha: "2025-03-01" },
      ]
    }

    return data.map((item) => {
      const fecha = new Date(item.fecha)
      return {
        mes: fecha.toLocaleDateString("es-ES", { month: "short" }),
        inpc: item.valor,
        fecha: item.fecha,
      }
    })
  }

  static getTouristChartData(): any[] {
    const data = this.getTable<Pernoctas>("pernoctas")
    if (data.length === 0) {
      return [
        { mes: "Ene", nacionales: 45000, extranjeros: 25000 },
        { mes: "Feb", nacionales: 48000, extranjeros: 27000 },
        { mes: "Mar", nacionales: 52000, extranjeros: 30000 },
      ]
    }

    return data.map((item) => ({
      mes: item.mes,
      nacionales: item.nacionales,
      extranjeros: item.extranjeros,
      total: item.total,
    }))
  }

  // Obtener información de almacenamiento
  static getStorageInfo(): { used: string; available: string; percentage: number } {
    const used = this.getStorageSize()
    const maxSize = this.STORAGE_QUOTA_MB * 1024 * 1024
    const percentage = Math.round((used / maxSize) * 100)

    return {
      used: `${(used / 1024 / 1024).toFixed(2)} MB`,
      available: `${this.STORAGE_QUOTA_MB} MB`,
      percentage: Math.min(percentage, 100),
    }
  }
}
