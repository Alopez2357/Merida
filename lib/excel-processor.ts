export interface ExcelProcessorResult {
  headers: string[]
  data: any[]
  totalRows: number
  processedRows: number
  sheets?: string[]
}

export class ExcelProcessor {
  static async processFile(file: File, dataType: string): Promise<ExcelProcessorResult> {
    try {
      // Simular la lectura del archivo Excel
      const arrayBuffer = await file.arrayBuffer()

      // En un entorno real, usarías una librería como 'xlsx' o 'exceljs'
      // const workbook = XLSX.read(arrayBuffer, { type: 'array' })

      // Por ahora, simularemos el procesamiento basado en el tipo de datos
      return this.generateDataByType(dataType, file.name)
    } catch (error) {
      console.error("Error procesando archivo Excel:", error)
      throw new Error("Error al procesar el archivo Excel. Verifica que el archivo no esté corrupto.")
    }
  }

  private static generateDataByType(dataType: string, fileName: string): ExcelProcessorResult {
    switch (dataType) {
      case "airbnb":
        return this.generateAirbnbData(fileName)
      case "hoteles":
        return this.generateHotelData(fileName)
      case "empleo":
        return this.generateEmploymentData(fileName)
      case "aeropuerto":
        return this.generateAirportData(fileName)
      case "inflacion":
        return this.generateInflationData(fileName)
      case "turistas":
        return this.generateTouristData(fileName)
      case "operacional":
        return this.generateOperationalData(fileName)
      case "origen":
        return this.generateOriginDestinationData(fileName)
      default:
        throw new Error(`Tipo de datos no soportado: ${dataType}`)
    }
  }

  private static generateAirbnbData(fileName: string): ExcelProcessorResult {
    const headers = [
      "Property ID",
      "Property Type",
      "Listing Type",
      "Bedrooms",
      "Reporting Month",
      "Occupancy Rate",
      "Active Listing Nights",
      "Revenue (USD)",
      "ADR (USD)",
      "Number of Reservations",
      "Country",
      "State",
      "City",
      "Neighborhood",
      "Latitude",
      "Longitude",
    ]

    const propertyTypes = ["Entire home/apt", "Private room", "Shared room", "Hotel room"]
    const neighborhoods = ["Centro Histórico", "Montejo", "García Ginerés", "Itzimná", "Campestre"]
    const data = []

    // Generar datos realistas para Airbnb
    for (let i = 1; i <= 2500; i++) {
      const occupancyRate = Math.round((Math.random() * 40 + 50) * 100) / 100
      const revenue = Math.round((Math.random() * 3000 + 800) * 100) / 100
      const reservations = Math.floor(Math.random() * 15 + 5)

      data.push({
        "Property ID": `MID${i.toString().padStart(6, "0")}`,
        "Property Type": propertyTypes[Math.floor(Math.random() * propertyTypes.length)],
        "Listing Type": "Entire home/apt",
        Bedrooms: Math.floor(Math.random() * 4) + 1,
        "Reporting Month": "2025-03-01",
        "Occupancy Rate": occupancyRate,
        "Active Listing Nights": Math.floor(occupancyRate * 0.31),
        "Revenue (USD)": revenue,
        "ADR (USD)": Math.round((revenue / reservations) * 100) / 100,
        "Number of Reservations": reservations,
        Country: "Mexico",
        State: "Yucatan",
        City: "Merida",
        Neighborhood: neighborhoods[Math.floor(Math.random() * neighborhoods.length)],
        Latitude: 20.9674 + (Math.random() - 0.5) * 0.1,
        Longitude: -89.5926 + (Math.random() - 0.5) * 0.1,
      })
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Airbnb_Data"],
    }
  }

  private static generateHotelData(fileName: string): ExcelProcessorResult {
    const headers = ["Año", "Mes", "Porcentaje_Ocupacion", "Cuartos_Disponibles", "Cuartos_Ocupados"]
    const months = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ]
    const data = []

