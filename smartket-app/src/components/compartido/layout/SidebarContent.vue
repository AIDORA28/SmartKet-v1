<template>
  <div class="flex flex-col h-full">
    <!-- Header / Logo -->
    <div
      :class="[
        'flex items-center justify-between px-6 py-6 border-b border-white/5',
        isCollapsed && 'px-4'
      ]"
    >
      <div v-if="!isCollapsed" class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20">
          <Store :size="20" class="text-white" />
        </div>
        <div>
          <h1 class="text-white font-black text-lg tracking-tight">SmartKet</h1>
          <p class="text-gray-400 text-[10px] uppercase tracking-widest font-semibold">Enterprise</p>
        </div>
      </div>
      <button
        v-else
        class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mx-auto shadow-lg shadow-red-500/20"
      >
        <Store :size="20" class="text-white" />
      </button>

      <!-- Collapse Toggle (Desktop only) -->
      <button
        v-if="!isCollapsed"
        @click="$emit('toggle-collapse')"
        class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-all duration-200"
      >
        <ChevronLeft :size="16" />
      </button>
      <button
        v-else
        @click="$emit('toggle-collapse')"
        class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-all duration-200 mx-auto mt-4"
      >
        <ChevronRight :size="16" />
      </button>

      <!-- Close button (Mobile only) -->
      <button
        v-if="!isCollapsed"
        @click="$emit('close')"
        class="lg:hidden w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-all duration-200 flex items-center justify-center"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Navigation -->
    <nav :class="['flex-1 overflow-y-auto px-4 py-6 space-y-1', isCollapsed && 'px-2']">
      <template v-for="item in navigationItems" :key="item.name">
        <!-- Section Label -->
        <div
          v-if="item.section && !isCollapsed"
          class="px-3 pt-6 pb-2 text-[10px] uppercase tracking-widest font-black text-gray-500"
        >
          {{ item.section }}
        </div>

        <!-- Divider for collapsed -->
        <div v-if="item.section && isCollapsed" class="border-t border-white/5 my-4" />

        <!-- Navigation Item -->
        <router-link
          v-if="!item.section"
          :to="item.href"
          :class="[
            'group flex items-center gap-3 rounded-xl font-semibold transition-all duration-200 relative overflow-hidden',
            isCollapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3',
            isActive(item.href)
              ? 'bg-gradient-to-r from-red-600 to-red-500 text-white shadow-lg shadow-red-500/30'
              : 'text-gray-400 hover:text-white hover:bg-white/5'
          ]"
        >
          <!-- Shine effect on hover -->
          <div
            v-if="!isActive(item.href)"
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"
          />

          <!-- Icon -->
          <component
            :is="item.icon"
            :size="20"
            :class="[
              'flex-shrink-0 transition-transform duration-200',
              isActive(item.href) ? 'scale-110' : 'group-hover:scale-110'
            ]"
          />

          <!-- Label -->
          <span v-if="!isCollapsed" class="text-[15px]">{{ item.name }}</span>

          <!-- Badge -->
          <span
            v-if="item.badge && !isCollapsed"
            class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-yellow-400 text-gray-900"
          >
            {{ item.badge }}
          </span>

          <!-- Tooltip for collapsed state -->
          <div
            v-if="isCollapsed"
            class="fixed left-20 ml-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50"
          >
            {{ item.name }}
            <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45" />
          </div>
        </router-link>
      </template>
    </nav>

    <!-- Footer -->
    <div :class="['border-t border-white/5 p-4', isCollapsed && 'px-2']">
      <button
        @click="$emit('logout')"
        :class="[
          'group flex items-center gap-3 w-full rounded-xl font-semibold transition-all duration-200 text-gray-400 hover:text-white hover:bg-red-500/10 relative overflow-hidden',
          isCollapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'
        ]"
      >
        <LogOut
          :size="20"
          class="flex-shrink-0 transition-transform duration-200 group-hover:-translate-x-1"
        />
        <span v-if="!isCollapsed" class="text-[15px]">Cerrar Sesión</span>

        <!-- Tooltip for collapsed state -->
        <div
          v-if="isCollapsed"
          class="fixed left-20 ml-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg shadow-xl opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50"
        >
          Cerrar Sesión
          <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45" />
        </div>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import {
  LayoutDashboard,
  ShoppingBag,
  Package,
  Users,
  TrendingUp,
  FileText,
  DollarSign,
  Settings,
  LogOut,
  ChevronLeft,
  ChevronRight,
  Store,
  Briefcase,
  BarChart3,
  Boxes,
  CreditCard
} from 'lucide-vue-next'

defineProps({
  isCollapsed: Boolean,
  navigation: Array
})

defineEmits(['toggle-collapse', 'close', 'logout'])

const route = useRoute()

// Navigation structure with icons
const navigationItems = computed(() => [
  { section: 'General' },
  {
    name: 'Dashboard',
    href: '/app/dashboard',
    icon: LayoutDashboard,
    badge: null
  },
  { section: 'Operaciones' },
  {
    name: 'POS',
    href: '/app/ventas',
    icon: ShoppingBag,
    badge: null
  },
  {
    name: 'Cajas',
    href: '/app/finanzas',
    icon: CreditCard,
    badge: null
  },
  {
    name: 'Productos',
    href: '/app/productos',
    icon: Package,
    badge: null
  },
  {
    name: 'Inventario',
    href: '/app/inventario',
    icon: Boxes,
    badge: null
  },
  { section: 'Gestión' },
  {
    name: 'Clientes',
    href: '/app/clientes',
    icon: Users,
    badge: null
  },
  {
    name: 'Proveedores',
    href: '/app/proveedores',
    icon: Briefcase,
    badge: null
  },
  { section: 'Análisis' },
  {
    name: 'Reportes',
    href: '/app/reportes',
    icon: BarChart3,
    badge: null
  }
])

const isActive = (path) => {
  return route.path.startsWith(path)
}
</script>

<style scoped>
/* Custom scrollbar */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 4px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.2);
}
</style>
