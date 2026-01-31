<template>
  <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Columna de Productos -->
    <div class="md:col-span-2">
      <h1 class="text-2xl font-bold mb-4">Menú</h1>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <ProductCard 
          v-for="p in products" 
          :key="p.id" 
          :product="p"
          @add-to-order="addToOrder"
        />
      </div>
    </div>

    <!-- Columna de Pedido -->
    <div class="bg-gray-50 p-4 rounded-lg">
      <h2 class="text-xl font-semibold mb-4">Pedido Actual</h2>
      <div class="mb-4">
        <label for="table-number" class="block text-sm font-medium text-gray-700">Mesa</label>
        <input id="table-number" v-model="order.table" class="input mt-1" placeholder="Ej: 12" />
      </div>
      
      <div v-if="order.items.length === 0" class="text-center text-gray-500">
        <p>Agrega productos desde el menú.</p>
      </div>

      <ul v-else class="space-y-2">
        <li v-for="(item, index) in order.items" :key="index" class="flex justify-between items-center">
          <div>
            <p class="font-semibold">{{ nameOf(item.product_id) }}</p>
            <p class="text-sm text-gray-600">{{ currency(priceOf(item.product_id)) }}</p>
          </div>
          <div class="flex items-center">
            <input type="number" v-model.number="item.qty" min="1" class="input w-16 text-center" />
            <button @click="removeItem(index)" class="ml-2 text-red-500 hover:text-red-700">
              &times;
            </button>
          </div>
        </li>
      </ul>

      <div class="mt-6 border-t pt-4">
        <div class="flex justify-between font-bold text-lg">
          <span>Total:</span>
          <span>{{ currency(total) }}</span>
        </div>
        <div class="mt-4 flex gap-2">
          <button class="btn-secondary w-full" @click="clear">Limpiar</button>
          <button class="btn-primary w-full" :disabled="busy || !order.items.length || !order.table" @click="submitOrder">Registrar pedido</button>
        </div>
        <span v-if="message" :class="msgClass" class="text-sm mt-2 block">{{ message }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { api, audit } from '@/api'
import ProductCard from '@/components/ProductCard.vue'

const products = ref([])
const order = ref({ table: '', items: [] })
const busy = ref(false)
const message = ref('')
const msgClass = ref('text-gray-600')

onMounted(async () => {
  try {
    const { data } = await api.get('/polleria/menu')
    products.value = Array.isArray(data) ? data : []
  } catch (err) {
    products.value = []
  }
})

const total = computed(() => {
  return order.value.items.reduce((acc, item) => {
    const product = products.value.find(p => p.id === item.product_id);
    return acc + (product ? product.price * item.qty : 0);
  }, 0);
});

function nameOf(id) {
  const p = products.value.find(x => x.id === id)
  return p ? p.name : `#${id}`
}

function priceOf(id) {
  const p = products.value.find(x => x.id === id)
  return p ? p.price : 0
}

function currency(v) { return new Intl.NumberFormat('es-PE',{style:'currency',currency:'PEN'}).format(v || 0) }

function addToOrder(product) {
  const existingItem = order.value.items.find(item => item.product_id === product.id);
  if (existingItem) {
    existingItem.qty++;
  } else {
    order.value.items.push({ product_id: product.id, qty: 1 });
  }
}

function removeItem(index) {
  order.value.items.splice(index, 1);
}

function clear() { order.value = { table: '', items: [] } }

async function submitOrder() {
  message.value = ''
  busy.value = true
  try {
    const { data } = await api.post('/polleria/orders', { ...order.value })
    msgClass.value = 'text-green-700'
    message.value = data?.message || 'Pedido registrado'
    audit('mesero_order_created', 'Pedido registrado por mesero', '/polleria/mesero', { table: order.value.table })
    clear()
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error al registrar pedido'
  } finally {
    busy.value = false
  }
}
</script>

<style scoped>
.input { border: 1px solid #d1d5db; border-radius: .5rem; padding: .5rem .75rem; width: 100%; }
.btn-primary { background: #4f46e5; color: white; padding: .5rem .75rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .5rem .75rem; border-radius: .5rem; }
.text-gray-700 { color: #374151 }
.text-green-700 { color: #15803d }
.text-red-700 { color: #b91c1c }
</style>

