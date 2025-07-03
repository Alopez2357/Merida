"use client"

import { useEffect, useState } from "react"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { CheckCircle, Database, X } from "lucide-react"
import { Button } from "@/components/ui/button"

export function DataSyncNotification() {
  const [notifications, setNotifications] = useState<
    Array<{
      id: string
      type: string
      timestamp: string
    }>
  >([])

  useEffect(() => {
    const handleDatabaseUpdate = (event: CustomEvent) => {
      const { type, timestamp } = event.detail
      const newNotification = {
        id: `${type}-${timestamp}`,
        type,
        timestamp,
      }

      setNotifications((prev) => [newNotification, ...prev.slice(0, 2)]) // Mantener solo 3 notificaciones

      // Auto-remover después de 5 segundos
      setTimeout(() => {
        setNotifications((prev) => prev.filter((n) => n.id !== newNotification.id))
      }, 5000)
    }

    window.addEventListener("databaseUpdated", handleDatabaseUpdate as EventListener)

    return () => {
      window.removeEventListener("databaseUpdated", handleDatabaseUpdate as EventListener)
    }
  }, [])

  const removeNotification = (id: string) => {
    setNotifications((prev) => prev.filter((n) => n.id !== id))
  }

  const getTypeLabel = (type: string) => {
    const labels: { [key: string]: string } = {
      empleo: "Datos de Empleo",
      hoteles: "Ocupación Hotelera",
      aeropuerto: "Tráfico Aeroportuario",
      airbnb: "Propiedades Airbnb",
      inflacion: "Datos de Inflación",
      turistas: "Datos de Turistas",
    }
    return labels[type] || type
  }

  if (notifications.length === 0) return null

  return (
    <div className="fixed top-4 right-4 z-50 space-y-2 max-w-sm">
      {notifications.map((notification) => (
        <Alert key={notification.id} className="bg-green-50 border-green-200">
          <CheckCircle className="h-4 w-4 text-green-600" />
          <AlertDescription className="flex items-center justify-between">
            <div>
              <div className="font-medium text-green-800">{getTypeLabel(notification.type)} actualizados</div>
              <div className="text-xs text-green-600">
                <Database className="h-3 w-3 inline mr-1" />
                Todas las vistas sincronizadas
              </div>
            </div>
            <Button
              variant="ghost"
              size="sm"
              className="h-6 w-6 p-0 text-green-600 hover:text-green-800"
              onClick={() => removeNotification(notification.id)}
            >
              <X className="h-3 w-3" />
            </Button>
          </AlertDescription>
        </Alert>
      ))}
    </div>
  )
}
