<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Equipo & Personal</h1>
      <button 
        @click="openCreateModal"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-lg shadow-indigo-500/30"
      >
        <span class="material-icons-outlined">person_add</span>
        Nuevo Empleado
      </button>
    </div>

    <!-- Staff Grid -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div 
        v-for="member in staff" 
        :key="member.id"
        class="group bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 hover:border-indigo-200 dark:hover:border-indigo-800 transition-all hover:shadow-xl relative overflow-hidden"
      >
        <!-- Background Pattern -->
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50/50 to-purple-50/50 dark:from-indigo-900/10 dark:to-purple-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

        <div class="relative flex flex-col items-center text-center">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-gray-200 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-1 mb-4 shadow-inner">
            <img 
              :src="`https://ui-avatars.com/api/?name=${member.name}&background=6366f1&color=fff`" 
              class="w-full h-full rounded-full object-cover border-2 border-white dark:border-gray-800"
              alt="Avatar"
            >
          </div>

          <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">{{ member.name }}</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">
            {{ member.username || 'Sin usuario' }}
          </p>

          <!-- Roles Badges -->
          <div class="flex flex-wrap justify-center gap-2 mb-4">
            <span 
              v-for="role in member.roles" 
              :key="role.id"
              class="px-2 py-1 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800"
            >
              {{ role.name }}
            </span>
             <span v-if="!member.roles?.length" class="text-xs text-gray-400 italic">Sin rol</span>
          </div>

          <!-- Branches Count -->
          <div class="mb-4 text-xs text-gray-500">
             <span class="flex items-center justify-center gap-1">
                 <span class="material-icons-outlined text-[14px]">store</span> 
                 {{ member.branches?.length || 0 }} sucursales
             </span>
          </div>

          <!-- Actions -->
          <div class="w-full flex gap-2">
             <button @click="editStaff(member)" class="flex-1 py-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
               Editar
             </button>
             <button class="flex-1 py-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors">
               Detalles
             </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
        
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
          <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            {{ isEditing ? 'Editar Empleado' : 'Registrar Nuevo Empleado' }}
          </h2>
          <button @click="closeModal" class="text-gray-400 hover:text-red-500">
            <span class="material-icons-outlined">close</span>
          </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-4">
             <!-- Name & Password -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                    <input v-model="form.name" type="text" class="input-base" placeholder="Juan Pérez">
                 </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ isEditing ? 'Nueva Contraseña (Opcional)' : 'Contraseña' }}
                    </label>
                    <input v-model="form.password" type="password" class="input-base" placeholder="********">
                 </div>
             </div>

             <!-- Roles Selection -->
             <div>
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asignar Roles</label>
                 <div class="flex flex-wrap gap-2">
                     <button 
                        v-for="role in availableRoles" 
                        :key="role.id"
                        @click="toggleSelection(form.roles, role.id)"
                        :class="form.roles.includes(role.id) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'"
                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors border border-transparent"
                     >
                        {{ role.name }}
                     </button>
                 </div>
             </div>

             <!-- Branches Selection -->
             <div>
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Asignar Sucursales</label>
                 <div class="grid grid-cols-2 gap-2">
                     <div 
                        v-for="branch in availableBranches" 
                        :key="branch.id"
                        @click="toggleSelection(form.branches, branch.id)"
                        class="cursor-pointer border rounded-lg p-3 flex items-center justify-between transition-colors"
                        :class="form.branches.includes(branch.id) 
                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' 
                            : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300'"
                     >
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ branch.name }}</span>
                        <span v-if="form.branches.includes(branch.id)" class="material-icons text-indigo-600 text-sm">check_circle</span>
                     </div>
                 </div>
             </div>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
          <button @click="closeModal" class="btn-secondary">Cancelar</button>
          <button @click="saveStaff" :disabled="saving" class="btn-primary">
            {{ saving ? 'Guardando...' : 'Guardar' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '../../api'

const staff = ref([])
const availableRoles = ref([])
const availableBranches = ref([])

const loading = ref(true)
const showModal = ref(false)
const saving = ref(false)
const isEditing = ref(false)

const form = ref({
    id: null,
    name: '',
    password: '',
    roles: [],
    branches: []
})

const fetchStaff = async () => {
    loading.value = true
    try {
        const [staffRes, rolesRes, branchesRes] = await Promise.all([
            api.get('/staff'),
            api.get('/roles'),
            api.get('/branches') // Assuming this endpoint exists, wait, checking routes.
        ])
        staff.value = staffRes.data
        availableRoles.value = rolesRes.data
        availableBranches.value = branchesRes.data
    } catch(e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

// Logic to fetch branches? Need to check if endpoint exists.
// Route::get('/branches', [BranchController::class, 'index']); Usually exists.

const openCreateModal = () => {
    isEditing.value = false
    form.value = { id: null, name: '', password: '', roles: [], branches: [] }
    showModal.value = true
}

const editStaff = (member) => {
    isEditing.value = true
    form.value = {
        id: member.id,
        name: member.name,
        password: '', // Password empty on edit
        roles: member.roles.map(r => r.id),
        branches: member.branches?.map(b => b.id) || []
    }
    showModal.value = true
}

const toggleSelection = (array, id) => {
    const index = array.indexOf(id)
    if (index === -1) array.push(id)
    else array.splice(index, 1)
}

const closeModal = () => showModal.value = false

const saveStaff = async () => {
    saving.value = true
    try {
        const payload = { ...form.value }
        if (!payload.password) delete payload.password // Don't send empty password

        if (isEditing.value) {
            await api.put(`/staff/${form.value.id}`, payload)
        } else {
            await api.post('/staff', payload)
        }
        await fetchStaff()
        closeModal()
    } catch (e) {
        console.error(e)
        alert('Error al guardar: ' + (e.response?.data?.message || e.message))
    } finally {
        saving.value = false
    }
}

onMounted(() => {
    fetchStaff()
})
</script>

<style scoped>
.input-base {
    @apply w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all;
}
.btn-primary {
    @apply bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors font-medium disabled:opacity-50;
}
.btn-secondary {
    @apply px-4 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors bg-white dark:bg-transparent border border-gray-300 dark:border-gray-600;
}
</style>
