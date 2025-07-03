"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import { SidebarProvider, SidebarInset, SidebarTrigger } from "@/components/ui/sidebar"
import { AppSidebar } from "@/components/app-sidebar"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { HotelOccupancyChart } from "@/components/charts/hotel-occupancy-chart"
import { DataUploadModal } from "@/components/data-upload-modal"
import { Building2, Upload, Download, RefreshCw, Calendar, TrendingUp } from "lucide-react"

export default function HotelesPage() {
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
              <Building2 className="h-5 w-5 text-blue-600" />
              <h1 className="text-lg font-semibold">Ocupación Hotelera</h1>
              <Badge variant="secondary">
                <Calendar className="h-3 w-3 mr-1" />
                Marzo 2025
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
                <Building2 className="h-5 w-5" />
                Porcentaje de Ocupación Hotelera
              </CardTitle>
              <CardDescription>Comparativo mensual de ocupación hotelera 2024 vs 2025 en Mérida</CardDescription>
            </CardHeader>
            <CardContent>
              <div style={{ height: "500px" }}>
                <HotelOccupancyChart />
              </div>
            </CardContent>
          </Card>

          {/* KPIs */}
          <div className="grid gap-4 md:grid-cols-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Ocupación Actual</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-blue-600">78.5%</div>
                <div className="flex items-center text-xs text-green-600">
                  <TrendingUp className="h-3 w-3 mr-1" />
                  +5.2% vs 2024
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Cuartos Disponibles</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">15,420</div>
                <div className="text-xs text-muted-foreground">Total en la ciudad</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Cuartos Ocupados</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">12,105</div>
                <div className="text-xs text-muted-foreground">Promedio mensual</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Estancia Promedio</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">3.2</div>
                <div className="text-xs text-muted-foreground">Noches por huésped</div>
              </CardContent>
            </Card>
          </div>

          {/* Análisis detallado */}
          <div className="grid gap-6 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Análisis por Categoría</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Hoteles 5 Estrellas</span>
                    <Badge variant="secondary">85.2%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Hoteles 4 Estrellas</span>
                    <Badge variant="secondary">78.5%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Hoteles 3 Estrellas</span>
                    <Badge variant="secondary">72.1%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Hoteles Boutique</span>
                    <Badge variant="secondary">81.3%</Badge>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-base">Tendencias Estacionales</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Temporada Alta (Dic-Mar)</span>
                    <Badge variant="secondary" className="text-green-600">
                      85-90%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Temporada Media (Abr-Jun)</span>
                    <Badge variant="secondary" className="text-amber-600">
                      70-80%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Temporada Baja (Jul-Nov)</span>
                    <Badge variant="secondary" className="text-red-600">
                      60-75%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Eventos Especiales</span>
                    <Badge variant="secondary" className="text-blue-600">
                      95%+
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Información adicional */}
          <Card>
            <CardHeader>
              <CardTitle className="text-base">Factores de Influencia</CardTitle>
              <CardDescription>Elementos que afectan la ocupación hotelera</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <h4 className="font-medium text-blue-600">Factores Positivos</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Turismo cultural (Chichén Itzá)</li>
                    <li>• Gastronomía yucateca</li>
                    <li>• Eventos y convenciones</li>
                    <li>• Conectividad aérea mejorada</li>
                    <li>• Seguridad turística</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-amber-600">Desafíos</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Competencia con Riviera Maya</li>
                    <li>• Estacionalidad marcada</li>
                    <li>• Infraestructura turística</li>
                    <li>• Capacitación del personal</li>
                    <li>• Promoción internacional</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-green-600">Oportunidades</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Turismo de bienestar</li>
                    <li>• Turismo de negocios</li>
                    <li>• Experiencias auténticas</li>
                    <li>• Turismo sustentable</li>
                    <li>• Mercados emergentes</li>
                  </ul>
                </div>
              </div>
            </CardContent>
          </Card>
        </main>

        <DataUploadModal open={showUploadModal} onOpenChange={setShowUploadModal} />
      </SidebarInset>
    </SidebarProvider>
  )
}