    // Generar datos para 3 años
    for (let year = 2023; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        // Solo hasta marzo para 2025
        if (year === 2025 && month > 2) break

        const cuartosDisponibles = 15000 + Math.floor(Math.random() * 2000)
        const ocupacion = Math.round((Math.random() * 30 + 60) * 100) / 100
        const cuartosOcupados = Math.floor((cuartosDisponibles * ocupacion) / 100)

        data.push({
          Año: year,
          Mes: months[month],
          Porcentaje_Ocupacion: ocupacion,
          Cuartos_Disponibles: cuartosDisponibles,
          Cuartos_Ocupados: cuartosOcupados,
        })
      }
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Ocupacion_Hotelera"],
    }
  }

  private static generateEmploymentData(fileName: string): ExcelProcessorResult {
    const headers = ["Categoria", "Subcategoria", "Total", "Hombres", "Mujeres", "Porcentaje"]
    const data = [
      {
        Categoria: "PEA",
        Subcategoria: "Ocupada",
        Total: 648469,
        Hombres: 360016,
        Mujeres: 288453,
        Porcentaje: 94.2,
      },
      {
        Categoria: "PEA",
        Subcategoria: "Desocupada",
        Total: 39740,
        Hombres: 21308,
        Mujeres: 18432,
        Porcentaje: 5.8,
      },
      {
        Categoria: "PNEA",
        Subcategoria: "Disponible",
        Total: 45230,
        Hombres: 15420,
        Mujeres: 29810,
        Porcentaje: 100,
      },
      {
        Categoria: "Sector Servicios",
        Subcategoria: "Total",
        Total: 442000,
        Hombres: 245000,
        Mujeres: 197000,
        Porcentaje: 68.2,
      },
      {
        Categoria: "Sector Comercio",
        Subcategoria: "Total",
        Total: 120000,
        Hombres: 66000,
        Mujeres: 54000,
        Porcentaje: 18.5,
      },
      {
        Categoria: "Sector Industria",
        Subcategoria: "Total",
        Total: 86000,
        Hombres: 58000,
        Mujeres: 28000,
        Porcentaje: 13.3,
      },
    ]

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Empleo_Merida"],
    }
  }

  private static generateAirportData(fileName: string): ExcelProcessorResult {
    const headers = ["Año", "Mes", "Tipo_Pasajero", "Cantidad", "Operaciones"]
    const months = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ]
    const types = ["Domestic", "International"]
    const data = []

    for (let year = 2023; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        if (year === 2025 && month > 2) break

        for (const type of types) {
          const basePassengers = type === "Domestic" ? 180000 : 45000
          const passengers = basePassengers + Math.floor(Math.random() * 20000 - 10000)
          const operations = Math.floor(passengers / 150) // Aproximadamente 150 pasajeros por vuelo

          data.push({
            Año: year,
            Mes: months[month],
            Tipo_Pasajero: type,
            Cantidad: passengers,
            Operaciones: operations,
          })
        }
      }
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Arribos_Aeropuerto"],
    }
  }

  private static generateInflationData(fileName: string): ExcelProcessorResult {
    const headers = ["Fecha", "INPC", "Variacion_Mensual", "Variacion_Anual"]
    const data = []
    let currentINPC = 100

    // Generar datos mensuales para 3 años
    for (let year = 2023; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        if (year === 2025 && month > 2) break

        const date = new Date(year, month, 1)
        const variacionMensual = (Math.random() - 0.5) * 2 // -1% a +1%
        currentINPC *= 1 + variacionMensual / 100

        // Calcular variación anual (simulada)
        const variacionAnual = 3 + Math.random() * 4 // 3% a 7%

        data.push({
          Fecha: date.toISOString().split("T")[0],
          INPC: Math.round(currentINPC * 100) / 100,
          Variacion_Mensual: Math.round(variacionMensual * 100) / 100,
          Variacion_Anual: Math.round(variacionAnual * 100) / 100,
        })
      }
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["INPC_Merida"],
    }
  }

  private static generateTouristData(fileName: string): ExcelProcessorResult {
    const headers = ["Año", "Mes", "Turistas_Nacionales", "Turistas_Extranjeros", "Total", "Estancia_Promedio"]
    const months = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ]
    const data = []

    for (let year = 2023; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        if (year === 2025 && month > 2) break

        const nacionales = 45000 + Math.floor(Math.random() * 15000)
        const extranjeros = 25000 + Math.floor(Math.random() * 10000)
        const total = nacionales + extranjeros
        const estanciaPromedio = 2.5 + Math.random() * 2 // 2.5 a 4.5 días

        data.push({
          Año: year,
          Mes: months[month],
          Turistas_Nacionales: nacionales,
          Turistas_Extranjeros: extranjeros,
          Total: total,
          Estancia_Promedio: Math.round(estanciaPromedio * 10) / 10,
        })
      }
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Pernoctas"],
    }
  }

  private static generateOperationalData(fileName: string): ExcelProcessorResult {
    const headers = ["Año", "Mes", "Tipo_Movimiento", "Origen_Destino", "Porcentaje", "Operaciones"]
    const movements = ["Llegada", "Salida"]
    const origins = ["Norteamérica", "Centroamérica", "Sudamérica", "Europa", "Nacional"]
    const data = []

    for (let year = 2024; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        if (year === 2025 && month > 2) break

        for (const movement of movements) {
          for (const origin of origins) {
            const porcentaje = Math.random() * 30 + 5 // 5% a 35%
            const operaciones = Math.floor(Math.random() * 200 + 50)

            data.push({
              Año: year,
              Mes: month + 1,
              Tipo_Movimiento: movement,
              Origen_Destino: origin,
              Porcentaje: Math.round(porcentaje * 100) / 100,
              Operaciones: operaciones,
            })
          }
        }
      }
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Movimiento_Operacional"],
    }
  }

  private static generateOriginDestinationData(fileName: string): ExcelProcessorResult {
    const headers = [
      "Año",
      "Mes",
      "Pais",
      "Aeropuerto",
      "Codigo_Aeropuerto",
      "Pasajeros",
      "Porcentaje_Pasajeros",
      "Operaciones",
    ]
    const countries = [
      {
        name: "Estados Unidos",
        airports: [
          { name: "Miami International", code: "MIA" },
          { name: "Houston Intercontinental", code: "IAH" },
        ],
      },
      {
        name: "México",
        airports: [
          { name: "Ciudad de México", code: "MEX" },
          { name: "Cancún", code: "CUN" },
        ],
      },
      {
        name: "Canadá",
        airports: [
          { name: "Toronto Pearson", code: "YYZ" },
          { name: "Montreal", code: "YUL" },
        ],
      },
    ]
    const data = []

    for (let year = 2024; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        if (year === 2025 && month > 2) break

        for (const country of countries) {
          for (const airport of country.airports) {
            const pasajeros = Math.floor(Math.random() * 15000 + 5000)
            const porcentaje = Math.random() * 20 + 5
            const operaciones = Math.floor(pasajeros / 150)

            data.push({
              Año: year,
              Mes: month + 1,
              Pais: country.name,
              Aeropuerto: airport.name,
              Codigo_Aeropuerto: airport.code,
              Pasajeros: pasajeros,
              Porcentaje_Pasajeros: Math.round(porcentaje * 100) / 100,
              Operaciones: operaciones,
            })
          }
        }
      }
    }

    return {
      headers,
      data,
      totalRows: data.length,
      processedRows: data.length,
      sheets: ["Origen_Destino"],
    }
  }
}
