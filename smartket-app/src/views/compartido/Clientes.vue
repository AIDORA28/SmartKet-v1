<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
            <Users :size="28" class="text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-black text-gray-900">Gestión de Clientes</h1>
            <p class="text-sm text-gray-500 font-semibold">Base de datos • {{ customers.length }} clientes registrados</p>
          </div>
        </div>

        <button 
          @click="openCreateModal"
          class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 transition-all hover:scale-105 flex items-center gap-2"
        >
          <UserPlus :size="20" />
          Nuevo Cliente
        </button>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1800px] mx-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center">
              <Users :size="24" class="text-indigo-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total Clientes</p>
          <h3 class="text-3xl font-black text-gray-900">{{ customers.length }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
              <UserCheck :size="24" class="text-emerald-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Clientes Activos</p>
          <h3 class="text-3xl font-black text-gray-900">{{ activeCustomers }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
              <Star :size="24" class="text-blue-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Clientes VIP</p>
          <h3 class="text-3xl font-black text-gray-900">{{ vipCustomers }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
              <TrendingUp :size="24" class="text-purple-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Nuevos (Este Mes)</p>
          <h3 class="text-3xl font-black text-gray-900">{{ newCustomersThisMonth }}</h3>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
          <div class="flex-1 relative">
            <Search :size="20" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Buscar por nombre, teléfono o email..."
              class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-semibold"
            />
          </div>

          <select 
            v-model="filterSegment"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-bold"
          >
            <option value="">Todos los segmentos</option>
            <option value="regular">Regular</option>
            <option value="frecuente">Frecuente</option>
            <option value="vip">VIP</option>
          </select>

          <select 
            v-model="filterStatus"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-bold"
          >
            <option value="">Todos los estados</option>
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
          </select>
        </div>
      </div>

      <!-- Customers Table -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Contacto</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Segmento</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Compras</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Total Gastado</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Última Visita</th>
                <th class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr 
                v-for="customer in filteredCustomers" 
                :key="customer.id"
                class="hover:bg-gray-50 transition-colors cursor-pointer"
                @click="viewCustomer(customer)"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-black">
                      {{ customer.name.charAt(0) }}
                    </div>
                    <div>
                      <h4 class="font-bold text-gray-900">{{ customer.name }}</h4>
                      <p class="text-xs text-gray-500">ID: {{ customer.id }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-700">{{ customer.phone }}</p>
                  <p class="text-xs text-gray-500">{{ customer.email }}</p>
                </td>
                <td class="px-6 py-4">
                  <span :class="[
                    'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold',
                    customer.segment === 'vip' ? 'bg-purple-100 text-purple-700' :
                    customer.segment === 'frecuente' ? 'bg-blue-100 text-blue-700' :
                    'bg-gray-100 text-gray-700'
                  ]">
                    {{ customer.segment === 'vip' ? '⭐ VIP' : customer.segment === 'frecuente' ? '🔥 Frecuente' : '👤 Regular' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-bold text-gray-900">{{ customer.totalOrders }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-black text-gray-900">S/ {{ customer.totalSpent.toFixed(2) }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm text-gray-600">{{ customer.lastVisit }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-2">
                    <button 
                      @click.stop="viewCustomer(customer)"
                      class="p-2 hover:bg-indigo-50 rounded-lg transition-colors"
                      title="Ver perfil"
                    >
                      <Eye :size="18" class="text-indigo-600" />
                    </button>
                    <button 
                      @click.stop="openEditModal(customer)"
                      class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
                      title="Editar"
                    >
                      <Pencil :size="18" class="text-gray-600" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty State -->
          <div v-if="filteredCustomers.length === 0" class="text-center py-16">
            <Users :size="64" class="text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500 font-bold mb-2">No se encontraron clientes</p>
            <button 
              @click="searchQuery = ''; filterSegment = ''; filterStatus = ''"
              class="text-indigo-600 hover:text-indigo-700 font-bold text-sm"
            >
              Limpiar filtros
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Customer Modal -->
    <transition name="modal">
      <div v-if="showCustomerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl">
          <h3 class="text-2xl font-black text-gray-900 mb-6">
            {{ editingCustomer ? 'Editar Cliente' : 'Nuevo Cliente' }}
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Nombre Completo *</label>
              <input 
                v-model="formCustomer.name"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-semibold"
                placeholder="Juan Pérez"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Teléfono *</label>
              <input 
                v-model="formCustomer.phone"
                type="tel" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-semibold"
                placeholder="999 888 777"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Email</label>
              <input 
                v-model="formCustomer.email"
                type="email" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                placeholder="cliente@email.com"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Dirección</label>
              <textarea 
                v-model="formCustomer.address"
                rows="2"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0"
                placeholder="Av. Principal 123"
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Tipo de Documento</label>
              <select 
                v-model="formCustomer.docType"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-bold"
              >
                <option value="dni">DNI</option>
                <option value="ruc">RUC</option>
                <option value="ce">Carné de Extranjería</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Número de Documento</label>
              <input 
                v-model="formCustomer.docNumber"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-0 font-mono font-bold"
                placeholder="12345678"
              />
            </div>
          </div>

          <div class="flex gap-3">
            <button 
              @click="closeCustomerModal"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="saveCustomer"
              class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-colors"
            >
              {{ editingCustomer ? 'Guardar Cambios' : 'Crear Cliente' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Customer Profile Modal -->
    <transition name="modal">
      <div v-if="showProfileModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full shadow-2xl my-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-black text-2xl">
                {{ selectedCustomer?.name.charAt(0) }}
              </div>
              <div>
                <h3 class="text-2xl font-black text-gray-900">{{ selectedCustomer?.name }}</h3>
                <p class="text-gray-600">{{ selectedCustomer?.phone }} • {{ selectedCustomer?.email }}</p>
              </div>
            </div>
            <div class="flex gap-2">
              <button 
                @click="newSaleForCustomer(selectedCustomer)"
                class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-colors flex items-center gap-2"
              >
                <ShoppingBag :size="18" />
                Nueva Venta
              </button>
              <button 
                @click="showProfileModal = false"
                class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition-colors"
              >
                <X :size="20" class="text-gray-600" />
              </button>
            </div>
          </div>

          <!-- Customer Stats -->
          <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-emerald-50 rounded-xl p-4">
              <p class="text-xs text-emerald-700 font-bold uppercase mb-1">Total Compras</p>
              <p class="text-3xl font-black text-emerald-900">{{ selectedCustomer?.totalOrders }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4">
              <p class="text-xs text-blue-700 font-bold uppercase mb-1">Total Gastado</p>
              <p class="text-3xl font-black text-blue-900">S/ {{ selectedCustomer?.totalSpent.toFixed(2) }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4">
              <p class="text-xs text-purple-700 font-bold uppercase mb-1">Ticket Promedio</p>
              <p class="text-3xl font-black text-purple-900">S/ {{ selectedCustomer ? (selectedCustomer.totalSpent / selectedCustomer.totalOrders).toFixed(2) : '0.00' }}</p>
            </div>
          </div>

          <!-- Tabs -->
          <div class="border-b border-gray-200 mb-6">
            <div class="flex gap-4">
              <button 
                @click="profileTab = 'history'"
                :class="[
                  'pb-3 px-2 font-bold text-sm border-b-2 transition-colors',
                  profileTab === 'history' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600'
                ]"
              >
                Historial de Compras
              </button>
              <button 
                @click="profileTab = 'info'"
                :class="[
                  'pb-3 px-2 font-bold text-sm border-b-2 transition-colors',
                  profileTab === 'info' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600'
                ]"
              >
                Información
              </button>
            </div>
          </div>

          <!-- Purchase History Tab -->
          <div v-if="profileTab === 'history'" class="space-y-3 max-h-96 overflow-y-auto">
            <div 
              v-for="purchase in mockPurchases" 
              :key="purchase.id"
              class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl"
            >
              <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center">
                <ShoppingBag :size="20" class="text-indigo-600" />
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                  <h4 class="font-bold text-gray-900">Venta #{{ purchase.id }}</h4>
                  <span class="text-lg font-black text-gray-900">S/ {{ purchase.total.toFixed(2) }}</span>
                </div>
                <p class="text-sm text-gray-600">{{ purchase.items }} productos • {{ purchase.date }}</p>
              </div>
            </div>
          </div>

          <!-- Info Tab -->
          <div v-if="profileTab === 'info'" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Dirección</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedCustomer?.address || 'No registrada' }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Documento</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedCustomer?.docType?.toUpperCase() }}: {{ selectedCustomer?.docNumber || 'No registrado' }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Segmento</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedCustomer?.segment }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Última Visita</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedCustomer?.lastVisit }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import {
  Users,
  UserPlus,
  UserCheck,
  Star,
  TrendingUp,
  Search,
  Eye,
  Pencil,
  X,
  ShoppingBag
} from 'lucide-vue-next'

const router = useRouter()

// State
const searchQuery = ref('')
const filterSegment = ref('')
const filterStatus = ref('')
const showCustomerModal = ref(false)
const showProfileModal = ref(false)
const profileTab = ref('history')
const editingCustomer = ref(null)
const selectedCustomer = ref(null)

// Form
const formCustomer = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
  docType: 'dni',
  docNumber: ''
})

// Mock Data
const customers = ref([
  { id: 1, name: 'Juan Pérez García', phone: '999 888 777', email: 'juan@email.com', segment: 'vip', totalOrders: 45, totalSpent: 2340.50, lastVisit: 'Hace 2 días', address: 'Av. Principal 123', docType: 'dni', docNumber: '12345678', status: 'active' },
  { id: 2, name: 'María López Silva', phone: '988 777 666', email: 'maria@email.com', segment: 'frecuente', totalOrders: 28, totalSpent: 1456.00, lastVisit: 'Hace 1 día', address: 'Jr. Los Pinos 456', docType: 'dni', docNumber: '87654321', status: 'active' },
  { id: 3, name: 'Carlos Rodríguez', phone: '977 666 555', email: 'carlos@email.com', segment: 'regular', totalOrders: 12, totalSpent: 589.00, lastVisit: 'Hace 5 días', address: 'Calle Luna 789', docType: 'dni', docNumber: '23456789', status: 'active' },
  { id: 4, name: 'Ana Torres Vega', phone: '966 555 444', email: 'ana@email.com', segment: 'vip', totalOrders: 52, totalSpent: 2890.75, lastVisit: 'Hoy', address: 'Av. Sol 321', docType: 'dni', docNumber: '34567890', status: 'active' },
  { id: 5, name: 'Roberto Silva', phone: '955 444 333', email: 'roberto@email.com', segment: 'frecuente', totalOrders: 22, totalSpent: 1124.50, lastVisit: 'Hace 3 días', address: 'Jr. Flores 654', docType: 'dni', docNumber: '45678901', status: 'active' },
  { id: 6, name: 'Patricia Gómez', phone: '944 333 222', email: 'patricia@email.com', segment: 'regular', totalOrders: 8, totalSpent: 345.00, lastVisit: 'Hace 7 días', address: 'Calle Mar 987', docType: 'dni', docNumber: '56789012', status: 'inactive' },
])

const mockPurchases = [
  { id: 10234, total: 45.50, items: 3, date: 'Hace 2 días' },
  { id: 10198, total: 78.20, items: 5, date: 'Hace 5 días' },
  { id: 10156, total: 32.00, items: 2, date: 'Hace 8 días' },
  { id: 10089, total: 56.90, items: 4, date: 'Hace 12 días' },
]

// Computed
const filteredCustomers = computed(() => {
  let filtered = customers.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(c => 
      c.name.toLowerCase().includes(query) || 
      c.phone.includes(query) ||
      c.email.toLowerCase().includes(query)
    )
  }

  if (filterSegment.value) {
    filtered = filtered.filter(c => c.segment === filterSegment.value)
  }

  if (filterStatus.value) {
    filtered = filtered.filter(c => c.status === filterStatus.value)
  }

  return filtered
})

const activeCustomers = computed(() => customers.value.filter(c => c.status === 'active').length)
const vipCustomers = computed(() => customers.value.filter(c => c.segment === 'vip').length)
const newCustomersThisMonth = computed(() => 12) // Mock

// Methods
const openCreateModal = () => {
  editingCustomer.value = null
  formCustomer.value = {
    name: '',
    phone: '',
    email: '',
    address: '',
    docType: 'dni',
    docNumber: ''
  }
  showCustomerModal.value = true
}

const openEditModal = (customer) => {
  editingCustomer.value = customer
  formCustomer.value = { ...customer }
  showCustomerModal.value = true
}

const closeCustomerModal = () => {
  showCustomerModal.value = false
  editingCustomer.value = null
}

const saveCustomer = () => {
  if (!formCustomer.value.name || !formCustomer.value.phone) {
    alert('Completa los campos requeridos')
    return
  }

  if (editingCustomer.value) {
    // Update existing
    const index = customers.value.findIndex(c => c.id === editingCustomer.value.id)
    if (index > -1) {
      customers.value[index] = { ...customers.value[index], ...formCustomer.value }
    }
  } else {
    // Create new
    const newCustomer = {
      ...formCustomer.value,
      id: customers.value.length + 1,
      segment: 'regular',
      totalOrders: 0,
      totalSpent: 0,
      lastVisit: 'Nunca',
      status: 'active'
    }
    customers.value.push(newCustomer)
  }
  
  closeCustomerModal()
}

const viewCustomer = (customer) => {
  selectedCustomer.value = customer
  profileTab.value = 'history'
  showProfileModal.value = true
}

const newSaleForCustomer = (customer) => {
  // Navegar a POS con cliente preseleccionado
  router.push({ 
    name: 'ventas', 
    query: { customer: customer.name }
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

* {
  font-family: 'Inter', sans-serif;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .bg-white,
.modal-leave-to .bg-white {
  transform: scale(0.9);
}
</style>
