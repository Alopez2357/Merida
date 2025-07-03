"use client"

import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"
import { Info, FileSpreadsheet } from "lucide-react"

export function ExcelInfoAlert() {
  return (
    <Alert className="mb-4">
      <Info className="h-4 w-4" />
      <AlertTitle>Procesamiento de Archivos Excel</AlertTitle>
      <AlertDescription className="mt-2">
        <div className="space-y-2 text-sm">
          <p>
            <strong>Archivos Excel soportados:</strong> El sistema puede procesar archivos .xlsx y .xls generando datos
            de ejemplo basados en el tipo seleccionado.
          </p>
          <div className="flex items-center gap-2 mt-2">
            <FileSpreadsheet className="h-4 w-4" />
            <span>Para mejor precisión, convierte tus archivos Excel a CSV antes de cargarlos.</span>
          </div>
        </div>
      </AlertDescription>
    </Alert>
  )
}
