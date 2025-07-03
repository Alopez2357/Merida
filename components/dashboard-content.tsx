"use client"

import { SidebarInset, SidebarTrigger } from "@/components/ui/sidebar"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { TrendingUp, TrendingDown, Users, Plane, Building2, Calendar, Activity, Upload, Database } from "lucide-react"
import { EmploymentChart } from "@/components/charts/employment-chart"
import { InflationChart } from "@/components/charts/inflation-chart"
import { HotelOccupancyChart } from "@/components/charts/hotel-occupancy-chart"
import { AirportTrafficChart } from "@/components/charts/airport-traffic-chart"
import { useState } from "react"
import { DataUploadModal } from "@/components/data-upload-modal"
import { AirbnbChart } from "@/components/charts/airbnb-chart"
import { DatabaseViewer } from "@/components/database-viewer"

const kpiData = [
  {
    title: "Tasa de Empleo",
    value: "94.2%",
    change: "+2.1%",
    trend: "up",
    icon: Users,
    description: "vs. mes anterior",
  },
  {
    title: "Inflación Anual",
    value: "4.8%",
    change: "-0.3%",
    trend: "down",
    icon: TrendingUp,
    description: "vs. año anterior",
  },
  {
    title: "Ocupación Hotelera",
    value: "78.5%",
    change: "+5.2%",
    trend: "up",
    icon: Building2,
    description: "promedio mensual",
  },
  {
    title: "Pasajeros Aeropuerto",
    value: "245,680",
    change: "+12.4%",
    trend: "up",
    icon: Plane,
    description: "marzo 2025",
  },
]

export function DashboardContent() {
  const [showUploadModal, setShowUploadModal] = useState(false)
  const [showDatabaseViewer, setShowDatabaseViewer] = useState(false)

  return (
    <SidebarInset className="flex-1">
      <header className="sticky top-0 z-10 flex h-16 shrink-0 items-center gap-2 border-b bg-background px-4">
        <SidebarTrigger className="-ml-1" />
        <div className="flex flex-1 items-center justify-between">
          <div className="flex items-center gap-2">
            <h1 className="text-lg font-semibold">Dashboard Principal</h1>
            <Badge variant="secondary">
              <Calendar className="h-3 w-3 mr-1" />
              Marzo 2025
            </Badge>
          </div>
          <div className="flex items-center gap-2">
            <Button variant="outline" size="sm" onClick={() => setShowUploadModal(true)}>
              <Upload className="h-4 w-4 mr-2" />
              Cargar Datos
            </Button>
            <Button variant="outline" size="sm" onClick={() => setShowDatabaseViewer(true)}>
              <Database className="h-4 w-4 mr-2" />
              Ver BD
            </Button>
          </div>
        </div>
      </header>

      <main className="flex-1 space-y-6 p-6 overflow-auto">
        {/* KPIs */}
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          {kpiData.map((kpi, index) => (
            <Card key={index}>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">{kpi.title}</CardTitle>
                <kpi.icon className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{kpi.value}</div>
                <div className="flex items-center text-xs text-muted-foreground">
                  {kpi.trend === "up" ? (
                    <TrendingUp className="h-3 w-3 mr-1 text-green-500" />
                  ) : (
                    <TrendingDown className="h-3 w-3 mr-1 text-red-500" />
                  )}
                  <span className={kpi.trend === "up" ? "text-green-500" : "text-red-500"}>{kpi.change}</span>
                  <span className="ml-1">{kpi.description}</span>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* Gráficas principales */}
        <div className="grid gap-6 lg:grid-cols-2">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Users className="h-5 w-5" />
                Ocupación y Empleo
              </CardTitle>
              <CardDescription>Indicadores de empleo por sector - Primer trimestre 2025</CardDescription>
            </CardHeader>
            <CardContent>
              <EmploymentChart />
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <TrendingUp className="h-5 w-5" />
                Inflación INPC
              </CardTitle>
              <CardDescription>Índice Nacional de Precios al Consumidor - Mérida</CardDescription>
            </CardHeader>
            <CardContent>
              <InflationChart />
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Building2 className="h-5 w-5" />
                Ocupación Hotelera
              </CardTitle>
              <CardDescription>Porcentaje de ocupación mensual</CardDescription>
            </CardHeader>
            <CardContent>
              <HotelOccupancyChart />
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Plane className="h-5 w-5" />
                Tráfico Aeroportuario
              </CardTitle>
              <CardDescription>Movimiento de pasajeros y operaciones</CardDescription>
            </CardHeader>
            <CardContent>
              <AirportTrafficChart />
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Building2 className="h-5 w-5" />
                Propiedades Airbnb
              </CardTitle>
              <CardDescription>Distribución de tipos de propiedades en Airbnb</CardDescription>
            </CardHeader>
            <CardContent>
              <AirbnbChart />
            </CardContent>
          </Card>
        </div>

        {/* Resumen estadístico */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Activity className="h-5 w-5" />
              Resumen Estadístico
            </CardTitle>
            <CardDescription>Principales indicadores del mes de marzo 2025</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="grid gap-4 md:grid-cols-3">
              <div className="space-y-2">
                <h4 className="font-medium">Sector Económico</h4>
                <ul className="text-sm text-muted-foreground space-y-1">
                  <li>• Servicios: 68.2% del empleo</li>
                  <li>• Comercio: 18.5% del empleo</li>
                  <li>• Industria: 13.3% del empleo</li>
                </ul>
              </div>
              <div className="space-y-2">
                <h4 className="font-medium">Turismo</h4>
                <ul className="text-sm text-muted-foreground space-y-1">
                  <li>• Turistas nacionales: 65%</li>
                  <li>• Turistas extranjeros: 35%</li>
                  <li>• Estancia promedio: 3.2 días</li>
                </ul>
              </div>
              <div className="space-y-2">
                <h4 className="font-medium">Transporte</h4>
                <ul className="text-sm text-muted-foreground space-y-1">
                  <li>• Vuelos nacionales: 78%</li>
                  <li>• Vuelos internacionales: 22%</li>
                  <li>• Operaciones diarias: 45 promedio</li>
                </ul>
              </div>
            </div>
          </CardContent>
        </Card>
      </main>

      <DataUploadModal open={showUploadModal} onOpenChange={setShowUploadModal} />
      <DatabaseViewer open={showDatabaseViewer} onOpenChange={setShowDatabaseViewer} />
    </SidebarInset>
  )
}
