"use client"

import { useEffect, useState } from "react"
import { useRouter } from "next/navigation"
import { SidebarProvider, SidebarInset, SidebarTrigger } from "@/components/ui/sidebar"
import { AppSidebar } from "@/components/app-sidebar"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { InflationChart } from "@/components/charts/inflation-chart"
import { DataUploadModal } from "@/components/data-upload-modal"
import { TrendingUp, Upload, Download, RefreshCw, Calendar, TrendingDown } from "lucide-react"

export default function InflacionPage() {
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
              <TrendingUp className="h-5 w-5 text-red-600" />
              <h1 className="text-lg font-semibold">Inflación INPC</h1>
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
                <TrendingUp className="h-5 w-5" />
                Índice Nacional de Precios al Consumidor - Mérida
              </CardTitle>
              <CardDescription>Evolución mensual de la inflación anual en la ciudad de Mérida</CardDescription>
            </CardHeader>
            <CardContent>
              <div style={{ height: "500px" }}>
                <InflationChart />
              </div>
            </CardContent>
          </Card>

          {/* KPIs */}
          <div className="grid gap-4 md:grid-cols-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Inflación Anual</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-red-600">4.8%</div>
                <div className="flex items-center text-xs text-green-600">
                  <TrendingDown className="h-3 w-3 mr-1" />
                  -0.3% vs mes anterior
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Inflación Mensual</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">0.2%</div>
                <div className="text-xs text-muted-foreground">Marzo 2025</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Meta Banco de México</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">3.0%</div>
                <div className="text-xs text-red-600">+1.8 puntos arriba</div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Inflación Subyacente</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">4.2%</div>
                <div className="text-xs text-muted-foreground">Sin volátiles</div>
              </CardContent>
            </Card>
          </div>

          {/* Análisis detallado */}
          <div className="grid gap-6 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Inflación por Categoría</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Alimentos y Bebidas</span>
                    <Badge variant="secondary" className="text-red-600">
                      6.2%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Vivienda</span>
                    <Badge variant="secondary" className="text-amber-600">
                      4.8%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Transporte</span>
                    <Badge variant="secondary" className="text-red-600">
                      5.1%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Educación y Esparcimiento</span>
                    <Badge variant="secondary" className="text-blue-600">
                      3.2%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Salud y Cuidado Personal</span>
                    <Badge variant="secondary" className="text-green-600">
                      2.8%
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-base">Comparativo Regional</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Mérida</span>
                    <Badge variant="secondary" className="text-red-600">
                      4.8%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Nacional</span>
                    <Badge variant="secondary" className="text-red-600">
                      4.5%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Cancún</span>
                    <Badge variant="secondary" className="text-red-600">
                      5.2%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Guadalajara</span>
                    <Badge variant="secondary" className="text-amber-600">
                      4.1%
                    </Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm font-medium">Monterrey</span>
                    <Badge variant="secondary" className="text-amber-600">
                      4.3%
                    </Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Información adicional */}
          <Card>
            <CardHeader>
              <CardTitle className="text-base">Análisis de Inflación</CardTitle>
              <CardDescription>Factores que influyen en el comportamiento de precios</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <h4 className="font-medium text-red-600">Presiones Inflacionarias</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Aumento en precios de energéticos</li>
                    <li>• Costos de transporte</li>
                    <li>• Precios internacionales de alimentos</li>
                    <li>• Depreciación del peso</li>
                    <li>• Demanda turística elevada</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-blue-600">Factores Moderadores</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Política monetaria restrictiva</li>
                    <li>• Expectativas de inflación ancladas</li>
                    <li>• Competencia en servicios</li>
                    <li>• Eficiencia en cadenas de suministro</li>
                    <li>• Subsidios gubernamentales</li>
                  </ul>
                </div>
                <div className="space-y-2">
                  <h4 className="font-medium text-green-600">Perspectivas</h4>
                  <ul className="text-sm text-muted-foreground space-y-1">
                    <li>• Convergencia gradual a la meta</li>
                    <li>• Estabilización en 2025</li>
                    <li>• Menor volatilidad esperada</li>
                    <li>• Fortalecimiento del peso</li>
                    <li>• Normalización post-pandemia</li>
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
