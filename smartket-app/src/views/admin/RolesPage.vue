<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gestión de Roles</h1>
      <button 
        @click="openCreateModal"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
      >
        <span class="material-icons-outlined">add</span>
        Nuevo Rol
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Roles Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="role in roles" 
        :key="role.id"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow"
      >
        <div class="flex justify-between items-start mb-4">
          <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
            <span class="material-icons-outlined text-indigo-600 dark:text-indigo-400">badge</span>
          </div>
          <button @click="editRole(role)" class="text-gray-400 hover:text-indigo-600">
            <span class="material-icons-outlined">edit</span>
          </button>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ role.name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ role.description || 'Sin descripción' }}</p>
        
        <div class="flex flex-wrap gap-2">
          <span 
            class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300"
          >
            {{ role.permissions?.length || 0 }} permisos
          </span>
        </div>
      </div>
    </div>

    <!-- Modal Editor -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
        
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
          <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            {{ isEditing ? 'Editar Rol' : 'Crear Nuevo Rol' }}
          </h2>
          <button @click="closeModal" class="text-gray-400 hover:text-red-500">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto flex-1">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
             <div>
               <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre del Rol</label>
               <input 
                 v-model="form.name"
                 type="text" 
                 class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                 placeholder="Ej. Supervisor de Caja"
               >
             </div>
             <div>
               <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
               <input 
                 v-model="form.description"
                 type="text" 
                 class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                 placeholder="Breve descripción del alcance"
               >
             </div>
          </div>

          <!-- Permissions Matrix -->
          <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-icons-outlined text-indigo-500">lock_open</span>
            Permisos Asignados
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div 
              v-for="(perms, group) in groupedPermissions" 
              :key="group"
              class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50/50 dark:bg-gray-700/30"
            >
              <h4 class="font-medium text-sm text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-200 dark:border-gray-600 pb-2">
                {{ formatGroup(group) }}
              </h4>
              <div class="space-y-3">
                <label 
                  v-for="perm in perms" 
                  :key="perm.id"
                  class="flex items-center gap-3 cursor-pointer group"
                >
                  <div class="relative flex items-center">
                    <input 
                      type="checkbox" 
                      :value="perm.id"
                      v-model="form.permissions"
                      class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-indigo-600 checked:bg-indigo-600 hover:border-indigo-400"
                    >
                    <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                      <span class="material-icons-outlined text-[16px]">check</span>
                    </span>
                  </div>
                  <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition-colors">
                    {{ perm.description || perm.name }}
                  </span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
          <button 
            @click="closeModal"
            class="px-4 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
          >
            Cancelar
          </button>
          <button 
            @click="saveRole"
            :disabled="saving"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 shadow-lg shadow-indigo-500/30 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="saving" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
            {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '../../api' // Asumiendo que existe un wrapper de axios

const roles = ref([])
const permissions = ref([])
const loading = ref(true)
const showModal = ref(false)
const saving = ref(false)
const isEditing = ref(false)

const form = ref({
  id: null,
  name: '',
  description: '',
  permissions: []
})

// Fetch Data
const fetchData = async () => {
  loading.value = true
  try {
    const [rolesRes, permsRes] = await Promise.all([
      api.get('/roles'),
      api.get('/permissions')
    ])
    roles.value = rolesRes.data
    permissions.value = permsRes.data
  } catch (error) {
    console.error('Error fetching RBAC data:', error)
  } finally {
    loading.value = false
  }
}

// Group Permissions by prefix (e.g., 'products.create' -> Group 'products')
const groupedPermissions = computed(() => {
  return permissions.value.reduce((acc, perm) => {
    const group = perm.name.split('.')[0] || 'general'
    if (!acc[group]) acc[group] = []
    acc[group].push(perm)
    return acc
  }, {})
})

const formatGroup = (group) => {
  const map = {
    'products': 'Inventario & Productos',
    'sales': 'Ventas & Caja',
    'staff': 'Personal & RRHH',
    'reports': 'Reportes & BI',
    'settings': 'Configuración'
  }
  return map[group] || group.charAt(0).toUpperCase() + group.slice(1)
}

// Actions
const openCreateModal = () => {
  isEditing.value = false
  form.value = { id: null, name: '', description: '', permissions: [] }
  showModal.value = true
}

const editRole = (role) => {
  isEditing.value = true
  // Clonamos el objeto para no mutar directamente
  form.value = {
    id: role.id,
    name: role.name,
    description: role.description,
    permissions: role.permissions.map(p => p.id)
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const saveRole = async () => {
  saving.value = true
  try {
    if (isEditing.value) {
      await api.put(`/roles/${form.value.id}`, form.value)
    } else {
      await api.post('/roles', form.value)
    }
    await fetchData() // Refresh
    closeModal()
  } catch (error) {
    console.error('Error saving role:', error)
    // Aquí podrías agregar una notificación toast
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  fetchData()
})
</script>
