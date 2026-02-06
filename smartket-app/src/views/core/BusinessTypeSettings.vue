<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">Tipo de negocio</h1>
    <p class="text-sm text-gray-600 mb-6">Selecciona el tipo de negocio para ajustar las funcionalidades del sistema. Podrás cambiarlo más adelante con confirmación.</p>

    <div class="grid grid-cols-2 gap-4">
      <label v-for="opt in options" :key="opt.value" class="border rounded-xl p-4 cursor-pointer hover:shadow-sm">
        <input type="radio" name="rubro" class="sr-only" :value="opt.value" v-model="selected" />
        <div class="flex items-center gap-3">
          <div class="text-2xl">{{ opt.emoji }}</div>
          <div>
            <div class="font-semibold text-gray-900">{{ opt.label }}</div>
            <div class="text-sm text-gray-600">{{ opt.help }}</div>
          </div>
        </div>
      </label>
    </div>

    <div class="mt-6">
      <h2 class="text-base font-semibold text-gray-800 mb-2">Previsualización</h2>
      <ul class="text-sm text-gray-700 list-disc ml-5 space-y-1">
        <li v-for="feat in preview" :key="feat">{{ feat }}</li>
      </ul>
    </div>

    <div class="mt-6 flex items-center gap-3">
      <button class="px-4 py-2 rounded-lg bg-gray-100 text-gray-800" @click="reload">Recargar actual</button>
      <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white" :disabled="busy" @click="save">Guardar cambios</button>
      <span v-if="message" :class="msgClass" class="text-sm">{{ message }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { api } from '@/api'

const options = [
  { value: 'minimarket', label: 'Minimarket', emoji: '🛒', help: 'POS, Inventario, Reportes', preview: ['POS rápido', 'Gestión de inventario', 'Reportes de ventas'] },
  { value: 'polleria', label: 'Pollería', emoji: '🍗', help: 'Recetas, Insumos, Ventas', preview: ['Recetas e insumos', 'Combos y menús', 'Ventas y reportes'] },
  { value: 'restaurante', label: 'Restaurante', emoji: '🍽️', help: 'Mesas, Comandas, POS', preview: ['Mesas y comandas', 'Cocina y pedidos', 'POS integrado'] },
  { value: 'farmacia', label: 'Farmacia', emoji: '💊', help: 'Lotes, Vencimientos, POS', preview: ['Lotes y vencimientos', 'Stock por producto', 'POS y reportes'] },
]

const busy = ref(false)
const message = ref('')
const selected = ref('')

const preview = computed(() => {
  const found = options.find(o => o.value === selected.value)
  return found?.preview || []
})

async function reload() {
  message.value = ''
  busy.value = true
  try {
    const { data } = await api.get('/tenant')
    selected.value = data?.rubro || ''
    message.value = 'Datos cargados'
  } catch (err) {
    message.value = err?.response?.data?.message || err.message || 'Error al cargar'
  } finally {
    busy.value = false
  }
}

async function save() {
  if (!selected.value) {
    message.value = 'Selecciona un tipo de negocio'
    return
  }
  if (!confirm('¿Confirmas cambiar el tipo de negocio?')) return
  message.value = ''
  busy.value = true
  try {
    const { data } = await api.patch('/tenant', { rubro: selected.value })
    message.value = data?.message || 'Actualizado'
  } catch (err) {
    message.value = err?.response?.data?.message || err.message || 'Error al actualizar'
  } finally {
    busy.value = false
  }
}

onMounted(() => reload())
</script>

<style scoped>
.text-gray-900 { color: #111827 }
.text-gray-800 { color: #1f2937 }
.text-gray-700 { color: #374151 }
.text-gray-600 { color: #4b5563 }
.bg-indigo-600 { background-color: #4f46e5 }
.bg-gray-100 { background-color: #f3f4f6 }
.rounded-lg { border-radius: .5rem }
.rounded-xl { border-radius: .75rem }
.hover\:shadow-sm:hover { box-shadow: 0 1px 2px rgba(0,0,0,.08) }
</style>

