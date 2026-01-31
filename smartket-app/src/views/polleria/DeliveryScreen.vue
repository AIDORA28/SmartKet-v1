<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Gestión de Delivery</h1>
    <div class="flex items-center gap-2 mb-4">
      <button class="btn-secondary" @click="reload" :disabled="busy">Recargar</button>
      <span class="text-sm text-gray-600">Intervalo: {{ pollInterval / 1000 }}s</span>
      <span v-if="message" :class="msgClass" class="text-sm">{{ message }}</span>
    </div>

    <div v-if="busy" class="text-center">Cargando entregas...</div>
    
    <div v-else class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
      <div v-for="d in deliveries" :key="d.id" 
           :class="['p-4 rounded-lg shadow', statusColor(d.status)]">
        <div class="flex justify-between items-start">
          <div>
            <p class="font-bold text-lg">{{ d.customer_name }}</p>
            <p class="text-sm">{{ d.address }}</p>
          </div>
          <span class="text-xs font-mono bg-gray-200 px-2 py-1 rounded">#{{ d.id }}</span>
        </div>
        <div class="mt-4">
          <p class="text-sm font-semibold">Estado: <span class="capitalize">{{ d.status.replace('_', ' ') }}</span></p>
        </div>
        <div class="mt-3 flex gap-2">
          <button v-if="d.status === 'pending'" class="btn-primary w-full" @click="updateStatus(d, 'en_ruta')" :disabled="d.updating">
            Marcar en Ruta
          </button>
          <button v-if="d.status === 'en_ruta'" class="btn-primary w-full bg-green-600 hover:bg-green-700" @click="updateStatus(d, 'entregado')" :disabled="d.updating">
            Marcar como Entregado
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { api, audit } from '@/api'

const deliveries = ref([])
const busy = ref(false)
const message = ref('')
const msgClass = ref('text-gray-600')
const pollInterval = Number(import.meta.env.VITE_REALTIME_POLL_INTERVAL_MS || 10000)
let timer = null

async function reload() {
  if (busy.value) return;
  message.value = ''
  busy.value = true
  try {
    const { data } = await api.get('/polleria/delivery')
    deliveries.value = Array.isArray(data) ? data : []
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error al cargar entregas'
  } finally { busy.value = false }
}

async function updateStatus(d, status) {
  d.updating = true
  try {
    const { data } = await api.patch(`/polleria/delivery/${d.id}`, { status })
    msgClass.value = 'text-green-700'
    message.value = data?.message || 'Actualizado'
    audit('delivery_status_change', 'Cambio de estado en delivery', '/polleria/delivery', { id: d.id, status })
    d.status = status
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error al actualizar estado'
  } finally { d.updating = false }
}

function statusColor(status) {
  switch (status) {
    case 'pending': return 'bg-yellow-100';
    case 'en_ruta': return 'bg-blue-100';
    case 'entregado': return 'bg-green-100';
    default: return 'bg-gray-100';
  }
}

function startPolling() {
  stopPolling()
  timer = setInterval(reload, pollInterval)
}
function stopPolling() { if (timer) { clearInterval(timer); timer = null } }

onMounted(() => { reload(); startPolling() })
onBeforeUnmount(stopPolling)
</script>

<style scoped>
.btn-primary { background: #4f46e5; color: white; padding: .5rem .75rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .5rem .75rem; border-radius: .5rem; }
.capitalize { text-transform: capitalize; }
</style>

