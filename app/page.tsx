"use client"

import type React from "react"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Eye, EyeOff, BarChart3, TrendingUp, Users } from "lucide-react"
import { useRouter } from "next/navigation"

export default function LoginPage() {
  const [showPassword, setShowPassword] = useState(false)
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [isLoading, setIsLoading] = useState(false)
  const router = useRouter()

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)

    // Simular autenticación
    setTimeout(() => {
      if (email && password) {
        localStorage.setItem("isAuthenticated", "true")
        router.push("/dashboard")
      }
      setIsLoading(false)
    }, 1000)
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <div className="flex justify-center mb-4">
            <div className="bg-blue-600 p-3 rounded-full">
              <BarChart3 className="h-8 w-8 text-white" />
            </div>
          </div>
          <h1 className="text-3xl font-bold text-gray-900">Dashboard Mérida</h1>
          <p className="text-gray-600 mt-2">Indicadores Económicos y Turísticos</p>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Iniciar Sesión</CardTitle>
            <CardDescription>Ingresa tus credenciales para acceder al dashboard</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleLogin} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="email">Correo Electrónico</Label>
                <Input
                  id="email"
                  type="email"
                  placeholder="usuario@ejemplo.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="password">Contraseña</Label>
                <div className="relative">
                  <Input
                    id="password"
                    type={showPassword ? "text" : "password"}
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                  />
                  <Button
                    type="button"
                    variant="ghost"
                    size="sm"
                    className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                    onClick={() => setShowPassword(!showPassword)}
                  >
                    {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                  </Button>
                </div>
              </div>

              <Button type="submit" className="w-full" disabled={isLoading}>
                {isLoading ? "Iniciando sesión..." : "Iniciar Sesión"}
              </Button>
            </form>

            <div className="mt-6 text-center text-sm text-gray-600">
              <p>Credenciales de prueba:</p>
              <p>Email: admin@merida.gob.mx</p>
              <p>Contraseña: cualquier texto</p>
            </div>
          </CardContent>
        </Card>

        <div className="mt-8 grid grid-cols-2 gap-4 text-center">
          <div className="bg-white/50 backdrop-blur-sm rounded-lg p-4">
            <TrendingUp className="h-6 w-6 text-blue-600 mx-auto mb-2" />
            <p className="text-sm font-medium">Indicadores</p>
            <p className="text-xs text-gray-600">Económicos</p>
          </div>
          <div className="bg-white/50 backdrop-blur-sm rounded-lg p-4">
            <Users className="h-6 w-6 text-green-600 mx-auto mb-2" />
            <p className="text-sm font-medium">Datos</p>
            <p className="text-xs text-gray-600">Turísticos</p>
          </div>
        </div>
      </div>
    </div>
  )
}
