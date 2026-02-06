<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <Box :size="28" class="text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-black text-gray-900">Control de Inventario</h1>
            <p class="text-sm text-gray-500 font-semibold">Movimientos y existencias • {{ currentDate }}</p>
          </div>
        </div>

        <button 
          @click="showMovementModal = true"
          class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 transition-all hover:scale-105 flex items-center gap-2"
        >
          <Plus :size="20" />
          Registrar Movimiento
        </button>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1800px] mx-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
              <Package :size="24" class="text-blue-600" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700">Total</span>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Productos</p>
          <h3 class="text-3xl font-black text-gray-900">{{ inventoryItems.length }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
              <TrendingUp :size="24" class="text-emerald-600" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ entriesCount }}</span>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Entradas Hoy</p>
          <h3 class="text-3xl font-black text-gray-900">{{ totalEntries }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center">
              <TrendingDown :size="24" class="text-red-600" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700">{{ exitsCount }}</span>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Salidas Hoy</p>
          <h3 class="text-3xl font-black text-gray-900">{{ totalExits }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
              <AlertTriangle :size="24" class="text-amber-600" />
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">Alerta</span>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Stock Bajo</p>
          <h3 class="text-3xl font-black text-gray-900">{{ lowStockCount }}</h3>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-200 flex">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'flex-1 px-6 py-4 font-bold text-sm transition-colors relative',
              activeTab === tab.id 
                ? 'text-blue-600 bg-blue-50' 
                : 'text-gray-600 hover:bg-gray-50'
            ]"
          >
            {{ tab.label }}
            <div v-if="activeTab === tab.id" class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600"></div>
          </button>
        </div>

        <!-- Existencias Tab -->
        <div v-if="activeTab === 'stock'" class="p-6">
          <!-- Search -->
          <div class="flex gap-4 mb-6">
            <div class="flex-1 relative">
              <Search :size="20" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Buscar productos..."
                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 font-semibold"
              />
            </div>
            <select 
              v-model="filterStatus"
              class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 font-bold"
            >
              <option value="">Todos los estados</option>
              <option value="ok">Stock OK</option>
              <option value="low">Stock Bajo</option>
              <option value="critical">Crítico</option>
            </select>
          </div>

          <!-- Inventory Table -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Producto</th>
                  <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Stock Actual</th>
                  <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Stock Mínimo</th>
                  <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Estado</th>
                  <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase">Última Act.</th>
                  <th class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr 
                  v-for="item in filteredInventory" 
                  :key="item.id"
                  class="hover:bg-gray-50 transition-colors"
                >
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <Package :size="18" class="text-gray-400" />
                      </div>
                      <div>
                        <h4 class="font-bold text-gray-900">{{ item.name }}</h4>
                        <p class="text-xs text-gray-500">{{ item.code }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-2xl font-black text-gray-900">{{ item.stock }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm font-semibold text-gray-600">{{ item.minStock }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <div :class="[
                        'w-3 h-3 rounded-full',
                        item.stock > item.minStock * 2 ? 'bg-emerald-500' :
                        item.stock > item.minStock ? 'bg-amber-500' :
                        'bg-red-500'
                      ]"></div>
                      <span :class="[
                        'text-sm font-bold',
                        item.stock > item.minStock * 2 ? 'text-emerald-600' :
                        item.stock > item.minStock ? 'text-amber-600' :
                        'text-red-600'
                      ]">
                        {{ item.stock > item.minStock * 2 ? 'OK' : item.stock > item.minStock ? 'Bajo' : 'Crítico' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm text-gray-500">{{ item.lastUpdate }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                      <button 
                        @click="viewKardex(item)"
                        class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg font-bold text-xs hover:bg-blue-100 transition-colors"
                      >
                        Kardex
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Movimientos Tab -->
        <div v-if="activeTab === 'movements'" class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-black text-gray-900">Historial de Movimientos</h3>
            <div class="flex gap-2">
              <button 
                v-for="type in movementTypes" 
                :key="type.value"
                @click="filterMovement = type.value"
                :class="[
                  'px-4 py-2 rounded-xl font-bold text-sm transition-all',
                  filterMovement === type.value 
                    ? 'bg-blue-600 text-white' 
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                ]"
              >
                {{ type.label }}
              </button>
            </div>
          </div>

          <div class="space-y-3">
            <div 
              v-for="movement in filteredMovements" 
              :key="movement.id"
              class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors"
            >
              <div :class="[
                'w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0',
                movement.type === 'entry' ? 'bg-emerald-500/10' : 
                movement.type === 'exit' ? 'bg-red-500/10' : 
                'bg-blue-500/10'
              ]">
                <component 
                  :is="movement.type === 'entry' ? ArrowDownToLine : movement.type === 'exit' ? ArrowUpFromLine : RefreshCw" 
                  :size="20" 
                  :class="movement.type === 'entry' ? 'text-emerald-600' : movement.type === 'exit' ? 'text-red-600' : 'text-blue-600'" 
                />
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-1">
                  <div>
                    <h4 class="font-bold text-gray-900">{{ movement.product }}</h4>
                    <p class="text-sm text-gray-600 mt-0.5">{{ movement.description }}</p>
                  </div>
                  <span :class="[
                    'text-lg font-black whitespace-nowrap ml-4',
                    movement.type === 'entry' ? 'text-emerald-600' : 'text-red-600'
                  ]">
                    {{ movement.type === 'entry' ? '+' : '-' }}{{ movement.quantity }}
                  </span>
                </div>
                <div class="flex items-center gap-4 mt-2">
                  <span class="text-xs text-gray-500">{{ movement.date }} • {{ movement.time }}</span>
                  <span class="text-xs font-semibold text-gray-700">{{ movement.user }}</span>
                  <span :class="[
                    'text-xs font-bold px-2 py-0.5 rounded-full',
                    movement.type === 'entry' ? 'bg-emerald-100 text-emerald-700' :
                    movement.type === 'exit' ? 'bg-red-100 text-red-700' :
                    'bg-blue-100 text-blue-700'
                  ]">
                    {{ movement.type === 'entry' ? 'Entrada' : movement.type === 'exit' ? 'Salida' : 'Ajuste' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Alerts Tab -->
        <div v-if="activeTab === 'alerts'" class="p-6">
          <h3 class="text-lg font-black text-gray-900 mb-6">Alertas de Reabastecimiento</h3>
          
          <div class="space-y-3">
            <div 
              v-for="alert in stockAlerts" 
              :key="alert.id"
              class="flex items-center gap-4 p-4 bg-amber-50 border-2 border-amber-200 rounded-xl"
            >
              <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                <AlertTriangle :size="24" class="text-white" />
              </div>

              <div class="flex-1">
                <h4 class="font-black text-gray-900">{{ alert.name }}</h4>
                <p class="text-sm text-gray-600 mt-0.5">Stock: {{ alert.stock }} • Mínimo: {{ alert.minStock }}</p>
              </div>

              <div class="text-right">
                <p class="text-xs text-gray-500 mb-2">Sugerido</p>
                <p class="text-2xl font-black text-amber-600">{{ alert.suggested }}</p>
              </div>

              <button class="px-4 py-2 bg-amber-600 text-white rounded-xl font-bold hover:bg-amber-700 transition-colors">
                Generar Orden
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Register Movement Modal -->
    <transition name="modal">
      <div v-if="showMovementModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl">
          <h3 class="text-2xl font-black text-gray-900 mb-6">Registrar Movimiento</h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Tipo de Movimiento</label>
              <div class="grid grid-cols-3 gap-3">
                <button 
                  @click="newMovement.type = 'entry'"
                  :class="[
                    'py-3 rounded-xl font-bold transition-all',
                    newMovement.type === 'entry' 
                      ? 'bg-emerald-600 text-white' 
                      : 'bg-gray-100 text-gray-700'
                  ]"
                >
                  📥 Entrada
                </button>
                <button 
                  @click="newMovement.type = 'exit'"
                  :class="[
                    'py-3 rounded-xl font-bold transition-all',
                    newMovement.type === 'exit' 
                      ? 'bg-red-600 text-white' 
                      : 'bg-gray-100 text-gray-700'
                  ]"
                >
                  📤 Salida
                </button>
                <button 
                  @click="newMovement.type = 'adjustment'"
                  :class="[
                    'py-3 rounded-xl font-bold transition-all',
                    newMovement.type === 'adjustment' 
                      ? 'bg-blue-600 text-white' 
                      : 'bg-gray-100 text-gray-700'
                  ]"
                >
                  🔄 Ajuste
                </button>
              </div>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Producto</label>
              <select 
                v-model="newMovement.productId"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 font-bold"
              >
                <option value="">Seleccionar producto...</option>
                <option v-for="item in inventoryItems" :key="item.id" :value="item.id">
                  {{ item.name }} (Stock: {{ item.stock }})
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Cantidad</label>
              <input 
                v-model.number="newMovement.quantity"
                type="number" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 font-bold"
                placeholder="0"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Fecha y Hora</label>
              <input 
                v-model="newMovement.datetime"
                type="datetime-local" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 font-semibold"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Descripción/Motivo</label>
              <textarea 
                v-model="newMovement.description"
                rows="3"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0"
                placeholder="Detalle del movimiento..."
              ></textarea>
            </div>
          </div>

          <div class="flex gap-3">
            <button 
              @click="showMovementModal = false"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="saveMovement"
              class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors"
            >
              Registrar Movimiento
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Kardex Modal -->
    <transition name="modal">
      <div v-if="showKardexModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full shadow-2xl my-8">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-2xl font-black text-gray-900">Kardex de Producto</h3>
              <p class="text-gray-600">{{ selectedProduct?.name }}</p>
            </div>
            <button 
              @click="showKardexModal = false"
              class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition-colors"
            >
              <X :size="20" class="text-gray-600" />
            </button>
          </div>

          <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <div class="grid grid-cols-3 gap-6">
              <div>
                <p class="text-xs text-gray-500 mb-1">Stock Actual</p>
                <p class="text-2xl font-black text-gray-900">{{ selectedProduct?.stock }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 mb-1">Stock Mínimo</p>
                <p class="text-2xl font-black text-amber-600">{{ selectedProduct?.minStock }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 mb-1">Estado</p>
                <p :class="[
                  'text-2xl font-black',
                  selectedProduct?.stock > selectedProduct?.minStock * 2 ? 'text-emerald-600' : 'text-red-600'
                ]">
                  {{ selectedProduct?.stock > selectedProduct?.minStock * 2 ? 'OK' : 'Bajo' }}
                </p>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-black text-gray-500 uppercase">Fecha</th>
                  <th class="px-4 py-3 text-left text-xs font-black text-gray-500 uppercase">Tipo</th>
                  <th class="px-4 py-3 text-left text-xs font-black text-gray-500 uppercase">Descripción</th>
                  <th class="px-4 py-3 text-right text-xs font-black text-gray-500 uppercase">Entrada</th>
                  <th class="px-4 py-3 text-right text-xs font-black text-gray-500 uppercase">Salida</th>
                  <th class="px-4 py-3 text-right text-xs font-black text-gray-500 uppercase">Saldo</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="(record, index) in kardexRecords" :key="index" class="hover:bg-gray-50">
                  <td class="px-4 py-3 text-gray-600">{{ record.date }}</td>
                  <td class="px-4 py-3">
                    <span :class="[
                      'text-xs font-bold px-2 py-1 rounded-full',
                      record.type === 'entry' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'
                    ]">
                      {{ record.type === 'entry' ? 'Entrada' : 'Salida' }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-gray-700">{{ record.description }}</td>
                  <td class="px-4 py-3 text-right font-bold text-emerald-600">
                    {{ record.type === 'entry' ? record.quantity : '-' }}
                  </td>
                  <td class="px-4 py-3 text-right font-bold text-red-600">
                    {{ record.type === 'exit' ? record.quantity : '-' }}
                  </td>
                  <td class="px-4 py-3 text-right font-black text-gray-900">{{ record.balance }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  Box,
  Package,
  Plus,
  Search,
  TrendingUp,
  TrendingDown,
  AlertTriangle,
  ArrowDownToLine,
  ArrowUpFromLine,
  RefreshCw,
  X
} from 'lucide-vue-next'

const currentDate = new Date().toLocaleDateString('es-PE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })

// State
const activeTab = ref('stock')
const searchQuery = ref('')
const filterStatus = ref('')
const filterMovement = ref('all')
const showMovementModal = ref(false)
const showKardexModal = ref(false)
const selectedProduct = ref(null)

// Tabs
const tabs = [
  { id: 'stock', label: 'Existencias' },
  { id: 'movements', label: 'Movimientos' },
  { id: 'alerts', label: 'Alertas' }
]

const movementTypes = [
  { value: 'all', label: 'Todos' },
  { value: 'entry', label: 'Entradas' },
  { value: 'exit', label: 'Salidas' }
]

// Form
const newMovement = ref({
  type: 'entry',
  productId: '',
  quantity: 0,
  datetime: new Date().toISOString().slice(0, 16),
  description: ''
})

// Mock Data
const inventoryItems = ref([
  { id: 1, code: 'POL-001', name: 'Pollo a la Brasa 1/4', stock: 45, minStock: 10, lastUpdate: 'Hace 2 horas' },
  { id: 2, code: 'POL-002', name: 'Pollo a la Brasa 1/2', stock: 32, minStock: 10, lastUpdate: 'Hace 3 horas' },
  { id: 3, code: 'GUA-001', name: 'Papas Fritas', stock: 60, minStock: 20, lastUpdate: 'Hace 1 hora' },
  { id: 4, code: 'BEB-001', name: 'Inca Kola 1.5L', stock: 8, minStock: 30, lastUpdate: 'Hace 30 min' },
  { id: 5, code: 'BEB-002', name: 'Coca Cola 1.5L', stock: 5, minStock: 30, lastUpdate: 'Hace 45 min' },
])

const movements = ref([
  { id: 1, type: 'entry', product: 'Pollo a la Brasa 1/4', description: 'Compra a proveedor', quantity: 20, date: 'Hoy', time: '14:30', user: 'Admin' },
  { id: 2, type: 'exit', product: 'Papas Fritas', description: 'Venta #10234', quantity: 5, date: 'Hoy', time: '13:45', user: 'Cajero 1' },
  { id: 3, type: 'entry', product: 'Inca Kola 1.5L', description: 'Reabastecimiento', quantity: 50, date: 'Hoy', time: '10:15', user: 'Admin' },
  { id: 4, type: 'adjustment', product: 'Coca Cola 1.5L', description: 'Ajuste por inventario', quantity: 3, date: 'Ayer', time: '18:00', user: 'Admin' },
])

const kardexRecords = ref([
  { date: '31/01/2026 14:30', type: 'entry', description: 'Compra proveedor', quantity: 20, balance: 45 },
  { date: '31/01/2026 12:15', type: 'exit', description: 'Venta #10230', quantity: 1, balance: 25 },
  { date: '31/01/2026 10:00', type: 'entry', description: 'Reabastecimiento', quantity: 10, balance: 26 },
  { date: '30/01/2026 16:45', type: 'exit', description: 'Venta #10225', quantity: 2, balance: 16 },
])

// Computed
const filteredInventory = computed(() => {
  let filtered = inventoryItems.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(item => 
      item.name.toLowerCase().includes(query) || 
      item.code.toLowerCase().includes(query)
    )
  }

  if (filterStatus.value) {
    if (filterStatus.value === 'ok') {
      filtered = filtered.filter(item => item.stock > item.minStock * 2)
    } else if (filterStatus.value === 'low') {
      filtered = filtered.filter(item => item.stock > item.minStock && item.stock <= item.minStock * 2)
    } else if (filterStatus.value === 'critical') {
      filtered = filtered.filter(item => item.stock <= item.minStock)
    }
  }

  return filtered
})

const filteredMovements = computed(() => {
  if (filterMovement.value === 'all') return movements.value
  return movements.value.filter(m => m.type === filterMovement.value)
})

const stockAlerts = computed(() => {
  return inventoryItems.value
    .filter(item => item.stock <= item.minStock)
    .map(item => ({
      ...item,
      suggested: Math.max(item.minStock * 3 - item.stock, 0)
    }))
})

const lowStockCount = computed(() => inventoryItems.value.filter(i => i.stock <= i.minStock).length)
const entriesCount = computed(() => movements.value.filter(m => m.type === 'entry' && m.date === 'Hoy').length)
const exitsCount = computed(() => movements.value.filter(m => m.type === 'exit' && m.date === 'Hoy').length)
const totalEntries = computed(() => movements.value.filter(m => m.type === 'entry' && m.date === 'Hoy').reduce((sum, m) => sum + m.quantity, 0))
const totalExits = computed(() => movements.value.filter(m => m.type === 'exit' && m.date === 'Hoy').reduce((sum, m) => sum + m.quantity, 0))

// Methods
const saveMovement = () => {
  if (!newMovement.value.productId || !newMovement.value.quantity) {
    alert('Completa todos los campos')
    return
  }

  const product = inventoryItems.value.find(p => p.id === newMovement.value.productId)
  
  const movement = {
    id: movements.value.length + 1,
    type: newMovement.value.type,
    product: product.name,
    description: newMovement.value.description,
    quantity: newMovement.value.quantity,
    date: 'Hoy',
    time: new Date().toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' }),
    user: 'Admin'
  }

  movements.value.unshift(movement)
  
  // Update stock
  if (newMovement.value.type === 'entry' || newMovement.value.type === 'adjustment') {
    product.stock += newMovement.value.quantity
  } else {
    product.stock = Math.max(0, product.stock - newMovement.value.quantity)
  }

  showMovementModal.value = false
  newMovement.value = {
    type: 'entry',
    productId: '',
    quantity: 0,
    datetime: new Date().toISOString().slice(0, 16),
    description: ''
  }
}

const viewKardex = (product) => {
  selectedProduct.value = product
  showKardexModal.value = true
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
