<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
            <Wallet :size="28" class="text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-black text-gray-900">Gestión de Caja</h1>
            <p class="text-sm text-gray-500 font-semibold">{{ currentBranch?.name || 'Sucursal Principal' }} • {{ currentDate }}</p>
          </div>
        </div>

        <div v-if="!cashRegisterOpen" class="flex gap-3">
          <button 
            @click="showOpenModal = true"
            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white rounded-xl font-bold shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all hover:scale-105 flex items-center gap-2"
          >
            <Lock :size="20" />
            Abrir Caja
          </button>
        </div>

        <div v-else class="flex gap-3">
          <button 
            @click="showMovementModal = true"
            class="px-4 py-2.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors flex items-center gap-2"
          >
            <ArrowUpDown :size="18" />
            Movimiento
          </button>
          <button 
            @click="showCloseModal = true"
            class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl font-bold shadow-lg shadow-red-600/30 hover:shadow-red-600/40 transition-all hover:scale-105 flex items-center gap-2"
          >
            <LockOpen :size="20" />
            Cerrar Caja
          </button>
        </div>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1600px] mx-auto">
      <!-- Cash Register Closed State -->
      <div v-if="!cashRegisterOpen" class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center">
          <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <Lock :size="48" class="text-gray-400" />
          </div>
          <h2 class="text-2xl font-black text-gray-900 mb-2">Caja Cerrada</h2>
          <p class="text-gray-500 mb-6">Para comenzar a operar, debes abrir la caja con el monto inicial</p>
          <button 
            @click="showOpenModal = true"
            class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white rounded-xl font-black text-lg shadow-xl shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all hover:scale-105"
          >
            Abrir Caja Ahora
          </button>
        </div>
      </div>

      <!-- Cash Register Open State -->
      <div v-else>
        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Opening Balance -->
          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                <TrendingUp :size="24" class="text-blue-600" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700">Apertura</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Monto Inicial</p>
            <h3 class="text-2xl font-black text-gray-900">S/ {{ openingBalance.toFixed(2) }}</h3>
            <p class="text-xs text-gray-500 mt-2">{{ openingTime }}</p>
          </div>

          <!-- Total Sales -->
          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <DollarSign :size="24" class="text-emerald-600" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">+{{ salesCount }}</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Ventas del Día</p>
            <h3 class="text-2xl font-black text-gray-900">S/ {{ totalSales.toFixed(2) }}</h3>
            <p class="text-xs text-gray-500 mt-2">Efectivo + Tarjeta</p>
          </div>

          <!-- Cash Movements -->
          <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
                <ArrowUpDown :size="24" class="text-amber-600" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">{{ movements.length }}</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Movimientos</p>
            <h3 class="text-2xl font-black text-gray-900">S/ {{ totalMovements.toFixed(2) }}</h3>
            <p class="text-xs text-gray-500 mt-2">Ingresos - Egresos</p>
          </div>

          <!-- Expected Total -->
          <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                <Calculator :size="24" class="text-white" />
              </div>
              <span class="text-xs font-bold px-2 py-1 rounded-full bg-white/20 text-white">Esperado</span>
            </div>
            <p class="text-xs text-white/60 font-bold uppercase mb-1">Total en Caja</p>
            <h3 class="text-3xl font-black text-white">S/ {{ expectedTotal.toFixed(2) }}</h3>
            <p class="text-xs text-white/60 mt-2">Inicial + Ventas + Movimientos</p>
          </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="border-b border-gray-200 flex">
            <button 
              v-for="tab in tabs" 
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                'flex-1 px-6 py-4 font-bold text-sm transition-colors relative',
                activeTab === tab.id 
                  ? 'text-emerald-600 bg-emerald-50' 
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              {{ tab.label }}
              <div v-if="activeTab === tab.id" class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-600"></div>
            </button>
          </div>

          <!-- Movements Tab -->
          <div v-if="activeTab === 'movements'" class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-black text-gray-900">Movimientos de Caja</h3>
              <button 
                @click="showMovementModal = true"
                class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-colors flex items-center gap-2"
              >
                <Plus :size="18" />
                Nuevo Movimiento
              </button>
            </div>

            <div class="space-y-3">
              <div 
                v-for="movement in movements" 
                :key="movement.id"
                class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors"
              >
                <div :class="[
                  'w-12 h-12 rounded-xl flex items-center justify-center',
                  movement.type === 'income' ? 'bg-emerald-500/10' : 'bg-red-500/10'
                ]">
                  <component :is="movement.type === 'income' ? TrendingUp : TrendingDown" :size="24" :class="movement.type === 'income' ? 'text-emerald-600' : 'text-red-600'" />
                </div>

                <div class="flex-1">
                  <h4 class="font-bold text-gray-900">{{ movement.description }}</h4>
                  <p class="text-xs text-gray-500">{{ movement.time }} • {{ movement.user }}</p>
                </div>

                <div class="text-right">
                  <p :class="[
                    'text-lg font-black',
                    movement.type === 'income' ? 'text-emerald-600' : 'text-red-600'
                  ]">
                    {{ movement.type === 'income' ? '+' : '-' }} S/ {{ movement.amount.toFixed(2) }}
                  </p>
                  <p class="text-xs text-gray-500">{{ movement.category }}</p>
                </div>
              </div>

              <div v-if="movements.length === 0" class="text-center py-12">
                <p class="text-gray-500">No hay movimientos registrados</p>
              </div>
            </div>
          </div>

          <!-- Sales Tab -->
          <div v-if="activeTab === 'sales'" class="p-6">
            <h3 class="text-lg font-black text-gray-900 mb-6">Ventas del Día</h3>
            
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-black text-gray-500 uppercase pb-3">ID</th>
                    <th class="text-left text-xs font-black text-gray-500 uppercase pb-3">Cliente</th>
                    <th class="text-left text-xs font-black text-gray-500 uppercase pb-3">Método</th>
                    <th class="text-left text-xs font-black text-gray-500 uppercase pb-3">Total</th>
                    <th class="text-left text-xs font-black text-gray-500 uppercase pb-3">Hora</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr v-for="sale in sales" :key="sale.id" class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 text-sm font-bold text-gray-900">#{{ sale.id }}</td>
                    <td class="py-3 text-sm text-gray-700">{{ sale.customer }}</td>
                    <td class="py-3">
                      <span :class="[
                        'text-xs font-bold px-2 py-1 rounded-full',
                        sale.method === 'cash' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'
                      ]">
                        {{ sale.method === 'cash' ? '💵 Efectivo' : '💳 Tarjeta' }}
                      </span>
                    </td>
                    <td class="py-3 text-sm font-black text-gray-900">S/ {{ sale.total.toFixed(2) }}</td>
                    <td class="py-3 text-sm text-gray-500">{{ sale.time }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Report Tab -->
          <div v-if="activeTab === 'report'" class="p-6">
            <h3 class="text-lg font-black text-gray-900 mb-6">Resumen de Cierre</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Summary -->
              <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-sm font-semibold text-gray-600">Monto Inicial</span>
                  <span class="text-sm font-bold text-gray-900">S/ {{ openingBalance.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-sm font-semibold text-gray-600">Ventas Efectivo</span>
                  <span class="text-sm font-bold text-emerald-600">+ S/ {{ cashSales.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-sm font-semibold text-gray-600">Ventas Tarjeta</span>
                  <span class="text-sm font-bold text-blue-600">+ S/ {{ cardSales.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-sm font-semibold text-gray-600">Ingresos Extra</span>
                  <span class="text-sm font-bold text-emerald-600">+ S/ {{ incomeMovements.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                  <span class="text-sm font-semibold text-gray-600">Egresos</span>
                  <span class="text-sm font-bold text-red-600">- S/ {{ expenseMovements.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between items-center py-4 bg-gray-50 rounded-xl px-4">
                  <span class="text-lg font-black text-gray-900">ESPERADO EN CAJA</span>
                  <span class="text-2xl font-black text-emerald-600">S/ {{ expectedTotal.toFixed(2) }}</span>
                </div>
              </div>

              <!-- Payment Methods Breakdown -->
              <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="font-black text-gray-900 mb-4">Desglose por Método</h4>
                <div class="space-y-3">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <Banknote :size="20" class="text-green-600" />
                      </div>
                      <div>
                        <p class="font-bold text-gray-900">Efectivo</p>
                        <p class="text-xs text-gray-500">{{ cashCount }} transacciones</p>
                      </div>
                    </div>
                    <p class="font-black text-gray-900">S/ {{ cashSales.toFixed(2) }}</p>
                  </div>

                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <CreditCard :size="20" class="text-blue-600" />
                      </div>
                      <div>
                        <p class="font-bold text-gray-900">Tarjeta</p>
                        <p class="text-xs text-gray-500">{{ cardCount }} transacciones</p>
                      </div>
                    </div>
                    <p class="font-black text-gray-900">S/ {{ cardSales.toFixed(2) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Open Cash Register Modal -->
    <transition name="modal">
      <div v-if="showOpenModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <LockOpen :size="32" class="text-white" />
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Abrir Caja</h3>
            <p class="text-gray-600">Ingresa el monto inicial en efectivo</p>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-black text-gray-700 mb-2">Monto Inicial (S/)</label>
            <input 
              v-model="tempOpeningBalance"
              type="number"
              step="0.01"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-center text-2xl font-black"
              placeholder="0.00"
            />
          </div>

          <div class="flex gap-3">
            <button 
              @click="showOpenModal = false"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="openCashRegister"
              class="flex-1 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-colors"
            >
              Abrir Caja
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Movement Modal -->
    <transition name="modal">
      <div v-if="showMovementModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
          <h3 class="text-2xl font-black text-gray-900 mb-6">Nuevo Movimiento</h3>

          <div class="space-y-4 mb-6">
            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Tipo</label>
              <div class="grid grid-cols-2 gap-3">
                <button 
                  @click="newMovement.type = 'income'"
                  :class="[
                    'py-3 rounded-xl font-bold transition-all',
                    newMovement.type === 'income' 
                      ? 'bg-emerald-600 text-white' 
                      : 'bg-gray-100 text-gray-700'
                  ]"
                >
                  Ingreso
                </button>
                <button 
                  @click="newMovement.type = 'expense'"
                  :class="[
                    'py-3 rounded-xl font-bold transition-all',
                    newMovement.type === 'expense' 
                      ? 'bg-red-600 text-white' 
                      : 'bg-gray-100 text-gray-700'
                  ]"
                >
                  Egreso
                </button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Monto (S/)</label>
              <input 
                v-model="newMovement.amount"
                type="number"
                step="0.01"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-0 font-bold"
                placeholder="0.00"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Descripción</label>
              <textarea 
                v-model="newMovement.description"
                rows="3"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-0"
                placeholder="Motivo del movimiento..."
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
              @click="addMovement"
              class="flex-1 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-colors"
            >
              Guardar
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Close Cash Register Modal -->
    <transition name="modal">
      <div v-if="showCloseModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <Lock :size="32" class="text-white" />
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Cerrar Caja</h3>
            <p class="text-gray-600 mb-4">Verifica el efectivo físico en caja</p>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
              <p class="text-xs text-gray-500 mb-1">Esperado en Caja</p>
              <p class="text-3xl font-black text-emerald-600">S/ {{ expectedTotal.toFixed(2) }}</p>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-black text-gray-700 mb-2">Efectivo Contado (S/)</label>
            <input 
              v-model="actualCash"
              type="number"
              step="0.01"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-0 text-center text-2xl font-black"
              placeholder="0.00"
            />
            
            <div v-if="actualCash" class="mt-3 text-center">
              <p :class="[
                'text-sm font-bold',
                difference === 0 ? 'text-emerald-600' : difference > 0 ? 'text-blue-600' : 'text-red-600'
              ]">
                {{ difference === 0 ? 'Cuadre Exacto ✓' : difference > 0 ? `Sobrante: S/ ${difference.toFixed(2)}` : `Faltante: S/ ${Math.abs(difference).toFixed(2)}` }}
              </p>
            </div>
          </div>

          <div class="flex gap-3">
            <button 
              @click="showCloseModal = false"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="closeCashRegister"
              class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-colors"
            >
              Cerrar Caja
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { authStore } from '@/store/auth'
import {
  Wallet,
  Lock,
  LockOpen,
  TrendingUp,
  TrendingDown,
  DollarSign,
  ArrowUpDown,
  Calculator,
  Plus,
  Banknote,
  CreditCard
} from 'lucide-vue-next'

const currentBranch = computed(() => authStore.state.current_branch)
const currentDate = new Date().toLocaleDateString('es-PE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })

// Cash Register State
const cashRegisterOpen = ref(false)
const openingBalance = ref(0)
const openingTime = ref('')
const tempOpeningBalance = ref(200)

// Modals
const showOpenModal = ref(false)
const showMovementModal = ref(false)
const showCloseModal = ref(false)
const actualCash = ref(null)

// Tabs
const activeTab = ref('movements')
const tabs = [
  { id: 'movements', label: 'Movimientos' },
  { id: 'sales', label: 'Ventas' },
  { id: 'report', label: 'Resumen' }
]

// Mock Data
const movements = ref([
  { id: 1, type: 'income', amount: 50, description: 'Pago de servicio extra', category: 'Servicio', time: '10:30', user: 'Admin' },
  { id: 2, type: 'expense', amount: 30, description: 'Compra de insumos', category: 'Compras', time: '11:45', user: 'Admin' },
])

const sales = ref([
  { id: 10234, customer: 'Juan Pérez', method: 'cash', total: 45.50, time: '14:35' },
  { id: 10233, customer: 'María García', method: 'card', total: 78.20, time: '14:28' },
  { id: 10232, customer: 'Carlos López', method: 'cash', total: 32.00, time: '14:15' },
  { id: 10231, customer: 'Ana Torres', method: 'card', total: 56.90, time: '14:08' },
  { id: 10230, customer: 'Roberto Silva', method: 'cash', total: 18.50, time: '13:52' },
])

// New Movement
const newMovement = ref({
  type: 'income',
  amount: null,
  description: ''
})

// Computed
const totalSales = computed(() => sales.value.reduce((sum, sale) => sum + sale.total, 0))
const salesCount = computed(() => sales.value.length)
const cashSales = computed(() => sales.value.filter(s => s.method === 'cash').reduce((sum, s) => sum + s.total, 0))
const cardSales = computed(() => sales.value.filter(s => s.method === 'card').reduce((sum, s) => sum + s.total, 0))
const cashCount = computed(() => sales.value.filter(s => s.method === 'cash').length)
const cardCount = computed(() => sales.value.filter(s => s.method === 'card').length)

const incomeMovements = computed(() => movements.value.filter(m => m.type === 'income').reduce((sum, m) => sum + m.amount, 0))
const expenseMovements = computed(() => movements.value.filter(m => m.type === 'expense').reduce((sum, m) => sum + m.amount, 0))
const totalMovements = computed(() => incomeMovements.value - expenseMovements.value)

const expectedTotal = computed(() => openingBalance.value + cashSales.value + totalMovements.value)
const difference = computed(() => actualCash.value ? actualCash.value - expectedTotal.value : 0)

// Methods
const openCashRegister = () => {
  openingBalance.value = parseFloat(tempOpeningBalance.value) || 0
  openingTime.value = new Date().toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' })
  cashRegisterOpen.value = true
  showOpenModal.value = false
}

const addMovement = () => {
  const movement = {
    id: movements.value.length + 1,
    type: newMovement.value.type,
    amount: parseFloat(newMovement.value.amount) || 0,
    description: newMovement.value.description,
    category: newMovement.value.type === 'income' ? 'Ingreso' : 'Egreso',
    time: new Date().toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' }),
    user: 'Admin'
  }
  
  movements.value.unshift(movement)
  showMovementModal.value = false
  newMovement.value = { type: 'income', amount: null, description: '' }
}

const closeCashRegister = () => {
  if (!actualCash.value) {
    alert('Por favor ingresa el efectivo contado')
    return
  }
  
  alert(`Caja cerrada exitosamente. Diferencia: S/ ${difference.value.toFixed(2)}`)
  
  // Reset
  cashRegisterOpen.value = false
  openingBalance.value = 0
  movements.value = []
  sales.value = []
  actualCash.value = null
  showCloseModal.value = false
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
