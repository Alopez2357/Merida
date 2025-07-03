"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import { SidebarProvider, SidebarInset, SidebarTrigger } from "@/components/ui/sidebar"
import { AppSidebar } from "@/components/app-sidebar"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { AirbnbChart } from "@/components/charts/airbnb-chart"
import { DataUploadModal } from "@/components/data-upload-modal"
import { Building2, Upload, Download, RefreshCw, Calendar, TrendingUp } from "lucide-react"

export default function AirbnbPage() {
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
              <h1 className="text-lg font-semibold">Propiedades Airbnb</h1>
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
                Distribución de Propiedades Airbnb
              </CardTitle>
              <CardDescription>Tipos de propiedades disponibles en la plataforma Airbnb en Mérida</CardDescription>
            </CardHeader>
            <CardContent>
              <div style={{ height: "500px" }}>
                <AirbnbChart />
              </div>
            </CardContent>
          </Card>

          {/* KPIs */}
          <div className="grid gap-4 md:grid-cols-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Total Propiedades</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-blue-600">2,847</div>
                <div className="flex items-center text-xs text-green-600">
                  <TrendingUp className="h-3 w-3 mr-1" />
                  +15.3% vs 2024
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Ocupación Promedio</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">68.5%</div>
                <div className="text-xs text-muted-foreground">Marzo 2025</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Tarifa Promedio</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">$85</div>
                <div className="text-xs text-muted-foreground">USD por noche</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Ingresos Estimados</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">$5.2M</div>
                <div className="text-xs text-muted-foreground">USD mensuales</div>
              </CardContent>
            </Card>
          </div>

          {/* Análisis detallado */}
          <div className="grid gap-6 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Distribución por Zona</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Centro Histórico</span>
                    <Badge variant="secondary">35%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Montejo</span>
                    <Badge variant="secondary">22%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">García Ginerés</span>
                    <Badge variant="secondary">18%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Itzimná</span>
                    <Badge variant="secondary">12%</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Otras zonas</span>
                    <Badge variant="secondary">13%</Badge>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-base">Rendimiento por Tipo</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Casa completa</span>
                    <Badge variant="secondary" className="text-green-600">
                      $120/noche
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Departamento</span>
                    <Badge variant="secondary" className="text-blue-600">
                      $85/noche
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Habitación privada</span>
                    <Badge variant="secondary" className="text-amber-600">
                      $45/noche
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Habitación compartida</span>
                    <Badge variant="secondary" className="text-gray-600">
                      $25/noche
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Información adicional */}
          <Card>
            <CardHeader>
              <CardTitle className="text-base">Análisis del Mercado Airbnb</CardTitle>
              <CardDescription>Tendencias y oportunidades en el sector</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <h4 className="font-medium text-blue-600">Fortalezas</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Patrimonio arquitectónico único</li>
                    <li>• Experiencias culturales auténticas</li>
                    <li>• Gastronomía local reconocida</li>
                    <li>• Seguridad para turistas</li>
                    <li>• Precios competitivos</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-green-600">Oportunidades</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Turismo de bienestar y retiro</li>
                    <li>• Nómadas digitales</li>
                    <li>• Estancias largas (1+ mes)</li>
                    <li>• Experiencias personalizadas</li>
                    <li>• Turismo sustentable</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-amber-600">Desafíos</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Regulación municipal</li>
                    <li>• Competencia con hoteles</li>
                    <li>• Mantenimiento de propiedades</li>
                    <li>• Estacionalidad del turismo</li>
                    <li>• Calidad del servicio</li>
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
