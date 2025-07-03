"use client"

import { useEffect, useRef, useCallback } from "react"
import { Chart, type ChartConfiguration } from "chart.js/auto"

export function useChart(config: ChartConfiguration, dependencies: any[] = []) {
  const chartRef = useRef<HTMLCanvasElement>(null)
  const chartInstance = useRef<Chart | null>(null)

  const updateChart = useCallback((newConfig: ChartConfiguration) => {
    if (!chartRef.current) return

    const ctx = chartRef.current.getContext("2d")
    if (!ctx) return

    // Si no existe la gráfica, crearla
    if (!chartInstance.current) {
      chartInstance.current = new Chart(ctx, newConfig)
      return
    }

    // Actualizar datos existentes de forma suave
    const chart = chartInstance.current

    // Actualizar labels si han cambiado
    if (JSON.stringify(chart.data.labels) !== JSON.stringify(newConfig.data.labels)) {
      chart.data.labels = newConfig.data.labels
    }

    // Actualizar datasets
    newConfig.data.datasets.forEach((newDataset, index) => {
      if (chart.data.datasets[index]) {
        // Actualizar datos existentes
        chart.data.datasets[index].data = newDataset.data
        chart.data.datasets[index].label = newDataset.label
      } else {
        // Agregar nuevo dataset
        chart.data.datasets.push(newDataset)
      }
    })

    // Remover datasets extra
    if (chart.data.datasets.length > newConfig.data.datasets.length) {
      chart.data.datasets.splice(newConfig.data.datasets.length)
    }

    // Actualizar con animación suave
    chart.update("none") // Sin animación para evitar movimiento
  }, [])

  const createChart = useCallback(() => {
    updateChart(config)
  }, [config, updateChart])

  useEffect(() => {
    createChart()

    return () => {
      if (chartInstance.current) {
        chartInstance.current.destroy()
        chartInstance.current = null
      }
    }
  }, dependencies)

  return { chartRef, updateChart }
}
