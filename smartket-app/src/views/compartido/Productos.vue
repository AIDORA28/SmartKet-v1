<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-8 py-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/20">
            <Package :size="28" class="text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-black text-gray-900">Gestión de Productos</h1>
            <p class="text-sm text-gray-500 font-semibold">Catálogo completo • {{ products.length }} productos</p>
          </div>
        </div>

        <button 
          @click="openCreateModal"
          class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-xl font-bold shadow-lg shadow-purple-600/30 hover:shadow-purple-600/40 transition-all hover:scale-105 flex items-center gap-2"
        >
          <Plus :size="20" />
          Nuevo Producto
        </button>
      </div>
    </div>

    <div class="px-8 py-8 max-w-[1800px] mx-auto">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
              <Package :size="24" class="text-purple-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total Productos</p>
          <h3 class="text-3xl font-black text-gray-900">{{ products.length }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
              <TrendingUp :size="24" class="text-emerald-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Stock Alto</p>
          <h3 class="text-3xl font-black text-gray-900">{{ highStockCount }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
              <AlertTriangle :size="24" class="text-amber-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Stock Bajo</p>
          <h3 class="text-3xl font-black text-gray-900">{{ lowStockCount }}</h3>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center">
              <XCircle :size="24" class="text-red-600" />
            </div>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase mb-1">Agotados</p>
          <h3 class="text-3xl font-black text-gray-900">{{ outOfStockCount }}</h3>
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
              placeholder="Buscar por nombre, código o categoría..."
              class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-semibold text-gray-900"
            />
          </div>

          <select 
            v-model="filterCategory"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold text-gray-700"
          >
            <option value="">Todas las categorías</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>

          <select 
            v-model="filterStock"
            class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold text-gray-700"
          >
            <option value="">Todo el stock</option>
            <option value="high">Stock Alto</option>
            <option value="low">Stock Bajo</option>
            <option value="out">Agotado</option>
          </select>
        </div>
      </div>

      <!-- Products Table -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Producto</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Código</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Costo</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr 
                v-for="product in filteredProducts" 
                :key="product.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                      <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover" />
                      <div v-else class="w-full h-full flex items-center justify-center">
                        <Package :size="20" class="text-gray-400" />
                      </div>
                    </div>
                    <div>
                      <h4 class="font-bold text-gray-900">{{ product.name }}</h4>
                      <p class="text-xs text-gray-500">{{ product.description }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="font-mono text-sm font-bold text-gray-700">{{ product.code }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                    {{ product.category }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-black text-gray-900">S/ {{ product.price.toFixed(2) }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-semibold text-gray-600">S/ {{ product.cost.toFixed(2) }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-900">{{ product.stock }}</span>
                    <div :class="[
                      'w-2 h-2 rounded-full',
                      product.stock > product.minStock * 2 ? 'bg-emerald-500' :
                      product.stock > product.minStock ? 'bg-amber-500' :
                      'bg-red-500'
                    ]"></div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span :class="[
                    'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold',
                    product.active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700'
                  ]">
                    {{ product.active ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-2">
                    <button 
                      @click="openEditModal(product)"
                      class="p-2 hover:bg-purple-50 rounded-lg transition-colors"
                      title="Editar"
                    >
                      <Pencil :size="18" class="text-purple-600" />
                    </button>
                    <button 
                      @click="openStockModal(product)"
                      class="p-2 hover:bg-blue-50 rounded-lg transition-colors"
                      title="Ajustar Stock"
                    >
                      <Box :size="18" class="text-blue-600" />
                    </button>
                    <button 
                      @click="viewInInventory(product)"
                      class="p-2 hover:bg-cyan-50 rounded-lg transition-colors"
                      title="Ver en Inventario"
                    >
                      <ArrowRight :size="18" class="text-cyan-600" />
                    </button>
                    <button 
                      @click="toggleActive(product)"
                      class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
                      :title="product.active ? 'Desactivar' : 'Activar'"
                    >
                      <component :is="product.active ? EyeOff : Eye" :size="18" class="text-gray-600" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty State -->
          <div v-if="filteredProducts.length === 0" class="text-center py-16">
            <PackageX :size="64" class="text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500 font-bold mb-2">No se encontraron productos</p>
            <button 
              @click="searchQuery = ''; filterCategory = ''; filterStock = ''"
              class="text-purple-600 hover:text-purple-700 font-bold text-sm"
            >
              Limpiar filtros
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Product Modal -->
    <transition name="modal">
      <div v-if="showProductModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl my-8">
          <h3 class="text-2xl font-black text-gray-900 mb-6">
            {{ editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Nombre del Producto *</label>
              <input 
                v-model="formProduct.name"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-semibold"
                placeholder="Ej: Pollo a la Brasa 1/4"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-black text-gray-700 mb-2">Descripción</label>
              <textarea 
                v-model="formProduct.description"
                rows="2"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0"
                placeholder="Descripción breve del producto..."
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Código *</label>
              <input 
                v-model="formProduct.code"
                type="text" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-mono font-bold"
                placeholder="POL-001"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Categoría *</label>
              <select 
                v-model="formProduct.category"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold"
              >
                <option value="">Seleccionar...</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Precio de Venta (S/) *</label>
              <input 
                v-model.number="formProduct.price"
                type="number" 
                step="0.01"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold"
                placeholder="0.00"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Costo (S/) *</label>
              <input 
                v-model.number="formProduct.cost"
                type="number" 
                step="0.01"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold"
                placeholder="0.00"
              />
              <p v-if="formProduct.price && formProduct.cost" class="text-xs text-gray-500 mt-1">
                Margen: {{ ((formProduct.price - formProduct.cost) / formProduct.cost * 100).toFixed(1) }}%
              </p>
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Stock Inicial *</label>
              <input 
                v-model.number="formProduct.stock"
                type="number" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold"
                placeholder="0"
              />
            </div>

            <div>
              <label class="block text-sm font-black text-gray-700 mb-2">Stock Mínimo *</label>
              <input 
                v-model.number="formProduct.minStock"
                type="number" 
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 font-bold"
                placeholder="10"
              />
            </div>
          </div>

          <div class="flex gap-3">
            <button 
              @click="closeProductModal"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="saveProduct"
              class="flex-1 py-3 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 transition-colors"
            >
              {{ editingProduct ? 'Guardar Cambios' : 'Crear Producto' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Stock Adjustment Modal -->
    <transition name="modal">
      <div v-if="showStockModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
          <h3 class="text-2xl font-black text-gray-900 mb-2">Ajustar Stock</h3>
          <p class="text-gray-600 mb-6">{{ stockProduct?.name }}</p>

          <div class="bg-gray-50 rounded-xl p-4 mb-6">
            <p class="text-xs text-gray-500 mb-1">Stock Actual</p>
            <p class="text-4xl font-black text-gray-900">{{ stockProduct?.stock }}</p>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-black text-gray-700 mb-2">Tipo de Ajuste</label>
            <div class="grid grid-cols-2 gap-3">
              <button 
                @click="stockAdjustment.type = 'add'"
                :class="[
                  'py-3 rounded-xl font-bold transition-all',
                  stockAdjustment.type === 'add' 
                    ? 'bg-emerald-600 text-white' 
                    : 'bg-gray-100 text-gray-700'
                ]"
              >
                + Agregar
              </button>
              <button 
                @click="stockAdjustment.type = 'remove'"
                :class="[
                  'py-3 rounded-xl font-bold transition-all',
                  stockAdjustment.type === 'remove' 
                    ? 'bg-red-600 text-white' 
                    : 'bg-gray-100 text-gray-700'
                ]"
              >
                - Restar
              </button>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-black text-gray-700 mb-2">Cantidad</label>
            <input 
              v-model.number="stockAdjustment.quantity"
              type="number" 
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 text-center text-2xl font-black"
              placeholder="0"
            />
            <p v-if="stockAdjustment.quantity" class="text-sm text-gray-600 mt-2 text-center">
              Nuevo stock: {{ stockAdjustment.type === 'add' ? (stockProduct?.stock || 0) + stockAdjustment.quantity : (stockProduct?.stock || 0) - stockAdjustment.quantity }}
            </p>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-black text-gray-700 mb-2">Motivo</label>
            <textarea 
              v-model="stockAdjustment.reason"
              rows="2"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0"
              placeholder="Motivo del ajuste..."
            ></textarea>
          </div>

          <div class="flex gap-3">
            <button 
              @click="closeStockModal"
              class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button 
              @click="adjustStock"
              class="flex-1 py-3 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 transition-colors"
            >
              Aplicar Ajuste
            </button>
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
  Package,
  Plus,
  Search,
  TrendingUp,
  AlertTriangle,
  XCircle,
  Pencil,
  Box,
  Eye,
  EyeOff,
  PackageX,
  ArrowRight
} from 'lucide-vue-next'

const router = useRouter()

// Search & Filters
const searchQuery = ref('')
const filterCategory = ref('')
const filterStock = ref('')

// Modals
const showProductModal = ref(false)
const showStockModal = ref(false)
const editingProduct = ref(null)
const stockProduct = ref(null)

// Form
const formProduct = ref({
  name: '',
  description: '',
  code: '',
  category: '',
  price: 0,
  cost: 0,
  stock: 0,
  minStock: 10,
  active: true
})

const stockAdjustment = ref({
  type: 'add',
  quantity: 0,
  reason: ''
})

// Categories
const categories = ['Pollo', 'Guarniciones', 'Bebidas', 'Extras', 'Combos']

// Mock Products
const products = ref([
  { id: 1, code: 'POL-001', name: 'Pollo a la Brasa 1/4', description: 'Cuarto de pollo con papas', category: 'Pollo', price: 15.00, cost: 8.00, stock: 45, minStock: 10, active: true, image: null },
  { id: 2, code: 'POL-002', name: 'Pollo a la Brasa 1/2', description: 'Medio pollo con papas', category: 'Pollo', price: 28.00, cost: 15.00, stock: 32, minStock: 10, active: true, image: null },
  { id: 3, code: 'POL-003', name: 'Pollo a la Brasa Entero', description: 'Pollo entero con papas', category: 'Pollo', price: 52.00, cost: 28.00, stock: 18, minStock: 5, active: true, image: null },
  { id: 4, code: 'GUA-001', name: 'Papas Fritas', description: 'Porción grande', category: 'Guarniciones', price: 8.00, cost: 3.00, stock: 60, minStock: 20, active: true, image: null },
  { id: 5, code: 'GUA-002', name: 'Ensalada Mixta', description: 'Ensalada fresca', category: 'Guarniciones', price: 6.00, cost: 2.50, stock: 40, minStock: 15, active: true, image: null },
  { id: 6, code: 'GUA-003', name: 'Arroz Chaufa', description: 'Arroz chaufa especial', category: 'Guarniciones', price: 14.00, cost: 6.00, stock: 25, minStock: 10, active: true, image: null },
  { id: 7, code: 'BEB-001', name: 'Inca Kola 1.5L', description: 'Bebida 1.5 litros', category: 'Bebidas', price: 6.00, cost: 3.50, stock: 80, minStock: 30, active: true, image: null },
  { id: 8, code: 'BEB-002', name: 'Coca Cola 1.5L', description: 'Bebida 1.5 litros', category: 'Bebidas', price: 6.00, cost: 3.50, stock: 75, minStock: 30, active: true, image: null },
  { id: 9, code: 'BEB-003', name: 'Chicha Morada 1L', description: 'Chicha morada natural', category: 'Bebidas', price: 5.00, cost: 2.00, stock: 4, minStock: 10, active: true, image: null },
  { id: 10, code: 'EXT-001', name: 'Cremas', description: 'Ocopa o Huancaína', category: 'Extras', price: 4.00, cost: 1.50, stock: 50, minStock: 20, active: true, image: null },
  { id: 11, code: 'EXT-002', name: 'Ají Especial', description: 'Porción de ají', category: 'Extras', price: 2.00, cost: 0.50, stock: 100, minStock: 40, active: true, image: null },
  { id: 12, code: 'POL-004', name: 'Alitas BBQ', description: '6 unidades', category: 'Pollo', price: 18.00, cost: 9.00, stock: 0, minStock: 10, active: false, image: null },
])

// Computed
const filteredProducts = computed(() => {
  let filtered = products.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(p => 
      p.name.toLowerCase().includes(query) || 
      p.code.toLowerCase().includes(query) ||
      p.category.toLowerCase().includes(query)
    )
  }

  if (filterCategory.value) {
    filtered = filtered.filter(p => p.category === filterCategory.value)
  }

  if (filterStock.value) {
    if (filterStock.value === 'high') {
      filtered = filtered.filter(p => p.stock > p.minStock * 2)
    } else if (filterStock.value === 'low') {
      filtered = filtered.filter(p => p.stock > 0 && p.stock <= p.minStock * 2)
    } else if (filterStock.value === 'out') {
      filtered = filtered.filter(p => p.stock === 0)
    }
  }

  return filtered
})

const highStockCount = computed(() => products.value.filter(p => p.stock > p.minStock * 2).length)
const lowStockCount = computed(() => products.value.filter(p => p.stock > 0 && p.stock <= p.minStock * 2).length)
const outOfStockCount = computed(() => products.value.filter(p => p.stock === 0).length)

// Methods
const openCreateModal = () => {
  editingProduct.value = null
  formProduct.value = {
    name: '',
    description: '',
    code: '',
    category: '',
    price: 0,
    cost: 0,
    stock: 0,
    minStock: 10,
    active: true
  }
  showProductModal.value = true
}

const openEditModal = (product) => {
  editingProduct.value = product
  formProduct.value = { ...product }
  showProductModal.value = true
}

const closeProductModal = () => {
  showProductModal.value = false
  editingProduct.value = null
}

const saveProduct = () => {
  if (editingProduct.value) {
    // Update existing
    const index = products.value.findIndex(p => p.id === editingProduct.value.id)
    if (index > -1) {
      products.value[index] = { ...formProduct.value }
    }
  } else {
    // Create new
    const newProduct = {
      ...formProduct.value,
      id: products.value.length + 1,
      image: null
    }
    products.value.push(newProduct)
  }
  
  closeProductModal()
}

const openStockModal = (product) => {
  stockProduct.value = product
  stockAdjustment.value = {
    type: 'add',
    quantity: 0,
    reason: ''
  }
  showStockModal.value = true
}

const closeStockModal = () => {
  showStockModal.value = false
  stockProduct.value = null
}

const adjustStock = () => {
  if (!stockAdjustment.value.quantity) {
    alert('Ingresa una cantidad')
    return
  }

  const product = products.value.find(p => p.id === stockProduct.value.id)
  if (product) {
    if (stockAdjustment.value.type === 'add') {
      product.stock += stockAdjustment.value.quantity
    } else {
      product.stock = Math.max(0, product.stock - stockAdjustment.value.quantity)
    }
  }
  
  closeStockModal()
}

const toggleActive = (product) => {
  product.active = !product.active
}

const viewInInventory = (product) => {
  router.push({ 
    name: 'inventario', 
    query: { search: product.name }
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
