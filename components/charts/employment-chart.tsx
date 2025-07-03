"use client"

import { useEffect, useState, useMemo } from "react"
import { useChart } from "@/hooks/use-chart"
import { DatabaseService } from "@/lib/database-service"

export function EmploymentChart() {
  const [dataCount, setDataCount] = useState(0)
  const [chartData, setChartData] = useState<{ labels: string[]; data: number[] }>({
    labels: [],
    data: [],
  })

  const processData = () => {
    const chartData = DatabaseService.getChartData("employment")
    setDataCount(chartData.length)

    if (chartData.length > 0) {
      const labels = chartData.map((item) => item.sector)
      const data = chartData.map((item) => item.empleados / 1000) // Convertir a miles
      setChartData({ labels, data })
    } else {
      // Datos por defecto
      setChartData({
        labels: ["Servicios", "Comercio", "Industria", "Construcción", "Agricultura"],
        data: [342, 98, 67, 45, 23],
      })
    }
  }

  const chartConfig = useMemo(
    () => ({
      type: "bar" as const,
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Empleados (miles)",
            data: chartData.data,
            backgroundColor: [
              "rgba(59, 130, 246, 0.8)",
              "rgba(16, 185, 129, 0.8)",
              "rgba(245, 158, 11, 0.8)",
              "rgba(239, 68, 68, 0.8)",
              "rgba(139, 92, 246, 0.8)",
              "rgba(236, 72, 153, 0.8)",
            ],
            borderColor: [
              "rgb(59, 130, 246)",
              "rgb(16, 185, 129)",
              "rgb(245, 158, 11)",
              "rgb(239, 68, 68)",
              "rgb(139, 92, 246)",
              "rgb(236, 72, 153)",
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 300, // Animación más rápida
        },
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: "Miles de empleados",
            },
          },
        },
      },
    }),
    [chartData],
  )

  const { chartRef, updateChart } = useChart(chartConfig, [chartData])

  useEffect(() => {
    processData()
  }, [])

  useEffect(() => {
    const handleDataUpdate = (event: CustomEvent) => {
      if (event.detail.type === "empleo") {
        processData()
      }
    }

    // Escuchar tanto el evento específico como el global
    window.addEventListener("dataUpdated", handleDataUpdate as EventListener)
    window.addEventListener("databaseUpdated", handleDataUpdate as EventListener)

    return () => {
      window.removeEventListener("dataUpdated", handleDataUpdate as EventListener)
      window.removeEventListener("databaseUpdated", handleDataUpdate as EventListener)
    }
  }, [])

  useEffect(() => {
    if (chartData.labels.length > 0) {
      updateChart(chartConfig)
    }
  }, [chartData, chartConfig, updateChart])

  return (
    <div className="space-y-2">
      <div style={{ height: "300px", position: "relative" }}>
        <canvas ref={chartRef} />
      </div>
      {dataCount > 0 && <div className="text-xs text-muted-foreground text-center">{dataCount} registros cargados</div>}
    </div>
  )
}
