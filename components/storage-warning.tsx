"use client"

import { useEffect, useState } from "react"
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"
import { Button } from "@/components/ui/button"
import { Progress } from "@/components/ui/progress"
import { AlertTriangle, HardDrive, X, Trash2 } from "lucide-react"
import { DatabaseService } from "@/lib/database-service"

export function StorageWarning() {
  const [warnings, setWarnings] = useState<
    Array<{
      id: string
      message: string
      type: "warning" | "error"
      timestamp: string
    }>
  >([])

  const [storageInfo, setStorageInfo] = useState<{
    used: string
    available: string
    percentage: number
  } | null>(null)

  useEffect(() => {
    // Obtener información de almacenamiento
    const updateStorageInfo = () => {
      try {
        const info = DatabaseService.getStorageInfo()
        setStorageInfo(info)
      } catch (error) {
        console.error("Error obteniendo información de almacenamiento:", error)
      }
    }

    updateStorageInfo()
    const interval = setInterval(updateStorageInfo, 30000) // Actualizar cada 30 segundos

    // Escuchar advertencias de almacenamiento
    const handleStorageWarning = (event: CustomEvent) => {
      const { message, type } = event.detail
      const newWarning = {
        id: `${type}-${Date.now()}`,
        message,
        type,
        timestamp: new Date().toISOString(),
      }

      setWarnings((prev) => [newWarning, ...prev.slice(0, 2)]) // Mantener solo 3 advertencias

      // Auto-remover después de 10 segundos
      setTimeout(() => {
        setWarnings((prev) => prev.filter((w) => w.id !== newWarning.id))
      }, 10000)
    }

    window.addEventListener("storageWarning", handleStorageWarning as EventListener)

    return () => {
      clearInterval(interval)
      window.removeEventListener("storageWarning", handleStorageWarning as EventListener)
    }
  }, [])

  const removeWarning = (id: string) => {
    setWarnings((prev) => prev.filter((w) => w.id !== id))
  }

  const clearAllData = () => {
    if (confirm("¿Estás seguro de que quieres limpiar todos los datos? Esta acción no se puede deshacer.")) {
      DatabaseService.clearDatabase()
      setWarnings([])
      window.location.reload() // Recargar para refrescar todas las vistas
    }
  }

  return (
    <div className="fixed bottom-4 right-4 z-50 space-y-2 max-w-sm">
      {/* Información de almacenamiento */}
      {storageInfo && storageInfo.percentage > 70 && (
        <Alert className="bg-amber-50 border-amber-200">
          <HardDrive className="h-4 w-4 text-amber-600" />
          <AlertTitle className="text-amber-800">Almacenamiento</AlertTitle>
          <AlertDescription className="space-y-2">
            <div className="text-xs text-amber-700">
              Usado: {storageInfo.used} de {storageInfo.available}
            </div>
            <Progress value={storageInfo.percentage} className="h-2" />
            {storageInfo.percentage > 90 && (
              <Button
                variant="outline"
                size="sm"
                className="w-full text-amber-700 border-amber-300 bg-transparent"
                onClick={clearAllData}
              >
                <Trash2 className="h-3 w-3 mr-1" />
                Limpiar Datos
              </Button>
            )}
          </AlertDescription>
        </Alert>
      )}

      {/* Advertencias */}
      {warnings.map((warning) => (
        <Alert
          key={warning.id}
          className={warning.type === "error" ? "bg-red-50 border-red-200" : "bg-amber-50 border-amber-200"}
        >
          <AlertTriangle className={`h-4 w-4 ${warning.type === "error" ? "text-red-600" : "text-amber-600"}`} />
          <AlertDescription className="flex items-start justify-between">
            <div className="flex-1 pr-2">
              <div className={`font-medium ${warning.type === "error" ? "text-red-800" : "text-amber-800"}`}>
                Advertencia de Almacenamiento
              </div>
              <div className={`text-xs mt-1 ${warning.type === "error" ? "text-red-700" : "text-amber-700"}`}>
                {warning.message}
              </div>
            </div>
            <Button
              variant="ghost"
              size="sm"
              className={`h-6 w-6 p-0 ${
                warning.type === "error" ? "text-red-600 hover:text-red-800" : "text-amber-600 hover:text-amber-800"
              }`}
              onClick={() => removeWarning(warning.id)}
            >
              <X className="h-3 w-3" />
            </Button>
          </AlertDescription>
        </Alert>
      ))}
    </div>
  )
}
