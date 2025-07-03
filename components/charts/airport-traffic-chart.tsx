"use client"

import { useEffect, useState, useMemo } from "react"
import { useChart } from "@/hooks/use-chart"
import { DatabaseService } from "@/lib/database-service"

export function AirportTrafficChart() {
  const [dataCount, setDataCount] = useState(0)
  const [chartData, setChartData] = useState<{
    labels: string[]
    nacionales: number[]
    internacionales: number[]
  }>({
    labels: [],
    nacionales: [],
    internacionales: [],
  })

  const processData = () => {
    const chartData = DatabaseService.getChartData("airport")
    setDataCount(chartData.length)

    if (chartData.length > 0) {
      const labels = chartData.map((item) => item.mes)
      const nacionales = chartData.map((item) => item.nacionales)
      const internacionales = chartData.map((item) => item.internacionales)
      setChartData({ labels, nacionales, internacionales })
    } else {
      // Datos por defecto
      setChartData({
        labels: ["Ene", "Feb", "Mar"],
        nacionales: [180000, 195000, 191730],
        internacionales: [45000, 48000, 53950],
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
            label: "Pasajeros Nacionales",
            data: chartData.nacionales,
            backgroundColor: "rgba(59, 130, 246, 0.8)",
            borderColor: "rgb(59, 130, 246)",
            borderWidth: 1,
          },
          {
            label: "Pasajeros Internacionales",
            data: chartData.internacionales,
            backgroundColor: "rgba(16, 185, 129, 0.8)",
            borderColor: "rgb(16, 185, 129)",
            borderWidth: 1,
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
          x: {
            stacked: true,
          },
          y: {
            stacked: true,
            beginAtZero: true,
            title: {
              display: true,
              text: "Número de pasajeros",
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
      if (event.detail.type === "aeropuerto") {
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
