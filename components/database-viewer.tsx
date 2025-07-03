"use client"

import { useState, useEffect } from "react"
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from "@/components/ui/dialog"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { DatabaseService } from "@/lib/database-service"
import { Database, Download, Trash2, RefreshCw } from "lucide-react"
import { useToast } from "@/hooks/use-toast"

interface DatabaseViewerProps {
  open: boolean
  onOpenChange: (open: boolean) => void
}

const tableNames = [
  { key: "airbnb_listings", label: "Propiedades Airbnb", description: "Listados de propiedades en Airbnb" },
  { key: "ocupacion_hotelera", label: "Ocupación Hotelera", description: "Porcentajes de ocupación mensual" },
  { key: "empleo_merida", label: "Empleo Mérida", description: "Estadísticas de empleo por categoría" },
  { key: "pernoctas", label: "Pernoctaciones", description: "Turistas nacionales e internacionales" },
  { key: "arribos_aeropuerto", label: "Arribos Aeropuerto", description: "Pasajeros por tipo y mes" },
  { key: "inpc_merida", label: "INPC Mérida", description: "Índice de precios al consumidor" },
] as const

export function DatabaseViewer({ open, onOpenChange }: DatabaseViewerProps) {
  const [selectedTable, setSelectedTable] = useState<string>("airbnb_listings")
  const [tableData, setTableData] = useState<any[]>([])
  const [tableStats, setTableStats] = useState<{ [key: string]: { count: number; lastUpdate: string | null } }>({})
  const { toast } = useToast()

  useEffect(() => {
    if (open) {
      loadTableStats()
      loadTableData(selectedTable)
    }
  }, [open, selectedTable])

  const loadTableStats = () => {
    const stats: { [key: string]: { count: number; lastUpdate: string | null } } = {}
    tableNames.forEach((table) => {
      stats[table.key] = DatabaseService.getTableStats(table.key as any)
    })
    setTableStats(stats)
  }

  const loadTableData = (tableName: string) => {
    const data = DatabaseService.getTable(tableName as any)
    setTableData(data.slice(0, 100)) // Mostrar solo los primeros 100 registros
  }

  const handleExportTable = (tableName: string) => {
    try {
      const csvContent = DatabaseService.exportTableToCSV(tableName as any)
      if (!csvContent) {
        toast({
          title: "Error",
          description: "No hay datos para exportar",
          variant: "destructive",
        })
        return
      }

      const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" })
      const link = document.createElement("a")
      const url = URL.createObjectURL(blob)
      link.setAttribute("href", url)
      link.setAttribute("download", `${tableName}_export.csv`)
      link.style.visibility = "hidden"
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)

      toast({
        title: "¡Éxito!",
        description: `Tabla ${tableName} exportada correctamente`,
      })
    } catch (error) {
      toast({
        title: "Error",
        description: "Error al exportar la tabla",
        variant: "destructive",
      })
    }
  }

  const handleClearDatabase = () => {
    if (confirm("¿Estás seguro de que quieres limpiar toda la base de datos? Esta acción no se puede deshacer.")) {
      DatabaseService.clearDatabase()
      loadTableStats()
      loadTableData(selectedTable)
      toast({
        title: "Base de datos limpiada",
        description: "Todos los datos han sido eliminados",
      })
    }
  }

  const renderTableContent = () => {
    if (tableData.length === 0) {
      return (
        <div className="text-center py-8 text-muted-foreground">
          <Database className="h-8 w-8 mx-auto mb-2 opacity-50" />
          <p>No hay datos en esta tabla</p>
        </div>
      )
    }

    const columns = Object.keys(tableData[0])

    return (
      <div className="overflow-auto max-h-96">
        <Table>
          <TableHeader>
            <TableRow>
              {columns.map((column) => (
                <TableHead key={column} className="whitespace-nowrap">
                  {column.replace(/_/g, " ").toUpperCase()}
                </TableHead>
              ))}
            </TableRow>
          </TableHeader>
          <TableBody>
            {tableData.map((row, index) => (
              <TableRow key={index}>
                {columns.map((column) => (
                  <TableCell key={column} className="whitespace-nowrap">
                    {typeof row[column] === "number" ? row[column].toLocaleString() : row[column]?.toString() || "-"}
                  </TableCell>
                ))}
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>
    )
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-6xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Database className="h-5 w-5" />
            Visor de Base de Datos
          </DialogTitle>
          <DialogDescription>Explora y gestiona los datos almacenados en el sistema</DialogDescription>
        </DialogHeader>

        <div className="space-y-6">
          {/* Resumen de tablas */}
          <div className="grid gap-4 md:grid-cols-3">
            {tableNames.map((table) => (
              <Card
                key={table.key}
                className="cursor-pointer hover:bg-accent/50"
                onClick={() => setSelectedTable(table.key)}
              >
                <CardHeader className="pb-2">
                  <CardTitle className="text-sm flex items-center justify-between">
                    {table.label}
                    <Badge variant={selectedTable === table.key ? "default" : "secondary"}>
                      {tableStats[table.key]?.count || 0}
                    </Badge>
                  </CardTitle>
                  <CardDescription className="text-xs">{table.description}</CardDescription>
                </CardHeader>
                <CardContent className="pt-0">
                  {tableStats[table.key]?.lastUpdate && (
                    <p className="text-xs text-muted-foreground">
                      Actualizado: {new Date(tableStats[table.key].lastUpdate!).toLocaleDateString()}
                    </p>
                  )}
                </CardContent>
              </Card>
            ))}
          </div>

          {/* Controles */}
          <div className="flex items-center justify-between">
            <h3 className="text-lg font-semibold">{tableNames.find((t) => t.key === selectedTable)?.label}</h3>
            <div className="flex gap-2">
              <Button variant="outline" size="sm" onClick={() => loadTableStats()}>
                <RefreshCw className="h-4 w-4 mr-2" />
                Actualizar
              </Button>
              <Button variant="outline" size="sm" onClick={() => handleExportTable(selectedTable)}>
                <Download className="h-4 w-4 mr-2" />
                Exportar CSV
              </Button>
              <Button variant="destructive" size="sm" onClick={handleClearDatabase}>
                <Trash2 className="h-4 w-4 mr-2" />
                Limpiar BD
              </Button>
            </div>
          </div>

          {/* Contenido de la tabla */}
          <Card>
            <CardHeader>
              <CardTitle className="text-base">
                Datos de {tableNames.find((t) => t.key === selectedTable)?.label}
              </CardTitle>
              <CardDescription>
                Mostrando {Math.min(tableData.length, 100)} de {tableStats[selectedTable]?.count || 0} registros
              </CardDescription>
            </CardHeader>
            <CardContent>{renderTableContent()}</CardContent>
          </Card>
        </div>
      </DialogContent>
    </Dialog>
  )
}
