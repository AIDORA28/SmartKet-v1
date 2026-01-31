<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4 h-screen bg-gray-50">
    <!-- Columna de Productos -->
    <div class="lg:col-span-2 bg-white p-4 rounded-lg shadow">
      <h1 class="text-2xl font-bold mb-4">Punto de Venta</h1>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <ProductCard 
          v-for="p in products" 
          :key="p.id" 
          :product="p"
          @add-to-order="addToCart"
        />
      </div>
    </div>

    <!-- Columna de Carrito y Pago -->
    <div class="bg-white p-4 rounded-lg shadow flex flex-col">
      <h2 class="text-xl font-semibold mb-4">Carrito</h2>
      <div v-if="cart.length === 0" class="grow flex items-center justify-center text-gray-500">
        <p>Selecciona productos para la venta.</p>
      </div>
      <div v-else class="grow overflow-y-auto">
        <ul class="space-y-2">
          <li v-for="(item, index) in cart" :key="index" class="flex justify-between items-center">
            <div>
              <p class="font-semibold">{{ item.name }}</p>
              <p class="text-sm text-gray-600">{{ currency(item.price) }}</p>
            </div>
            <div class="flex items-center">
              <input type="number" v-model.number="item.qty" min="1" class="input w-16 text-center" />
              <button @click="remove(index)" class="ml-2 text-red-500 hover:text-red-700">&times;</button>
            </div>
          </li>
        </ul>
      </div>
      <div class="border-t pt-4">
        <div class="flex justify-between font-bold text-lg mb-4">
          <span>Total:</span>
          <span>{{ currency(total) }}</span>
        </div>
        <div class="space-y-3">
          <select v-model="payment.method" class="input w-full">
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="yape">Yape/Plin</option>
          </select>
          <button class="btn-primary w-full text-lg" :disabled="busy || !cart.length" @click="checkout">
            Cobrar
          </button>
        </div>
        <span v-if="message" :class="msgClass" class="text-sm mt-2 block">{{ message }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { api, audit } from '@/api'
import ProductCard from '@/components/ProductCard.vue'

const products = ref([])
const cart = ref([])
const payment = ref({ method: 'efectivo' })
const busy = ref(false)
const message = ref('')
const msgClass = ref('text-gray-600')

const total = computed(() => cart.value.reduce((s, it) => s + (it.price * it.qty), 0))

function addToCart(product) {
  const found = cart.value.find(it => it.id === product.id)
  if (found) {
    found.qty += 1
  } else {
    cart.value.push({ id: product.id, name: product.name, price: product.price, qty: 1 })
  }
}

function remove(idx) { cart.value.splice(idx, 1) }
function currency(v) { return new Intl.NumberFormat('es-PE',{style:'currency',currency:'PEN'}).format(v || 0) }

onMounted(async () => {
  try {
    const { data } = await api.get('/polleria/menu')
    products.value = Array.isArray(data) ? data : []
  } catch { products.value = [] }
})

async function checkout() {
  busy.value = true
  message.value = ''
  try {
    const payload = { items: cart.value.map(({ id, qty }) => ({ product_id: id, qty })), payment_method: payment.value.method }
    const { data } = await api.post('/polleria/sales', payload)
    msgClass.value = 'text-green-700'
    message.value = data?.message || 'Venta registrada'
    audit('pos_sale', 'Venta registrada en caja', '/polleria/caja', { total: total.value })
    cart.value = []
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error en cobro'
  } finally { busy.value = false }
}
</script>

<style scoped>
.input { border: 1px solid #d1d5db; border-radius: .5rem; padding: .5rem .75rem; }
.btn-primary { background: #4f46e5; color: white; padding: .75rem 1rem; border-radius: .5rem; font-weight: 600; }
</style>

