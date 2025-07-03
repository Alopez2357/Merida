"use client"

import { useEffect, useState, useMemo } from "react"
import { useChart } from "@/hooks/use-chart"
import { DatabaseService } from "@/lib/database-service"

export function HotelOccupancyChart() {
  const [dataCount, setDataCount] = useState(0)
  const [chartData, setChartData] = useState<{
    labels: string[]
    data2024: (number | null)[]
    data2025: (number | null)[]
  }>({
    labels: [],
    data2024: [],
    data2025: [],
  })

  const processData = () => {
    const chartData = DatabaseService.getChartData("hotel")
    setDataCount(chartData.length)

    if (chartData.length > 0) {
      const labels = chartData.map((item) => item.mes)
      const data2024 = chartData.map((item) => item.ocupacion2024)
      const data2025 = chartData.map((item) => item.ocupacion2025)
      setChartData({ labels, data2024, data2025 })
    } else {
      // Datos por defecto
      setChartData({
        labels: ["Ene", "Feb", "Mar"],
        data2024: [65, 68, 72],
        data2025: [70, 73, 78.5],
      })
    }
  }

  const chartConfig = useMemo(
    () => ({
      type: "line" as const,
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: "2024",
            data: chartData.data2024,
            borderColor: "rgb(156, 163, 175)",
            backgroundColor: "rgba(156, 163, 175, 0.1)",
            tension: 0.4,
          },
          {
            label: "2025",
            data: chartData.data2025,
            borderColor: "rgb(59, 130, 246)",
            backgroundColor: "rgba(59, 130, 246, 0.1)",
            tension: 0.4,
            fill: false,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 300,
        },
        scales: {
          y: {
            beginAtZero: false,
            min: 50,
            max: 100,
            title: {
              display: true,
              text: "Ocupación (%)",
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
      if (event.detail.type === "hoteles") {
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
