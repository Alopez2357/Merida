"use client"

import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarSeparator,
} from "@/components/ui/sidebar"
import { BarChart3, TrendingUp, Users, Plane, Building2, DollarSign, MapPin, LogOut, Home } from "lucide-react"
import { Button } from "@/components/ui/button"
import { useRouter, usePathname } from "next/navigation"

const menuItems = [
  {
    title: "Dashboard Principal",
    url: "/dashboard",
    icon: Home,
  },
  {
    title: "Ocupación y Empleo",
    url: "/dashboard/empleo",
    icon: Users,
  },
  {
    title: "Inflación",
    url: "/dashboard/inflacion",
    icon: TrendingUp,
  },
  {
    title: "Ingresos",
    url: "/dashboard/ingresos",
    icon: DollarSign,
  },
  {
    title: "Ocupación Hotelera",
    url: "/dashboard/hoteles",
    icon: Building2,
  },
  {
    title: "Tráfico Aeropuerto",
    url: "/dashboard/aeropuerto",
    icon: Plane,
  },
  {
    title: "Propiedades Airbnb",
    url: "/dashboard/airbnb",
    icon: Building2,
  },
  {
    title: "Geolocalización",
    url: "/dashboard/geolocalizacion",
    icon: MapPin,
  },
]

export function AppSidebar() {
  const router = useRouter()
  const pathname = usePathname()

  const handleLogout = () => {
    localStorage.removeItem("isAuthenticated")
    router.push("/")
  }

  return (
    <Sidebar collapsible="icon" className="group-data-[collapsible=icon]:w-12">
      <SidebarHeader className="p-4">
        <div className="flex items-center gap-2">
          <div className="bg-blue-600 p-2 rounded-lg">
            <BarChart3 className="h-6 w-6 text-white" />
          </div>
          <div className="group-data-[collapsible=icon]:hidden">
            <h2 className="text-lg font-semibold">Dashboard Mérida</h2>
            <p className="text-sm text-muted-foreground">Indicadores 2025</p>
          </div>
        </div>
      </SidebarHeader>

      <SidebarSeparator />

      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel className="group-data-[collapsible=icon]:hidden">Navegación</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {menuItems.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild isActive={pathname === item.url} tooltip={item.title}>
                    <a href={item.url}>
                      <item.icon className="h-4 w-4" />
                      <span>{item.title}</span>
                    </a>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>

      <SidebarFooter className="p-4">
        <Button
          variant="outline"
          className="w-full justify-start bg-transparent group-data-[collapsible=icon]:justify-center group-data-[collapsible=icon]:px-2"
          onClick={handleLogout}
        >
          <LogOut className="h-4 w-4 group-data-[collapsible=icon]:mr-0 mr-2" />
          <span className="group-data-[collapsible=icon]:hidden">Cerrar Sesión</span>
        </Button>
      </SidebarFooter>
    </Sidebar>
  )
}
