"use client"

import { Badge } from "@/components/ui/badge"
import { parseCSVLine } from "@/utils/csv-parser" // Import parseCSVLine from a utility file
import { ExcelInfoAlert } from "@/components/excel-info-alert"
import { ExcelProcessor } from "@/lib/excel-processor"
import { DataService } from "@/lib/data-service"
import { DatabaseService } from "@/lib/database-service"
import { StorageWarning } from "@/components/storage-warning"

import type React from "react"

import { useState } from "react"
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from "@/components/ui/dialog"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Progress } from "@/components/ui/progress"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Upload, FileText, CheckCircle, AlertCircle, Database } from "lucide-react"
import { useToast } from "@/hooks/use-toast"

interface DataUploadModalProps {
  open: boolean
  onOpenChange: (open: boolean) => void
}

const dataTypes = [
  { value: "empleo", label: "Datos de Empleo", description: "primer_trimestre_empleo_2025-merida-corregido.xlsx" },
  { value: "inflacion", label: "Datos de Inflación", description: "INPC MERIDA Marzo 2025.xlsx" },
  { value: "hoteles", label: "Ocupación Hotelera", description: "OCUPACION HOTELERA a Marzo 2025.xlsx" },
  { value: "aeropuerto", label: "Tráfico Aeroportuario", description: "Arribos a Marzo 2025.xlsx" },
  { value: "turistas", label: "Datos de Turistas", description: "Pernocta Marzo 2025.xls" },
  { value: "airbnb", label: "Datos Airbnb", description: "AIRBNB - MARZO1.csv" },
  { value: "operacional", label: "Movimiento Operacional", description: "movimiento operacional marzo 2025.xlsx" },
  { value: "origen", label: "Origen-Destino", description: "Origen destino Marzo 2025.xlsx" },
]

