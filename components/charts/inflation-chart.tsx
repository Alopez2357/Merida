"use client"

import { useEffect, useState, useMemo } from "react"
import { useChart } from "@/hooks/use-chart"
import { DatabaseService } from "@/lib/database-service"

export function InflationChart() {
  const [dataCount, setDataCount] = useState(0)
  const [chartData, setChartData] = useState<{ labels: string[]; data: number[] }>({
    labels: [],
    data: [],
  })

  const processData = () => {
    const chartData = DatabaseService.getChartData("inflation")
    setDataCount(chartData.length)

    if (chartData.length > 0) {
      const labels = chartData.map((item) => item.mes)
      const data = chartData.map((item) => item.inpc)
      setChartData({ labels, data })
    } else {
      // Datos por defecto
      setChartData({
        labels: ["Ene", "Feb", "Mar"],
        data: [5.2, 5.1, 4.8],
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
            label: "INPC Anual (%)",
            data: chartData.data,
            borderColor: "rgb(239, 68, 68)",
            backgroundColor: "rgba(239, 68, 68, 0.1)",
            tension: 0.4,
            fill: true,
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
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: false,
            title: {
              display: true,
              text: "Porcentaje (%)",
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
      if (event.detail.type === "inflacion") {
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
