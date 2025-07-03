"use client"

import { useEffect, useState, useMemo } from "react"
import { useChart } from "@/hooks/use-chart"
import { DatabaseService } from "@/lib/database-service"

export function AirbnbChart() {
  const [dataCount, setDataCount] = useState(0)
  const [chartData, setChartData] = useState<{ labels: string[]; data: number[] }>({
    labels: [],
    data: [],
  })

  const processData = () => {
    const chartData = DatabaseService.getChartData("airbnb")
    setDataCount(chartData.length)

    if (chartData.length > 0) {
      const labels = chartData.map((item) => item.tipo)
      const data = chartData.map((item) => item.cantidad)
      setChartData({ labels, data })
    } else {
      // Datos por defecto
      setChartData({
        labels: ["Entire home/apt", "Private room", "Shared room", "Hotel room"],
        data: [45, 32, 18, 5],
      })
    }
  }

  const chartConfig = useMemo(
    () => ({
      type: "doughnut" as const,
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "Propiedades Airbnb",
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
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 300,
        },
        plugins: {
          legend: {
            position: "bottom" as const,
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
      if (event.detail.type === "airbnb") {
        processData()
      }
    }

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
