<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-gradient-to-br from-orange-600 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/20">
            <Truck :size="28" class="text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-black text-gray-900">Gestión de Proveedores</h1>
            <p class="text-sm text-gray-500 font-semibold">Contactos comerciales • {{ suppliers.length }} proveedores</p>
          </div>
        </div>

        <div class="flex gap-3">
          <button 
            @click="showOrderModal = true"
            class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors flex items-center gap-2"
          >
            <FileText :size="20" />
            Nueva Orden
          </button>
          <button 
            @click="openCreateModal"
            class="px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-xl font-bold shadow-lg shadow-orange-600/30 hover:shadow-orange-600/40 transition-all hover:scale-105 flex items-center gap-2"
          >
            <Plus :size="20" />
            Nuevo Proveedor
          </button>
        </div>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1800px] mx-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-500/10 rounded-xl flex items-center justify-center">
              <Truck :size="24" class="text-orange-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total Proveedores</p>
          <h3 class="text-3xl font-black text-gray-900">{{ suppliers.length }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
              <CheckCircle :size="24" class="text-emerald-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Activos</p>
          <h3 class="text-3xl font-black text-gray-900">{{ activeSuppliers }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
              <ShoppingCart :size="24" class="text-blue-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Órdenes Pendientes</p>
          <h3 class="text-3xl font-black text-gray-900">{{ pendingOrders }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
              <DollarSign :size="24" class="text-purple-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Compras Este Mes</p>
          <h3 class="text-3xl font-black text-gray-900">S/ {{ monthlyPurchases.toFixed(2) }}</h3>
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
              placeholder="Buscar por nombre, RUC o categoría..."
              class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-semibold"
            />
          </div>

          <select 
            v-model="filterCategory"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-bold"
          >
            <option value="">Todas las categorías</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>

          <select 
            v-model="filterStatus"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-bold"
          >
            <option value="">Todos los estados</option>
            <option value="active">Activo</option>
            <option value="inactive">Inactivo</option>
          </select>
        </div>
      </div>

      <!-- Suppliers Table -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Proveedor</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Contacto</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Categoría</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Órdenes</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Total Comprado</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr 
                v-for="supplier in filteredSuppliers" 
                :key="supplier.id"
                class="hover:bg-gray-50 transition-colors cursor-pointer"
                @click="viewSupplier(supplier)"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white font-black">
                      {{ supplier.name.charAt(0) }}
                    </div>
                    <div>
                      <h4 class="font-bold text-gray-900">{{ supplier.name }}</h4>
                      <p class="text-xs text-gray-500">RUC: {{ supplier.ruc }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-700">{{ supplier.contact }}</p>
                  <p class="text-xs text-gray-500">{{ supplier.phone }}</p>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                    {{ supplier.category }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-bold text-gray-900">{{ supplier.totalOrders }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-black text-gray-900">S/ {{ supplier.totalPurchased.toFixed(2) }}</span>
                </td>
                <td class="px-6 py-4">
                  <span :class="[
                    'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold',
                    supplier.status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700'
                  ]">
                    {{ supplier.status === 'active' ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-2">
                    <button 
                      @click.stop="viewSupplier(supplier)"
                      class="p-2 hover:bg-orange-50 rounded-lg transition-colors"
                      title="Ver perfil"
                    >
                      <Eye :size="18" class="text-orange-600" />
                    </button>
                    <button 
                      @click.stop="openEditModal(supplier)"
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
          <div v-if="filteredSuppliers.length === 0" class="text-center py-16">
            <Truck :size="64" class="text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500 font-bold mb-2">No se encontraron proveedores</p>
            <button 
              @click="searchQuery = ''; filterCategory = ''; filterStatus = ''"
              class="text-orange-600 hover:text-orange-700 font-bold text-sm"
            >
              Limpiar filtros
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Supplier Modal -->
    <transition name="modal">
      <div v-if="showSupplierModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl">
          <h3 class="text-2xl font-black text-gray-900 mb-6">
            {{ editingSupplier ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Razón Social *</label>
              <input 
                v-model="formSupplier.name"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-semibold"
                placeholder="Distribuidora ABC S.A.C."
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">RUC *</label>
              <input 
                v-model="formSupplier.ruc"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-mono font-bold"
                placeholder="20123456789"
                maxlength="11"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Categoría *</label>
              <select 
                v-model="formSupplier.category"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-bold"
              >
                <option value="">Seleccionar...</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Contacto Principal</label>
              <input 
                v-model="formSupplier.contact"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-semibold"
                placeholder="Juan Pérez"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Teléfono *</label>
              <input 
                v-model="formSupplier.phone"
                type="tel" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0 font-semibold"
                placeholder="999 888 777"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Email</label>
              <input 
                v-model="formSupplier.email"
                type="email" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0"
                placeholder="ventas@proveedor.com"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Dirección</label>
              <textarea 
                v-model="formSupplier.address"
                rows="2"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-0"
                placeholder="Av. Industrial 123, Lima"
              ></textarea>
            </div>
          </div>

          <div class="flex gap-3">
            <button 
              @click="closeSupplierModal"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="saveSupplier"
              class="flex-1 py-3 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition-colors"
            >
              {{ editingSupplier ? 'Guardar Cambios' : 'Crear Proveedor' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Supplier Profile Modal -->
    <transition name="modal">
      <div v-if="showProfileModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full shadow-2xl my-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl">
                {{ selectedSupplier?.name.charAt(0) }}
              </div>
              <div>
                <h3 class="text-2xl font-black text-gray-900">{{ selectedSupplier?.name }}</h3>
                <p class="text-gray-600">{{ selectedSupplier?.category }} • RUC: {{ selectedSupplier?.ruc }}</p>
              </div>
            </div>
            <button 
              @click="showProfileModal = false"
              class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition-colors"
            >
              <X :size="20" class="text-gray-600" />
            </button>
          </div>

          <!-- Supplier Stats -->
          <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-xl p-4">
              <p class="text-xs text-blue-700 font-bold uppercase mb-1">Total Órdenes</p>
              <p class="text-3xl font-black text-blue-900">{{ selectedSupplier?.totalOrders }}</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4">
              <p class="text-xs text-emerald-700 font-bold uppercase mb-1">Total Comprado</p>
              <p class="text-3xl font-black text-emerald-900">S/ {{ selectedSupplier?.totalPurchased.toFixed(2) }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4">
              <p class="text-xs text-purple-700 font-bold uppercase mb-1">Promedio/Orden</p>
              <p class="text-3xl font-black text-purple-900">S/ {{ selectedSupplier ? (selectedSupplier.totalPurchased / selectedSupplier.totalOrders).toFixed(2) : '0.00' }}</p>
            </div>
          </div>

          <!-- Tabs -->
          <div class="border-b border-gray-200 mb-6">
            <div class="flex gap-4">
              <button 
                @click="profileTab = 'orders'"
                :class="[
                  'pb-3 px-2 font-bold text-sm border-b-2 transition-colors',
                  profileTab === 'orders' ? 'border-orange-600 text-orange-600' : 'border-transparent text-gray-600'
                ]"
              >
                Historial de Órdenes
              </button>
              <button 
                @click="profileTab = 'products'"
                :class="[
                  'pb-3 px-2 font-bold text-sm border-b-2 transition-colors',
                  profileTab === 'products' ? 'border-orange-600 text-orange-600' : 'border-transparent text-gray-600'
                ]"
              >
                Productos Suministrados
              </button>
              <button 
                @click="profileTab = 'info'"
                :class="[
                  'pb-3 px-2 font-bold text-sm border-b-2 transition-colors',
                  profileTab === 'info' ? 'border-orange-600 text-orange-600' : 'border-transparent text-gray-600'
                ]"
              >
                Información
              </button>
            </div>
          </div>

          <!-- Orders History Tab -->
          <div v-if="profileTab === 'orders'" class="space-y-3 max-h-96 overflow-y-auto">
            <div 
              v-for="order in mockOrders" 
              :key="order.id"
              class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl"
            >
              <div class="w-12 h-12 bg-orange-500/10 rounded-xl flex items-center justify-center">
                <FileText :size="20" class="text-orange-600" />
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                  <h4 class="font-bold text-gray-900">Orden #{{ order.id }}</h4>
                  <span class="text-lg font-black text-gray-900">S/ {{ order.total.toFixed(2) }}</span>
                </div>
                <p class="text-sm text-gray-600">{{ order.items }} productos • {{ order.date }}</p>
                <span :class="[
                  'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold mt-2',
                  order.status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                  order.status === 'pending' ? 'bg-amber-100 text-amber-700' :
                  'bg-blue-100 text-blue-700'
                ]">
                  {{ order.status === 'completed' ? 'Completada' : order.status === 'pending' ? 'Pendiente' : 'En Tránsito' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Products Tab -->
          <div v-if="profileTab === 'products'" class="space-y-3 max-h-96 overflow-y-auto">
            <div 
              v-for="product in mockSuppliedProducts" 
              :key="product.id"
              class="flex items-center justify-between p-4 bg-gray-50 rounded-xl"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                  <Package :size="18" class="text-gray-600" />
                </div>
                <div>
                  <h4 class="font-bold text-gray-900">{{ product.name }}</h4>
                  <p class="text-xs text-gray-500">Código: {{ product.code }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-black text-gray-900">S/ {{ product.lastPrice.toFixed(2) }}</p>
                <p class="text-xs text-gray-500">Último precio</p>
              </div>
            </div>
          </div>

          <!-- Info Tab -->
          <div v-if="profileTab === 'info'" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Contacto Principal</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedSupplier?.contact }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Teléfono</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedSupplier?.phone }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Email</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedSupplier?.email || 'No registrado' }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Estado</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedSupplier?.status === 'active' ? 'Activo' : 'Inactivo' }}</p>
              </div>
              <div class="col-span-2">
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Dirección</p>
                <p class="text-sm font-semibold text-gray-900">{{ selectedSupplier?.address || 'No registrada' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- New Order Modal -->
    <transition name="modal">
      <div v-if="showOrderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
          <h3 class="text-2xl font-black text-gray-900 mb-6">Nueva Orden de Compra</h3>
          
          <div class="text-center py-8">
            <FileText :size="64" class="text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500 mb-4">Funcionalidad en construcción</p>
            <p class="text-sm text-gray-400">Aquí podrás crear órdenes de compra a tus proveedores</p>
          </div>

          <button 
            @click="showOrderModal = false"
            class="w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
          >
            Cerrar
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  Truck,
  Plus,
  Search,
  CheckCircle,
  ShoppingCart,
  DollarSign,
  Eye,
  Pencil,
  X,
  FileText,
  Package
} from 'lucide-vue-next'

// State
const searchQuery = ref('')
const filterCategory = ref('')
const filterStatus = ref('')
const showSupplierModal = ref(false)
const showProfileModal = ref(false)
const showOrderModal = ref(false)
const profileTab = ref('orders')
const editingSupplier = ref(null)
const selectedSupplier = ref(null)

// Categories
const categories = ['Alimentos', 'Bebidas', 'Empaques', 'Limpieza', 'Equipos', 'Varios']

// Form
const formSupplier = ref({
  name: '',
  ruc: '',
  category: '',
  contact: '',
  phone: '',
  email: '',
  address: ''
})

// Mock Data
const suppliers = ref([
  { id: 1, name: 'Distribuidora AVE S.A.C.', ruc: '20123456789', category: 'Alimentos', contact: 'Juan Pérez', phone: '999 888 777', email: 'ventas@ave.com', totalOrders: 45, totalPurchased: 12340.50, status: 'active', address: 'Av. Industrial 123, Lima' },
  { id: 2, name: 'Bebidas del Sur E.I.R.L.', ruc: '20234567890', category: 'Bebidas', contact: 'María López', phone: '988 777 666', email: 'contacto@bebidas.com', totalOrders: 32, totalPurchased: 8456.00, status: 'active', address: 'Jr. Comercio 456, Arequipa' },
  { id: 3, name: 'Empaques Perú S.A.', ruc: '20345678901', category: 'Empaques', contact: 'Carlos Ruiz', phone: '977 666 555', email: 'ventas@empaques.pe', totalOrders: 28, totalPurchased: 5689.75, status: 'active', address: 'Calle Principal 789, Callao' },
  { id: 4, name: 'Limpieza Total S.R.L.', ruc: '20456789012', category: 'Limpieza', contact: 'Ana Torres', phone: '966 555 444', email: 'info@limpiezatotal.com', totalOrders: 18, totalPurchased: 2340.00, status: 'active', address: 'Av. Los Héroes 321, Lima' },
  { id: 5, name: 'Equipos Gastro S.A.C.', ruc: '20567890123', category: 'Equipos', contact: 'Roberto Silva', phone: '955 444 333', email: 'ventas@equiposgastro.pe', totalOrders: 12, totalPurchased: 15890.00, status: 'inactive', address: 'Jr. Industria 654, Lima' },
])

const mockOrders = [
  { id: 12345, total: 1240.50, items: 15, date: 'Hace 3 días', status: 'completed' },
  { id: 12320, total: 890.00, items: 10, date: 'Hace 1 semana', status: 'completed' },
  { id: 12298, total: 1580.75, items: 20, date: 'Hace 2 semanas', status: 'pending' },
  { id: 12256, total: 720.00, items: 8, date: 'Hace 3 semanas', status: 'transit' },
]

const mockSuppliedProducts = [
  { id: 1, code: 'POL-RAW', name: 'Pollo Crudo Entero', lastPrice: 25.00 },
  { id: 2, code: 'PAP-IND', name: 'Papas Pre-Fritas Industrial', lastPrice: 45.00 },
  { id: 3, code: 'SAL-ESP', name: 'Sal Especial para Pollos', lastPrice: 12.50 },
  { id: 4, code: 'AJI-AMR', name: 'Ají Amarillo Molido', lastPrice: 18.00 },
]

// Computed
const filteredSuppliers = computed(() => {
  let filtered = suppliers.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(s => 
      s.name.toLowerCase().includes(query) || 
      s.ruc.includes(query) ||
      s.category.toLowerCase().includes(query)
    )
  }

  if (filterCategory.value) {
    filtered = filtered.filter(s => s.category === filterCategory.value)
  }

  if (filterStatus.value) {
    filtered = filtered.filter(s => s.status === filterStatus.value)
  }

  return filtered
})

const activeSuppliers = computed(() => suppliers.value.filter(s => s.status === 'active').length)
const pendingOrders = computed(() => 3) // Mock
const monthlyPurchases = computed(() => 24580.50) // Mock

// Methods
const openCreateModal = () => {
  editingSupplier.value = null
  formSupplier.value = {
    name: '',
    ruc: '',
    category: '',
    contact: '',
    phone: '',
    email: '',
    address: ''
  }
  showSupplierModal.value = true
}

const openEditModal = (supplier) => {
  editingSupplier.value = supplier
  formSupplier.value = { ...supplier }
  showSupplierModal.value = true
}

const closeSupplierModal = () => {
  showSupplierModal.value = false
  editingSupplier.value = null
}

const saveSupplier = () => {
  if (!formSupplier.value.name || !formSupplier.value.ruc || !formSupplier.value.phone) {
    alert('Completa los campos requeridos')
    return
  }

  if (editingSupplier.value) {
    // Update existing
    const index = suppliers.value.findIndex(s => s.id === editingSupplier.value.id)
    if (index > -1) {
      suppliers.value[index] = { ...suppliers.value[index], ...formSupplier.value }
    }
  } else {
    // Create new
    const newSupplier = {
      ...formSupplier.value,
      id: suppliers.value.length + 1,
      totalOrders: 0,
      totalPurchased: 0,
      status: 'active'
    }
    suppliers.value.push(newSupplier)
  }
  
  closeSupplierModal()
}

const viewSupplier = (supplier) => {
  selectedSupplier.value = supplier
  profileTab.value = 'orders'
  showProfileModal.value = true
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
