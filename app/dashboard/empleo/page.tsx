"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import { SidebarProvider, SidebarInset, SidebarTrigger } from "@/components/ui/sidebar"
import { AppSidebar } from "@/components/app-sidebar"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { EmploymentChart } from "@/components/charts/employment-chart"
import { DataUploadModal } from "@/components/data-upload-modal"
import { Users, Upload, Download, RefreshCw, Calendar } from "lucide-react"
import { DataSyncNotification } from "@/components/data-sync-notification"

export default function EmpleoPage() {
  const [isAuthenticated, setIsAuthenticated] = useState(false)
  const [isLoading, setIsLoading] = useState(true)
  const [showUploadModal, setShowUploadModal] = useState(false)
  const router = useRouter()

  useEffect(() => {
    const checkAuth = () => {
      const auth = localStorage.getItem("isAuthenticated")
      if (auth === "true") {
        setIsAuthenticated(true)
      } else {
        router.push("/")
      }
      setIsLoading(false)
    }

    checkAuth()
  }, [router])

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
      </div>
    )
  }

  if (!isAuthenticated) {
    return null
  }

  return (
    <SidebarProvider defaultOpen={false}>
      <AppSidebar />
      <SidebarInset className="flex-1">
        <header className="sticky top-0 z-10 flex h-16 shrink-0 items-center gap-2 border-b bg-background px-4">
          <SidebarTrigger className="-ml-1" />
          <div className="flex flex-1 items-center justify-between">
            <div className="flex items-center gap-2">
              <Users className="h-5 w-5 text-blue-600" />
              <h1 className="text-lg font-semibold">Ocupación y Empleo</h1>
              <Badge variant="secondary">
                <Calendar className="h-3 w-3 mr-1" />
                Primer Trimestre 2025
              </Badge>
            </div>
            <div className="flex items-center gap-2">
              <Button variant="outline" size="sm">
                <RefreshCw className="h-4 w-4 mr-2" />
                Actualizar
              </Button>
              <Button variant="outline" size="sm">
                <Download className="h-4 w-4 mr-2" />
                Exportar
              </Button>
              <Button variant="outline" size="sm" onClick={() => setShowUploadModal(true)}>
                <Upload className="h-4 w-4 mr-2" />
                Cargar Datos
              </Button>
            </div>
          </div>
        </header>

        <main className="flex-1 space-y-6 p-6 overflow-auto">
          {/* Gráfica principal */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Users className="h-5 w-5" />
                Indicadores de Empleo por Sector
              </CardTitle>
              <CardDescription>
                Distribución del empleo en Mérida por sectores económicos - Primer trimestre 2025
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div style={{ height: "500px" }}>
                <EmploymentChart />
              </div>
            </CardContent>
          </Card>

          {/* Estadísticas adicionales */}
          <div className="grid gap-6 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Resumen Estadístico</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Tasa de Ocupación</span>
                    <Badge variant="secondary">94.2%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Población Económicamente Activa</span>
                    <Badge variant="secondary">688,209</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Sector Predominante</span>
                    <Badge variant="secondary">Servicios (68.2%)</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Crecimiento Anual</span>
                    <Badge variant="secondary" className="text-green-600">
                      +2.1%
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-base">Distribución por Género</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Hombres Ocupados</span>
                    <Badge variant="secondary">360,016 (55.5%)</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Mujeres Ocupadas</span>
                    <Badge variant="secondary">288,453 (44.5%)</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Brecha de Género</span>
                    <Badge variant="secondary">11.0 puntos</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Tendencia</span>
                    <Badge variant="secondary" className="text-green-600">
                      Reduciendo
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Información adicional */}
          <Card>
            <CardHeader>
              <CardTitle className="text-base">Análisis Sectorial</CardTitle>
              <CardDescription>Desglose detallado por sector económico</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <h4 className="font-medium text-blue-600">Sector Servicios</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Comercio al por menor: 35%</li>
                    <li>• Servicios profesionales: 20%</li>
                    <li>• Turismo y hospitalidad: 15%</li>
                    <li>• Servicios financieros: 10%</li>
                    <li>• Otros servicios: 20%</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-green-600">Sector Industrial</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Manufactura textil: 40%</li>
                    <li>• Industria alimentaria: 25%</li>
                    <li>• Construcción: 20%</li>
                    <li>• Industria química: 10%</li>
                    <li>• Otras industrias: 5%</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-amber-600">Sector Primario</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Agricultura: 60%</li>
                    <li>• Ganadería: 25%</li>
                    <li>• Pesca: 10%</li>
                    <li>• Silvicultura: 3%</li>
                    <li>• Minería: 2%</li>
                  </ul>
                </div>
              </div>
            </CardContent>
          </Card>
        </main>

        <DataUploadModal open={showUploadModal} onOpenChange={setShowUploadModal} />
      </SidebarInset>
      <DataSyncNotification />
    </SidebarProvider>
  )
}
