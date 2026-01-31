<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Panel de Administración</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Sección de Planes -->
      <section class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Gestión de Planes</h2>
        <div class="flex gap-2 mb-2">
          <select v-model="plans.selected" class="input grow">
            <option value="">Selecciona un plan…</option>
            <option v-for="p in plans.list" :key="p.id" :value="p.id">{{ p.name }} — {{ currency(p.price) }}</option>
          </select>
          <button class="btn-primary" :disabled="plans.busy || !plans.selected" @click="activatePlan">
            {{ plans.busy ? 'Activando...' : 'Activar' }}
          </button>
        </div>
        <div class="text-sm min-h-[1.25rem]" v-if="plans.message" :class="plans.msgClass">{{ plans.message }}</div>
      </section>

      <!-- Sección de Sucursales -->
      <section class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Gestión de Sucursales</h2>
        <form class="grid gap-3" @submit.prevent="createBranch">
          <input v-model="branch.name" class="input" placeholder="Nombre de la nueva sucursal" required />
          <input v-model="branch.address" class="input" placeholder="Dirección" required />
          <button class="btn-primary" :disabled="branch.busy || !branch.name">
            {{ branch.busy ? 'Creando...' : 'Crear Sucursal' }}
          </button>
        </form>
        <div class="text-sm min-h-[1.25rem]" v-if="branch.message" :class="branch.msgClass">{{ branch.message }}</div>
        
        <div class="mt-4 border-t pt-4">
          <h3 class="font-semibold mb-2">Sucursales Existentes</h3>
          <ul class="text-sm space-y-2">
            <li v-for="b in branches" :key="b.id" class="p-2 bg-gray-50 rounded">{{ b.name }} — {{ b.address }}</li>
          </ul>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api, audit } from '@/api'

const branches = ref([])
const branch = ref({ name: '', address: '', busy: false, message: '', msgClass: '' })
const plans = ref({ list: [], selected: '', busy: false, message: '', msgClass: '' })

function currency(v) { return new Intl.NumberFormat('es-PE',{style:'currency',currency:'PEN'}).format(v || 0) }

onMounted(async () => {
  try {
    const [plansRes, branchesRes] = await Promise.all([
      api.get('/polleria/plans'),
      api.get('/polleria/branches'),
    ])
    plans.value.list = Array.isArray(plansRes.data) ? plansRes.data : []
    branches.value = Array.isArray(branchesRes.data) ? branchesRes.data : []
  } catch (err) {
    // Manejo de error general si alguna de las peticiones falla
    plans.value.message = 'Error al cargar datos iniciales';
    plans.value.msgClass = 'text-red-700';
  }
})

async function activatePlan() {
  plans.value.busy = true
  plans.value.message = ''
  try {
    const { data } = await api.post('/polleria/plans/activate', { plan_id: plans.value.selected })
    plans.value.msgClass = 'text-green-700'
    plans.value.message = data?.message || 'Plan activado correctamente'
    audit('admin_plan_activated', 'Plan activado', '/polleria/admin', { plan_id: plans.value.selected })
  } catch (err) {
    plans.value.msgClass = 'text-red-700'
    plans.value.message = err?.response?.data?.message || err.message || 'Error al activar el plan'
  } finally { 
    plans.value.busy = false 
  }
}

async function createBranch() {
  branch.value.busy = true
  branch.value.message = ''
  try {
    const { data } = await api.post('/polleria/branches', { name: branch.value.name, address: branch.value.address })
    branch.value.msgClass = 'text-green-700'
    branch.value.message = data?.message || 'Sucursal creada con éxito'
    audit('admin_branch_created', 'Sucursal creada', '/polleria/admin', { name: branch.value.name })
    branches.value.push({ id: data?.id || Date.now(), name: branch.value.name, address: branch.value.address })
    branch.value.name = ''
    branch.value.address = ''
  } catch (err) {
    branch.value.msgClass = 'text-red-700'
    branch.value.message = err?.response?.data?.message || err.message || 'Error al crear la sucursal'
  } finally { 
    branch.value.busy = false 
  }
}
</script>

<style scoped>
.input { border: 1px solid #d1d5db; border-radius: .5rem; padding: .5rem .75rem; width: 100%; }
.btn-primary { background: #4f46e5; color: white; padding: .5rem 1rem; border-radius: .5rem; }
.text-green-700 { color: #15803d }
.text-red-700 { color: #b91c1c }
.min-h-\[1\.25rem\] { min-height: 1.25rem; }
</style>

