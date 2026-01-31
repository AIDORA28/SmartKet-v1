<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Gestión de Inventario</h1>
    <div class="flex items-center gap-2 mb-4">
      <button class="btn-secondary" @click="reload" :disabled="busy">Recargar</button>
      <input type="text" v-model="searchQuery" class="input w-64" placeholder="Buscar producto..." />
      <span v-if="message" :class="msgClass" class="text-sm">{{ message }}</span>
    </div>

    <div v-if="busy" class="text-center">Cargando inventario...</div>
    
    <div v-else class="overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3">Producto</th>
            <th class="p-3">Stock Actual</th>
            <th class="p-3">Nivel de Stock</th>
            <th class="p-3 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredItems" :key="item.id" class="border-t hover:bg-gray-50">
            <td class="p-3 font-semibold">{{ item.name }}</td>
            <td class="p-3">
              <input type="number" class="input w-24 text-center" v-model.number="item.stock" />
            </td>
            <td class="p-3">
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full" :class="stockLevelColor(item.stock)" :style="{ width: stockLevelWidth(item.stock) }"></div>
              </div>
            </td>
            <td class="p-3 text-center">
              <button class="btn-primary" @click="save(item)" :disabled="item.saving">
                {{ item.saving ? 'Guardando...' : 'Guardar' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { api, audit } from '@/api'

const items = ref([])
const busy = ref(false)
const message = ref('')
const msgClass = ref('text-gray-600')
const searchQuery = ref('')

const filteredItems = computed(() => {
  if (!searchQuery.value) {
    return items.value;
  }
  return items.value.filter(item => 
    item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

async function reload() {
  message.value = ''
  busy.value = true
  try {
    const { data } = await api.get('/inventory/items')
    items.value = Array.isArray(data) ? data.map(item => ({ ...item, saving: false })) : []
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error al cargar inventario'
  } finally { busy.value = false }
}

async function save(item) {
  item.saving = true
  try {
    const { data } = await api.patch(`/inventory/items/${item.id}`, { stock: item.stock })
    msgClass.value = 'text-green-700'
    message.value = data?.message || 'Actualizado'
    audit('inventory_update', 'Actualización de stock', '/polleria/almacen', { id: item.id, stock: item.stock })
  } catch (err) {
    msgClass.value = 'text-red-700'
    message.value = err?.response?.data?.message || err.message || 'Error al guardar'
  } finally { item.saving = false }
}

function stockLevelColor(stock) {
  if (stock < 10) return 'bg-red-500';
  if (stock < 50) return 'bg-yellow-500';
  return 'bg-green-500';
}

function stockLevelWidth(stock) {
  // Asumimos un stock máximo de 100 para la visualización
  const percentage = Math.min((stock / 100) * 100, 100);
  return `${percentage}%`;
}

onMounted(reload)
</script>

<style scoped>
.input { border: 1px solid #d1d5db; border-radius: .5rem; padding: .5rem .75rem; }
.btn-primary { background: #4f46e5; color: white; padding: .5rem 1rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .5rem 1rem; border-radius: .5rem; }
</style>

