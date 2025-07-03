"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import { SidebarProvider, SidebarInset, SidebarTrigger } from "@/components/ui/sidebar"
import { AppSidebar } from "@/components/app-sidebar"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { AirportTrafficChart } from "@/components/charts/airport-traffic-chart"
import { DataUploadModal } from "@/components/data-upload-modal"
import { Plane, Upload, Download, RefreshCw, Calendar, TrendingUp } from "lucide-react"

export default function AeropuertoPage() {
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
              <Plane className="h-5 w-5 text-blue-600" />
              <h1 className="text-lg font-semibold">Tráfico Aeroportuario</h1>
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
                <Plane className="h-5 w-5" />
                Movimiento de Pasajeros - Aeropuerto de Mérida
              </CardTitle>
              <CardDescription>Tráfico de pasajeros nacionales e internacionales por mes</CardDescription>
            </CardHeader>
            <CardContent>
              <div style={{ height: "500px" }}>
                <AirportTrafficChart />
              </div>
            </CardContent>
          </Card>

          {/* KPIs */}
          <div className="grid gap-4 md:grid-cols-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Total Pasajeros</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-blue-600">245,680</div>
                <div className="flex items-center text-xs text-green-600">
                  <TrendingUp className="h-3 w-3 mr-1" />
                  +12.4% vs 2024
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Vuelos Nacionales</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">191,730</div>
                <div className="text-xs text-muted-foreground">78% del total</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Vuelos Internacionales</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">53,950</div>
                <div className="text-xs text-muted-foreground">22% del total</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Operaciones Diarias</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">45</div>
                <div className="text-xs text-muted-foreground">Promedio</div>
              </CardContent>
            </Card>
          </div>

          {/* Análisis detallado */}
          <div className="grid gap-6 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Principales Destinos Nacionales</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Ciudad de México (MEX)</span>
                    <Badge variant="secondary">35%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Cancún (CUN)</span>
                    <Badge variant="secondary">18%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Guadalajara (GDL)</span>
                    <Badge variant="secondary">12%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Monterrey (MTY)</span>
                    <Badge variant="secondary">10%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Otros destinos</span>
                    <Badge variant="secondary">25%</Badge>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-base">Principales Destinos Internacionales</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Miami (MIA)</span>
                    <Badge variant="secondary">28%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Houston (IAH)</span>
                    <Badge variant="secondary">22%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Toronto (YYZ)</span>
                    <Badge variant="secondary">15%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Madrid (MAD)</span>
                    <Badge variant="secondary">12%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Otros destinos</span>
                    <Badge variant="secondary">23%</Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Información adicional */}
          <Card>
            <CardHeader>
              <CardTitle className="text-base">Análisis del Sector Aeroportuario</CardTitle>
              <CardDescription>Factores que influyen en el tráfico aéreo</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <h4 className="font-medium text-blue-600">Crecimiento</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Nuevas rutas internacionales</li>
                    <li>• Aumento del turismo</li>
                    <li>• Mejora en conectividad</li>
                    <li>• Inversión en infraestructura</li>
                    <li>• Promoción turística</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-green-600">Oportunidades</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Vuelos directos a Europa</li>
                    <li>• Carga aérea especializada</li>
                    <li>• Hub regional</li>
                    <li>• Turismo de negocios</li>
                    <li>• Aviación privada</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-amber-600">Desafíos</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Capacidad de pistas</li>
                    <li>• Competencia regional</li>
                    <li>• Costos operativos</li>
                    <li>• Regulaciones internacionales</li>
                    <li>• Sostenibilidad ambiental</li>
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
