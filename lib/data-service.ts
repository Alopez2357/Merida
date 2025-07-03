export interface DataRecord {
  [key: string]: string | number
}

export interface DataSet {
  data: DataRecord[]
  headers: string[]
  uploadDate: string
  fileName: string
}

export interface StoredData {
  [key: string]: DataSet
}

export class DataService {
  private static STORAGE_KEY = "dashboardData"

  static getAllData(): StoredData {
    if (typeof window === "undefined") return {}
    return JSON.parse(localStorage.getItem(this.STORAGE_KEY) || "{}")
  }

  static getDataByType(type: string): DataSet | null {
    const allData = this.getAllData()
    return allData[type] || null
  }

  static saveData(type: string, dataset: DataSet): void {
    if (typeof window === "undefined") return

    const allData = this.getAllData()
    allData[type] = dataset
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(allData))
  }

  static deleteData(type: string): void {
    if (typeof window === "undefined") return

    const allData = this.getAllData()
    delete allData[type]
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(allData))
  }

  // Métodos específicos para cada tipo de gráfica
  static getEmploymentData(): DataRecord[] {
    const dataset = this.getDataByType("empleo")
    if (dataset?.data && dataset.data.length > 0) {
      // Usar datos reales cargados
      return dataset.data
    }

    // Datos por defecto si no hay datos cargados
    return [
      { sector: "Servicios", empleados: 342000, porcentaje: 68.2 },
      { sector: "Comercio", empleados: 98000, porcentaje: 18.5 },
      { sector: "Industria", empleados: 67000, porcentaje: 13.3 },
      { sector: "Construcción", empleados: 45000, porcentaje: 8.9 },
      { sector: "Agricultura", empleados: 23000, porcentaje: 4.6 },
    ]
  }

  static getInflationData(): DataRecord[] {
    const dataset = this.getDataByType("inflacion")
    if (dataset?.data && dataset.data.length > 0) {
      return dataset.data
    }

    return [
      { mes: "Ene", inpc: 5.2, fecha: "2025-01-01" },
      { mes: "Feb", inpc: 5.1, fecha: "2025-02-01" },
      { mes: "Mar", inpc: 4.8, fecha: "2025-03-01" },
    ]
  }

  static getHotelData(): DataRecord[] {
    const dataset = this.getDataByType("hoteles")
    if (dataset?.data && dataset.data.length > 0) {
      return dataset.data
    }

    return [
      { mes: "Ene", ocupacion2024: 65, ocupacion2025: 70 },
      { mes: "Feb", ocupacion2024: 68, ocupacion2025: 73 },
      { mes: "Mar", ocupacion2024: 72, ocupacion2025: 78.5 },
    ]
  }

  static getAirportData(): DataRecord[] {
    const dataset = this.getDataByType("aeropuerto")
    if (dataset?.data && dataset.data.length > 0) {
      return dataset.data
    }

    return [
      { mes: "Ene", nacionales: 180000, internacionales: 45000 },
      { mes: "Feb", nacionales: 195000, internacionales: 48000 },
      { mes: "Mar", nacionales: 191730, internacionales: 53950 },
    ]
  }

  static getAirbnbData(): DataRecord[] {
    const dataset = this.getDataByType("airbnb")
    if (dataset?.data && dataset.data.length > 0) {
      return dataset.data
    }

    return [
      {
        id: 1,
        nombre: "Casa Centro Histórico",
        precio: 1200,
        tipo: "Casa completa",
        huespedes: 4,
        calificacion: 4.8,
        ubicacion: "Centro",
      },
      {
        id: 2,
        nombre: "Departamento Moderno",
        precio: 800,
        tipo: "Departamento",
        huespedes: 2,
        calificacion: 4.5,
        ubicacion: "Norte",
      },
    ]
  }

  static getDataStats(type: string): { count: number; lastUpdate: string | null } {
    const dataset = this.getDataByType(type)
    return {
      count: dataset?.data?.length || 0,
      lastUpdate: dataset?.uploadDate || null,
    }
  }

  static processCSV(csvText: string): { headers: string[]; data: DataRecord[] } {
    try {
      const lines = csvText.split(/\r?\n/).filter((line) => line.trim())
      if (lines.length === 0) return { headers: [], data: [] }

      // Función mejorada para parsear CSV con comillas
      const parseCSVLine = (line: string): string[] => {
        const result: string[] = []
        let current = ""
        let inQuotes = false

        for (let i = 0; i < line.length; i++) {
          const char = line[i]
          const nextChar = line[i + 1]

          if (char === '"') {
            if (inQuotes && nextChar === '"') {
              current += '"'
              i++ // Skip next quote
            } else {
              inQuotes = !inQuotes
            }
          } else if (char === "," && !inQuotes) {
            result.push(current.trim())
            current = ""
          } else {
            current += char
          }
        }

        result.push(current.trim())
        return result.map((field) => field.replace(/^"|"$/g, ""))
      }

      const headers = parseCSVLine(lines[0])
      const data: DataRecord[] = []

      for (let i = 1; i < lines.length; i++) {
        if (!lines[i].trim()) continue

        try {
          const values = parseCSVLine(lines[i])
          const row: DataRecord = {}

          headers.forEach((header, index) => {
            const value = values[index] || ""
            const numValue = Number.parseFloat(value)
            row[header] = isNaN(numValue) ? value : numValue
          })

          data.push(row)
        } catch (error) {
          console.warn(`Error parsing row ${i}:`, error)
        }
      }

      return { headers, data }
    } catch (error) {
      console.error("Error processing CSV:", error)
      throw error
    }
  }

  // Método para notificar cambios
  static notifyDataUpdate(type: string): void {
    window.dispatchEvent(new CustomEvent("dataUpdated", { detail: { type } }))
  }
}
