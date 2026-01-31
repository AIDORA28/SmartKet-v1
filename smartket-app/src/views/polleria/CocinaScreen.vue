<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Monitor de Cocina</h1>
    <div class="flex items-center gap-2 mb-4">
      <button class="btn-secondary" @click="reload" :disabled="busy">Recargar</button>
      <span v-if="message" :class="msgClass" class="text-sm">{{ message }}</span>
    </div>

    <div v-if="busy" class="text-center">Cargando pedidos...</div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Columna Pendiente -->
      <div class="bg-gray-100 p-4 rounded-lg">
        <h2 class="font-bold text-lg mb-3">Pendiente</h2>
        <div class="space-y-3">
          <div v-for="order in pendingOrders" :key="order.id" class="bg-white p-3 rounded-md shadow">
            <div class="flex justify-between font-semibold">
              <span>Mesa {{ order.table }}</span>
              <span class="text-sm text-gray-500">#{{ order.id }}</span>
            </div>
            <ul class="text-sm list-disc ml-5 mt-2">
              <li v-for="item in order.items" :key="item.product_id">{{ item.name }} × {{ item.qty }}</li>
            </ul>
            <button @click="updateOrderStatus(order, 'preparing')" class="btn-primary mt-3 w-full">
              Empezar a Preparar
            </button>
          </div>
        </div>
      </div>

      <!-- Columna En Preparación -->
      <div class="bg-blue-100 p-4 rounded-lg">
        <h2 class="font-bold text-lg mb-3 text-blue-800">En Preparación</h2>
        <div class="space-y-3">
          <div v-for="order in preparingOrders" :key="order.id" class="bg-white p-3 rounded-md shadow">
            <div class="flex justify-between font-semibold">
              <span>Mesa {{ order.table }}</span>
              <span class="text-sm text-gray-500">#{{ order.id }}</span>
            </div>
            <ul class="text-sm list-disc ml-5 mt-2">
              <li v-for="item in order.items" :key="item.product_id">{{ item.name }} × {{ item.qty }}</li>
            </ul>
            <button @click="updateOrderStatus(order, 'prepared')" class="btn-primary mt-3 w-full bg-green-600 hover:bg-green-700">
              Marcar como Listo
            </button>
          </div>
        </div>
      </div>

      <!-- Columna Listo -->
      <div class="bg-green-100 p-4 rounded-lg">
        <h2 class="font-bold text-lg mb-3 text-green-800">Listo para Servir</h2>
        <div class="space-y-3">
          <div v-for="order in preparedOrders" :key="order.id" class="bg-white p-3 rounded-md shadow-sm">
            <div class="flex justify-between font-semibold">
              <span>Mesa {{ order.table }}</span>
              <span class="text-sm text-gray-500">#{{ order.id }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { api, audit } from '@/api'

const orders = ref([])
const busy = ref(false)
const message = ref('')
const msgClass = ref('text-gray-600')

const pendingOrders = computed(() => orders.value.filter(o => o.status === 'pending'));
const preparingOrders = computed(() => orders.value.filter(o => o.status === 'preparing'));
const preparedOrders = computed(() => orders.value.filter(o => o.status === 'prepared'));

async function reload() {
  message.value = ''
  busy.value = true
  try {
    const { data } = await api.get('/polleria/kitchen/orders')
    orders.value = Array.isArray(data) ? data : []
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error al cargar pedidos'
  } finally {
    busy.value = false
  }
}

async function updateOrderStatus(order, newStatus) {
  order.updating = true;
  try {
    // Asumo que el endpoint para actualizar es PUT /polleria/orders/{id}/status
    // o algo similar. Lo ajustaré si es necesario.
    const { data } = await api.put(`/polleria/orders/${order.id}/${newStatus}`);
    audit(`kitchen_order_${newStatus}`, `Pedido marcado como ${newStatus}`, '/polleria/cocina', { orderId: order.id });
    message.value = data?.message || 'Pedido actualizado';
    msgClass.value = 'text-green-700';
    
    // Actualizar el estado localmente para reflejar el cambio en la UI
    const updatedOrder = orders.value.find(o => o.id === order.id);
    if (updatedOrder) {
      updatedOrder.status = newStatus;
    }

  } catch (err) {
    msgClass.value = 'text-red-700';
    message.value = err?.response?.data?.message || err.message || 'Error al actualizar pedido';
  } finally {
    order.updating = false;
  }
}

onMounted(reload)
</script>

<style scoped>
.btn-primary { background: #4f46e5; color: white; padding: .5rem .75rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .5rem .75rem; border-radius: .5rem; }
</style>