export function DataUploadModal({ open, onOpenChange }: DataUploadModalProps) {
  const [selectedFile, setSelectedFile] = useState<File | null>(null)
  const [dataType, setDataType] = useState<string>("")
  const [isUploading, setIsUploading] = useState(false)
  const [uploadProgress, setUploadProgress] = useState(0)
  const [uploadStatus, setUploadStatus] = useState<"idle" | "success" | "error">("idle")
  const { toast } = useToast()

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0]
    if (file) {
      setSelectedFile(file)
      setUploadStatus("idle")
    }
  }

  const processCSVData = (csvText: string, type: string) => {
    try {
      // Limpiar el texto CSV
      const cleanText = csvText.trim()
      if (!cleanText) {
        throw new Error("El archivo está vacío")
      }

      const lines = cleanText.split(/\r?\n/).filter((line) => line.trim())
      if (lines.length < 2) {
        throw new Error("El archivo debe tener al menos una fila de encabezados y una fila de datos")
      }

      // Procesar encabezados
      const headers = parseCSVLine(lines[0]).map((h) => h.replace(/^"|"$/g, "").trim())

      if (headers.length === 0) {
        throw new Error("No se encontraron encabezados válidos")
      }

      const data = []
      let processedRows = 0
      const maxRows = 10000 // Limitar para evitar problemas de memoria

      // Procesar datos
      for (let i = 1; i < Math.min(lines.length, maxRows + 1); i++) {
        const line = lines[i].trim()
        if (!line) continue

        try {
          const values = parseCSVLine(line)
          const row: any = {}

          headers.forEach((header, index) => {
            let value = values[index] || ""

            // Limpiar valor
            value = value.replace(/^"|"$/g, "").trim()

            // Intentar convertir a número si es apropiado
            if (value && !isNaN(Number(value)) && value !== "") {
              const numValue = Number(value)
              if (!isNaN(numValue) && isFinite(numValue)) {
                row[header] = numValue
              } else {
                row[header] = value
              }
            } else {
              row[header] = value
            }
          })

          data.push(row)
          processedRows++
        } catch (rowError) {
          console.warn(`Error procesando fila ${i}:`, rowError)
          // Continuar con la siguiente fila
        }
      }

      if (data.length === 0) {
        throw new Error("No se pudieron procesar datos válidos del archivo")
      }

      console.log(`Procesadas ${processedRows} filas de ${lines.length - 1} total`)

      return {
        headers,
        data: data.slice(0, 5000), // Limitar datos almacenados
        totalRows: lines.length - 1,
        processedRows,
      }
    } catch (error) {
      console.error("Error procesando CSV:", error)
      throw error
    }
  }

  const processLargeCSV = async (csvText: string, type: string) => {
    try {
      const lines = csvText.split(/\r?\n/).filter((line) => line.trim())
      if (lines.length < 2) {
        throw new Error("El archivo debe tener al menos una fila de encabezados y una fila de datos")
      }

      // Procesar en chunks para archivos grandes
      const CHUNK_SIZE = 1000
      const headers = parseCSVLine(lines[0]).map((h) => h.replace(/^"|"$/g, "").trim())
      const data = []
      let processedRows = 0

      for (let i = 1; i < lines.length; i += CHUNK_SIZE) {
        const chunk = lines.slice(i, i + CHUNK_SIZE)

        for (const line of chunk) {
          if (!line.trim()) continue

          try {
            const values = parseCSVLine(line)
            const row: any = {}

            headers.forEach((header, index) => {
              let value = values[index] || ""
              value = value.replace(/^"|"$/g, "").trim()

              if (value && !isNaN(Number(value)) && value !== "") {
                const numValue = Number(value)
                if (!isNaN(numValue) && isFinite(numValue)) {
                  row[header] = numValue
                } else {
                  row[header] = value
                }
              } else {
                row[header] = value
              }
            })

            data.push(row)
            processedRows++
          } catch (rowError) {
            console.warn(`Error procesando fila ${i}:`, rowError)
          }
        }

        // Actualizar progreso
        const progress = Math.min(80, 40 + (i / lines.length) * 40)
        setUploadProgress(progress)

        // Permitir que el navegador respire
        await new Promise((resolve) => setTimeout(resolve, 10))
      }

      return {
        headers,
        data: data.slice(0, 50000), // Aumentar límite a 50k registros
        totalRows: lines.length - 1,
        processedRows,
      }
    } catch (error) {
      console.error("Error procesando CSV:", error)
      throw error
    }
  }

  const processExcelFile = async (file: File, type: string) => {
    try {
      setUploadProgress(60)
      const result = await ExcelProcessor.processFile(file, type)
      setUploadProgress(80)

      return {
        headers: result.headers,
        data: result.data,
        totalRows: result.totalRows,
        processedRows: result.processedRows,
      }
    } catch (error) {
      console.error("Error procesando Excel:", error)
      throw error
    }
  }

  const handleUpload = async () => {
    if (!selectedFile || !dataType) {
      toast({
        title: "Error",
        description: "Por favor selecciona un archivo y tipo de datos",
        variant: "destructive",
      })
      return
    }

    // Verificar tamaño del archivo (máximo 50MB para evitar problemas)
    if (selectedFile.size > 50 * 1024 * 1024) {
      toast({
        title: "Error",
        description: "El archivo es demasiado grande. Máximo 50MB permitido para evitar problemas de almacenamiento.",
        variant: "destructive",
      })
      return
    }

    setIsUploading(true)
    setUploadProgress(0)
    setUploadStatus("idle")

    try {
      // Simular progreso de carga
      const progressInterval = setInterval(() => {
        setUploadProgress((prev) => {
          if (prev >= 80) {
            clearInterval(progressInterval)
            return 80
          }
          return prev + 10
        })
      }, 300)

      // Leer archivo
      setUploadProgress(20)
      const text = await selectedFile.text()

      setUploadProgress(40)

      // Procesar datos según el tipo
      let processedData
      if (selectedFile.name.toLowerCase().endsWith(".csv")) {
        processedData = await processLargeCSV(text, dataType)
      } else if (
        selectedFile.name.toLowerCase().endsWith(".xlsx") ||
        selectedFile.name.toLowerCase().endsWith(".xls")
      ) {
        // Procesar archivos Excel
        processedData = await processExcelFile(selectedFile, dataType)
      } else {
        throw new Error("Formato de archivo no soportado. Solo se permiten archivos CSV, XLS y XLSX.")
      }

      setUploadProgress(80)

      // Limitar datos para evitar problemas de almacenamiento
      const maxRecords = 1000
      if (processedData.data.length > maxRecords) {
        processedData.data = processedData.data.slice(0, maxRecords)
        toast({
          title: "Datos limitados",
          description: `Se procesaron solo ${maxRecords} registros de ${processedData.totalRows} para optimizar el almacenamiento.`,
        })
      }

      // Guardar en la base de datos centralizada
      try {
        DatabaseService.processAndSaveData(dataType, processedData.data, selectedFile.name)
      } catch (storageError) {
        if (storageError instanceof Error && storageError.message.includes("quota")) {
          toast({
            title: "Almacenamiento lleno",
            description: "Se guardó una muestra de los datos. Considera limpiar datos antiguos.",
            variant: "destructive",
          })
        } else {
          throw storageError
        }
      }

      clearInterval(progressInterval)
      setUploadProgress(100)
      setUploadStatus("success")

      const selectedDataType = dataTypes.find((dt) => dt.value === dataType)
      toast({
        title: "¡Éxito!",
        description: `${processedData.processedRows || processedData.data.length} registros de ${selectedDataType?.label} cargados correctamente`,
      })

      // Disparar evento personalizado para actualizar gráficas
      console.log("Disparando evento dataUpdated para tipo:", dataType)
      window.dispatchEvent(new CustomEvent("dataUpdated", { detail: { type: dataType } }))

      setTimeout(() => {
        onOpenChange(false)
        setSelectedFile(null)
        setDataType("")
        setUploadProgress(0)
        setUploadStatus("idle")
      }, 2000)
    } catch (error) {
      console.error("Error completo:", error)
      setUploadStatus("error")
      toast({
        title: "Error",
        description: error instanceof Error ? error.message : "Error al procesar el archivo. Verifica el formato.",
        variant: "destructive",
      })
    } finally {
      setIsUploading(false)
    }
  }

  // Actualizar getStoredDataInfo para mostrar más información
  const getStoredDataInfo = () => {
    try {
      const data = DataService.getAllData()
      return Object.keys(data).map((key) => ({
        type: key,
        label: dataTypes.find((dt) => dt.value === key)?.label || key,
        fileName: data[key].fileName,
        uploadDate: new Date(data[key].uploadDate).toLocaleDateString(),
        recordCount: data[key].data?.length || 0,
        totalRows: data[key].data?.length || 0,
        fileSize: data[key].fileSize ? `${(data[key].fileSize / 1024 / 1024).toFixed(1)} MB` : "N/A",
      }))
    } catch (error) {
      console.error("Error obteniendo información de datos:", error)
      return []
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-4xl max-h-[80vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Database className="h-5 w-5" />
            Gestión de Datos
          </DialogTitle>
          <DialogDescription>
            Carga archivos CSV/Excel para alimentar las visualizaciones del dashboard
          </DialogDescription>
        </DialogHeader>

        <div className="grid gap-6 md:grid-cols-2">
          {/* Panel de carga */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Upload className="h-4 w-4" />
                Cargar Nuevo Archivo
              </CardTitle>
              <CardDescription>Selecciona el tipo de datos y el archivo correspondiente</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <ExcelInfoAlert />

              <div className="space-y-2">
                <Label htmlFor="dataType">Tipo de Datos</Label>
                <Select value={dataType} onValueChange={setDataType}>
                  <SelectTrigger>
                    <SelectValue placeholder="Selecciona el tipo de datos" />
                  </SelectTrigger>
                  <SelectContent>
                    {dataTypes.map((type) => (
                      <SelectItem key={type.value} value={type.value}>
                        <div>
                          <div className="font-medium">{type.label}</div>
                          <div className="text-xs text-muted-foreground">{type.description}</div>
                        </div>
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-2">
                <Label htmlFor="file">Archivo</Label>
                <Input
                  id="file"
                  type="file"
                  accept=".csv,.xlsx,.xls"
                  onChange={handleFileChange}
                  className="cursor-pointer"
                />
                {selectedFile && (
                  <div className="flex items-center gap-2 text-sm text-muted-foreground">
                    <FileText className="h-4 w-4" />
                    {selectedFile.name} ({(selectedFile.size / 1024).toFixed(1)} KB)
                  </div>
                )}
              </div>

              {isUploading && (
                <div className="space-y-2">
                  <div className="flex items-center justify-between text-sm">
                    <span>Procesando archivo...</span>
                    <span>{uploadProgress}%</span>
                  </div>
                  <Progress value={uploadProgress} />
                </div>
              )}

              {uploadStatus === "success" && (
                <Alert>
                  <CheckCircle className="h-4 w-4" />
                  <AlertDescription>Archivo procesado y datos guardados exitosamente</AlertDescription>
                </Alert>
              )}

              {uploadStatus === "error" && (
                <Alert variant="destructive">
                  <AlertCircle className="h-4 w-4" />
                  <AlertDescription>Error al procesar el archivo. Verifica el formato.</AlertDescription>
                </Alert>
              )}

              <Button onClick={handleUpload} disabled={!selectedFile || !dataType || isUploading} className="w-full">
                {isUploading ? "Procesando..." : "Cargar Datos"}
              </Button>
            </CardContent>
          </Card>

          {/* Panel de datos existentes */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Database className="h-4 w-4" />
                Datos Almacenados
              </CardTitle>
              <CardDescription>Archivos cargados actualmente en el sistema</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-3">
                {getStoredDataInfo().length === 0 ? (
                  <div className="text-center text-muted-foreground py-8">
                    <Database className="h-8 w-8 mx-auto mb-2 opacity-50" />
                    <p>No hay datos cargados</p>
                    <p className="text-xs">Carga tu primer archivo para comenzar</p>
                  </div>
                ) : (
                  getStoredDataInfo().map((info, index) => (
                    <div key={index} className="border rounded-lg p-3 space-y-2">
                      <div className="flex items-center justify-between">
                        <h4 className="font-medium text-sm">{info.label}</h4>
                        <Badge variant="secondary" className="text-xs">
                          {info.recordCount} registros
                        </Badge>
                      </div>
                      <p className="text-xs text-muted-foreground">{info.fileName}</p>
                      <div className="flex justify-between text-xs text-muted-foreground">
                        <span>Cargado: {info.uploadDate}</span>
                        <span>{info.fileSize}</span>
                      </div>
                    </div>
                  ))
                )}
              </div>
            </CardContent>
          </Card>
        </div>

        <StorageWarning />
      </DialogContent>
    </Dialog>
  )
}
