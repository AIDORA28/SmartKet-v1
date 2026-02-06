<template>
  <div class="h-screen flex flex-col bg-gray-50 overflow-hidden">
    <!-- POS Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
            <ShoppingCart :size="24" class="text-white" />
          </div>
          <div>
            <h1 class="text-xl font-black text-gray-900">Punto de Venta</h1>
            <p class="text-xs text-gray-500 font-semibold">{{ currentBranch?.name || 'Sucursal Principal' }} • {{ currentDate }}</p>
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <button 
            @click="router.push({ name: 'reportes' })"
            class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-bold text-gray-700 transition-colors"
          >
            <Clock :size="16" />
            <span class="hidden md:inline">Historial</span>
          </button>
          <button 
            @click="router.push({ name: 'finanzas' })"
            class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-sm font-bold text-white transition-colors shadow-lg shadow-blue-600/30"
          >
            <Receipt :size="16" />
            <span class="hidden md:inline">Ver Caja</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Main POS Content -->
    <div class="flex-1 flex overflow-hidden gap-0">
      <!-- Left Side: Products (más ancho) -->
      <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
        <!-- Search & Filters -->
        <div class="bg-white border-b border-gray-200 p-4">
          <div class="flex gap-3">
            <div class="flex-1 relative">
              <Search :size="20" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Buscar productos por nombre o código..."
                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-0 font-semibold text-gray-900 placeholder-gray-400"
              />
            </div>
            <!-- Toggle cart button (visible on tablets) -->
            <button 
              @click="showCart = !showCart"
              class="lg:hidden px-4 py-3 bg-red-600 text-white rounded-xl font-bold transition-colors flex items-center gap-2 relative"
            >
              <ShoppingBag :size="20" />
              <span v-if="cart.length > 0" class="absolute -top-2 -right-2 w-6 h-6 bg-white text-red-600 rounded-full flex items-center justify-center text-xs font-black">
                {{ cart.length }}
              </span>
            </button>
          </div>

          <!-- Categories - Scroll horizontal mejorado -->
          <div class="relative mt-4">
            <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar scroll-smooth snap-x snap-mandatory">
              <button 
                v-for="cat in categories" 
                :key="cat.id"
                @click="selectedCategory = cat.id"
                :class="[
                  'px-4 py-2 rounded-xl font-bold text-sm whitespace-nowrap transition-all flex-shrink-0 snap-start',
                  selectedCategory === cat.id 
                    ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' 
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                ]"
              >
                {{ cat.icon }} {{ cat.name }}
              </button>
            </div>
            <!-- Scroll indicators -->
            <div class="absolute right-0 top-0 bottom-2 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none"></div>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto p-4">
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3">
            <div 
              v-for="product in filteredProducts" 
              :key="product.id"
              @click="addToCart(product)"
              class="bg-white rounded-xl p-3 border-2 border-gray-100 hover:border-red-500 hover:shadow-lg transition-all cursor-pointer group"
            >
              <!-- Product Image -->
              <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg mb-2 overflow-hidden relative">
                <img 
                  v-if="product.image"
                  :src="product.image" 
                  :alt="product.name"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <Package :size="32" class="text-gray-300" />
                </div>
                
                <!-- Stock Badge -->
                <div class="absolute top-2 right-2">
                  <span 
                    :class="[
                      'text-[10px] font-black px-2 py-1 rounded-full',
                      product.stock > 10 ? 'bg-emerald-500 text-white' :
                      product.stock > 0 ? 'bg-amber-500 text-white' :
                      'bg-red-500 text-white'
                    ]"
                  >
                    {{ product.stock }} und
                  </span>
                </div>
              </div>

              <!-- Product Info -->
              <h3 class="font-black text-xs text-gray-900 mb-1 line-clamp-2 group-hover:text-red-600 transition-colors">
                {{ product.name }}
              </h3>
              <p class="text-[10px] text-gray-500 mb-2">{{ product.code }}</p>
              
              <!-- Price -->
              <div class="flex items-center justify-between">
                <span class="text-base font-black text-gray-900">S/ {{ product.price.toFixed(2) }}</span>
                <div class="w-6 h-6 bg-red-600 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                  <Plus :size="14" class="text-white" />
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-64">
            <PackageX :size="64" class="text-gray-300 mb-4" />
            <p class="text-gray-500 font-bold">No se encontraron productos</p>
            <button @click="searchQuery = ''; selectedCategory = null" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-colors">
              Limpiar filtros
            </button>
          </div>
        </div>
      </div>

      <!-- Center: Cart Items List - Hidden on mobile, overlay on tablet -->
      <div 
        :class="[
          'w-80 bg-white border-x border-gray-200 flex-col',
          'hidden lg:flex',
          showCart ? 'fixed lg:relative inset-y-0 right-80 z-40 flex' : ''
        ]"
      >
        <div class="p-4 border-b border-gray-200">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-black text-gray-900">Items Carrito</h2>
            <div class="flex items-center gap-2">
              <button 
                v-if="cart.length > 0"
                @click="clearCart"
                class="text-xs font-bold text-red-600 hover:bg-red-50 px-2 py-1 rounded-lg transition-colors"
              >
                Limpiar
              </button>
              <button 
                @click="showCart = false"
                class="lg:hidden w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center"
              >
                <X :size="18" />
              </button>
            </div>
          </div>

          <!-- Customer Info -->
          <div class="relative">
            <User :size="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input 
              v-model="customerName"
              type="text" 
              placeholder="Nombre del cliente"
              class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg focus:border-red-500 focus:ring-0 text-sm font-semibold"
            />
          </div>
        </div>

        <!-- Cart Items (scrollable) -->
        <div class="flex-1 overflow-y-auto p-3">
          <div v-if="cart.length === 0" class="flex flex-col items-center justify-center h-full text-center px-4">
            <ShoppingBag :size="48" class="text-gray-300 mb-3" />
            <p class="text-gray-500 font-bold text-sm">Carrito vacío</p>
            <p class="text-xs text-gray-400">Selecciona productos</p>
          </div>

          <div v-else class="space-y-2">
            <div 
              v-for="item in cart" 
              :key="item.id"
              class="bg-gray-50 rounded-lg p-2 border border-gray-200"
            >
              <div class="flex items-start gap-2">
                <div class="w-12 h-12 bg-white rounded-lg overflow-hidden flex-shrink-0">
                  <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
                  <div v-else class="w-full h-full flex items-center justify-center bg-gray-100">
                    <Package :size="16" class="text-gray-400" />
                  </div>
                </div>

                <div class="flex-1 min-w-0">
                  <h4 class="font-bold text-xs text-gray-900 truncate">{{ item.name }}</h4>
                  <p class="text-[10px] text-gray-500 mb-1">S/ {{ item.price.toFixed(2) }} c/u</p>
                  
                  <!-- Quantity Controls -->
                  <div class="flex items-center gap-1">
                    <button 
                      @click="decrementQuantity(item)"
                      class="w-6 h-6 bg-white border border-gray-200 rounded flex items-center justify-center hover:bg-gray-100 transition-colors"
                    >
                      <Minus :size="12" class="text-gray-600" />
                    </button>
                    <span class="w-8 text-center font-black text-xs text-gray-900">{{ item.quantity }}</span>
                    <button 
                      @click="incrementQuantity(item)"
                      class="w-6 h-6 bg-white border border-gray-200 rounded flex items-center justify-center hover:bg-gray-100 transition-colors"
                    >
                      <Plus :size="12" class="text-gray-600" />
                    </button>
                    <button 
                      @click="removeFromCart(item)"
                      class="ml-auto w-6 h-6 bg-red-50 rounded flex items-center justify-center hover:bg-red-100 transition-colors"
                    >
                      <Trash2 :size="12" class="text-red-600" />
                    </button>
                  </div>
                </div>

                <div class="text-right">
                  <p class="font-black text-sm text-gray-900">S/ {{ (item.price * item.quantity).toFixed(2) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Summary & Actions - Hidden on tablets, visible on desktop -->
      <div 
        :class="[
          'w-80 bg-white border-r border-gray-200 flex-col',
          'hidden xl:flex',
          showCart ? 'fixed xl:relative inset-y-0 right-0 z-40 flex' : ''
        ]"
      >
        <div class="p-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-gray-900">Resumen</h2>
            <button 
              @click="showCart = false"
              class="xl:hidden w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center"
            >
              <X :size="18" />
            </button>
          </div>
        </div>

        <div v-if="cart.length > 0" class="flex-1 flex flex-col p-4">
          <!-- Totals -->
          <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600 font-semibold">Subtotal</span>
              <span class="font-bold text-gray-900">S/ {{ subtotal.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600 font-semibold">IGV (18%)</span>
              <span class="font-bold text-gray-900">S/ {{ tax.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-xl pt-2 border-t border-gray-200">
              <span class="font-black text-gray-900">TOTAL</span>
              <span class="font-black text-red-600">S/ {{ total.toFixed(2) }}</span>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="mb-4">
            <label class="block text-xs font-black text-gray-700 uppercase mb-2">Método de Pago</label>
            <div class="grid grid-cols-3 gap-2">
              <button 
                v-for="method in paymentMethods" 
                :key="method.id"
                @click="selectedPaymentMethod = method.id"
                :class="[
                  'py-2.5 rounded-lg font-bold text-xs transition-all',
                  selectedPaymentMethod === method.id
                    ? 'bg-red-600 text-white shadow-lg'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                ]"
              >
                {{ method.icon }} {{ method.name }}
              </button>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-auto space-y-2">
            <button 
              @click="completeSale"
              class="w-full py-3.5 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl font-black text-base shadow-xl shadow-red-600/30 hover:shadow-red-600/40 transition-all hover:scale-105 active:scale-95 flex items-center justify-center gap-2"
            >
              <Check :size="20" />
              Finalizar Venta
            </button>
            <button 
              class="w-full py-2.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors text-sm"
            >
              Guardar para después
            </button>
          </div>
        </div>

        <div v-else class="flex-1 flex items-center justify-center p-6 text-center">
          <div>
            <ShoppingBag :size="64" class="text-gray-300 mb-4 mx-auto" />
            <p class="text-gray-500 font-bold mb-2">Sin items</p>
            <p class="text-xs text-gray-400">Agrega productos para ver el resumen</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Overlay for mobile/tablet cart -->
    <div 
      v-if="showCart" 
      @click="showCart = false"
      class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    ></div>

    <!-- Success Modal -->
    <transition name="modal">
      <div v-if="showSuccessModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl transform scale-100 animate-in">
          <div class="text-center">
            <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/30">
              <Check :size="40" class="text-white" />
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">¡Venta Completada!</h3>
            <p class="text-gray-600 mb-1">Venta #{{ lastSaleId }}</p>
            <p class="text-3xl font-black text-red-600 mb-6">S/ {{ lastSaleTotal }}</p>
            
            <div class="space-y-3">
              <button 
                @click="printReceipt"
                class="w-full py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-gray-800 transition-colors flex items-center justify-center gap-2"
              >
                <Printer :size="20" />
                Imprimir Comprobante
              </button>
              <button 
                @click="closeSuccessModal"
                class="w-full py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-colors"
              >
                Nueva Venta
              </button>
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
import { authStore } from '@/store/auth'
import {
  ShoppingCart,
  Search,
  SlidersHorizontal,
  Package,
  PackageX,
  Plus,
  Minus,
  Trash2,
  User,
  ShoppingBag,
  Check,
  Clock,
  Receipt,
  Printer,
  X
} from 'lucide-vue-next'

const router = useRouter()
const currentBranch = computed(() => authStore.state.current_branch)
const currentDate = new Date().toLocaleDateString('es-PE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })

// Search & Filters
const searchQuery = ref('')
const selectedCategory = ref(null)
const showFilters = ref(false)
const showCart = ref(false)

// Cart
const cart = ref([])
const customerName = ref('')
const selectedPaymentMethod = ref('cash')

// Modal
const showSuccessModal = ref(false)
const lastSaleId = ref('')
const lastSaleTotal = ref(0)

// Categories
const categories = [
  { id: null, name: 'Todos', icon: '🏷️' },
  { id: 'pollo', name: 'Pollo', icon: '🍗' },
  { id: 'guarniciones', name: 'Guarniciones', icon: '🍟' },
  { id: 'bebidas', name: 'Bebidas', icon: '🥤' },
  { id: 'extras', name: 'Extras', icon: '🧂' },
]

// Payment Methods
const paymentMethods = [
  { id: 'cash', name: 'Efectivo', icon: '💵' },
  { id: 'card', name: 'Tarjeta', icon: '💳' },
  { id: 'yape', name: 'Yape', icon: '📱' },
]

// Mock Products
const products = [
  { id: 1, code: 'POL-001', name: 'Pollo a la Brasa 1/4', price: 15.00, stock: 45, category: 'pollo', image: null },
  { id: 2, code: 'POL-002', name: 'Pollo a la Brasa 1/2', price: 28.00, stock: 32, category: 'pollo', image: null },
  { id: 3, code: 'POL-003', name: 'Pollo a la Brasa Entero', price: 52.00, stock: 18, category: 'pollo', image: null },
  { id: 4, code: 'GUA-001', name: 'Papas Fritas', price: 8.00, stock: 60, category: 'guarniciones', image: null },
  { id: 5, code: 'GUA-002', name: 'Ensalada Mixta', price: 6.00, stock: 40, category: 'guarniciones', image: null },
  { id: 6, code: 'GUA-003', name: 'Arroz Chaufa', price: 14.00, stock: 25, category: 'guarniciones', image: null },
  { id: 7, code: 'BEB-001', name: 'Inca Kola 1.5L', price: 6.00, stock: 80, category: 'bebidas', image: null },
  { id: 8, code: 'BEB-002', name: 'Coca Cola 1.5L', price: 6.00, stock: 75, category: 'bebidas', image: null },
  { id: 9, code: 'BEB-003', name: 'Chicha Morada 1L', price: 5.00, stock: 30, category: 'bebidas', image: null },
  { id: 10, code: 'EXT-001', name: 'Cremas (Ocopa, Huancaína)', price: 4.00, stock: 50, category: 'extras', image: null },
  { id: 11, code: 'EXT-002', name: 'Ají Especial', price: 2.00, stock: 100, category: 'extras', image: null },
  { id: 12, code: 'POL-004', name: 'Alitas BBQ (6 und)', price: 18.00, stock: 22, category: 'pollo', image: null },
]

// Computed
const filteredProducts = computed(() => {
  let filtered = products

  if (selectedCategory.value) {
    filtered = filtered.filter(p => p.category === selectedCategory.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(p => 
      p.name.toLowerCase().includes(query) || 
      p.code.toLowerCase().includes(query)
    )
  }

  return filtered
})

const subtotal = computed(() => {
  return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const tax = computed(() => subtotal.value * 0.18)
const total = computed(() => subtotal.value + tax.value)

// Methods
const addToCart = (product) => {
  const existing = cart.value.find(item => item.id === product.id)
  
  if (existing) {
    if (existing.quantity < product.stock) {
      existing.quantity++
    }
  } else {
    cart.value.push({
      ...product,
      quantity: 1
    })
  }
}

const incrementQuantity = (item) => {
  const product = products.find(p => p.id === item.id)
  if (item.quantity < product.stock) {
    item.quantity++
  }
}

const decrementQuantity = (item) => {
  if (item.quantity > 1) {
    item.quantity--
  }
}

const removeFromCart = (item) => {
  const index = cart.value.findIndex(i => i.id === item.id)
  if (index > -1) {
    cart.value.splice(index, 1)
  }
}

const clearCart = () => {
  if (confirm('¿Estás seguro de limpiar el carrito?')) {
    cart.value = []
    customerName.value = ''
  }
}

const completeSale = () => {
  // Generate sale ID
  lastSaleId.value = `#${Math.floor(10000 + Math.random() * 90000)}`
  lastSaleTotal.value = total.value.toFixed(2)
  
  // Show success modal
  showSuccessModal.value = true
}

const closeSuccessModal = () => {
  showSuccessModal.value = false
  cart.value = []
  customerName.value = ''
  selectedPaymentMethod.value = 'cash'
}

const printReceipt = () => {
  alert('Función de impresión: Aquí se enviaría a la impresora térmica')
  closeSuccessModal()
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

* {
  font-family: 'Inter', sans-serif;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;
}

.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
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
