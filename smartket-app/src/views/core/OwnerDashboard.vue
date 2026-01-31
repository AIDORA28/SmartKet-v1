<template>
  <div class="min-h-screen bg-[#F3F4F6] p-4 md:p-8"> <!-- Light gray background similar to v5 -->
    
    <!-- Welcome Header -->
    <div class="mb-8 flex justify-between items-center">
       <div>
          <div class="flex items-center gap-2 mb-1">
             <span class="text-2xl">👑</span>
             <h1 class="text-2xl font-bold text-gray-900">Panel de Control Empresarial - OWNER</h1>
          </div>
          <p class="text-sm text-gray-500">Gestión completa de empresas y sucursales - <span class="text-red-600 font-bold">SmartKet</span></p>
       </div>
    </div>

    <!-- Context Cards -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
       <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-4">
         <span class="material-icons text-red-500 text-sm">target</span>
         Gestión de Contexto
       </h3>
       
       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Empresa Selector -->
          <div>
             <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Empresa Activa</label>
             <div class="relative">
                <select class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-4 py-3 pr-8 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 font-medium cursor-pointer">
                   <option selected>{{ currentTenant?.business_name || 'Mi Empresa' }}</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                   <span class="material-icons text-sm">expand_more</span>
                </div>
             </div>
             <p class="text-xs text-gray-400 mt-1 pl-1">Plan: Profesional</p>
          </div>
          
          <!-- Sucursal Selector -->
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sucursal Activa</label>
             <div class="relative">
                <select 
                    v-model="selectedBranchId"
                    @change="updateBranch"
                    class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-4 py-3 pr-8 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 font-medium cursor-pointer"
                >
                   <option 
                      v-for="branch in branches" 
                      :key="branch.id" 
                      :value="branch.id"
                   >
                    {{ branch.name }}
                   </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <span class="material-icons text-sm">expand_more</span>
                </div>
             </div>
          </div>
       </div>
    </div>

    <!-- User & Config Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
       <!-- Active User Card -->
       <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 flex items-center gap-4">
          <div class="h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
             <span class="material-icons text-3xl">person</span>
          </div>
          <div>
             <p class="text-xs text-gray-500 uppercase mb-0.5">Usuario Activo</p>
             <h2 class="text-xl font-bold text-gray-900">{{ user?.name }}</h2>
             <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
               Rol: {{ user?.roles?.[0] || 'owner' }}
             </span>
          </div>
       </div>

       <!-- Config Card -->
       <button @click="$router.push({ name: 'settings-company' })" class="bg-indigo-50 hover:bg-indigo-100 rounded-xl p-6 shadow-sm border border-indigo-100 flex items-center justify-between text-left transition-colors group">
          <div class="flex items-center gap-4">
              <div class="bg-indigo-600 p-3 rounded-lg text-white shadow-lg shadow-indigo-500/30">
                 <span class="material-icons">settings</span>
              </div>
              <div>
                 <h3 class="font-bold text-gray-900">Configuración</h3>
                 <p class="text-sm text-gray-500">Gestionar configuración de empresa</p>
              </div>
          </div>
          <span class="material-icons text-gray-400 group-hover:translate-x-1 transition-transform">arrow_forward</span>
       </button>
    </div>

    <!-- Management Modules Grid -->
     <h3 class="text-lg font-bold text-gray-800 mb-4 px-1">Módulos de Gestión</h3>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div 
          v-for="item in managementItems" 
          :key="item.name"
          class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden group"
          @click="$router.push(item.route)"
        >
           <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-gray-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
           <div class="relative z-10 flex items-center gap-4">
              <div class="p-3 rounded-lg bg-gray-50 text-gray-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                 <span class="material-icons">{{ item.icon }}</span>
              </div>
              <div>
                 <h4 class="font-bold text-gray-900">{{ item.name }}</h4>
                 <p class="text-xs text-gray-500 mt-0.5">{{ item.desc }}</p>
              </div>
           </div>
        </div>

     </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { authStore } from '../../store/auth'
import { api } from '../../api'

const user = computed(() => authStore.state.user)
const currentTenant = computed(() => authStore.state.tenant)
const branches = ref([])
const selectedBranchId = ref(null)

const managementItems = [
    { name: 'Gestión de Sucursales', desc: 'Crear y editar sedes', icon: 'store', route: { name: 'admin-branches' } }, // Assume route TODO
    { name: 'Gestión de Rubros', desc: 'Verticales de negocio', icon: 'category', route: { name: 'dashboard' } }, // Placeholder
    { name: 'Métodos de Pago', desc: 'Pasarelas y efectivo', icon: 'payments', route: { name: 'dashboard' } },
    { name: 'Gestión de Usuarios', desc: 'Staff y Accesos', icon: 'group', route: { name: 'admin-staff' } },
    { name: 'Roles y Permisos', desc: 'Seguridad Granular', icon: 'security', route: { name: 'admin-roles' } },
    { name: 'Administrar Plan', desc: 'Pagos y Facturación', icon: 'star', route: { name: 'dashboard' } }
]

const loadBranches = async () => {
    try {
        const res = await api.get('/branches')
        branches.value = res.data
        selectedBranchId.value = authStore.state.current_branch?.id || branches.value[0]?.id
    } catch (e) {
        console.error("Error loading branches", e)
    }
}

const updateBranch = () => {
    const branch = branches.value.find(b => b.id === selectedBranchId.value)
    if (branch) {
        authStore.setBranch(branch)
        window.location.reload()
    }
}

onMounted(() => {
    loadBranches()
})
</script>
