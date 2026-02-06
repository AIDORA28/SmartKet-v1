<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <div class="mx-auto h-16 w-16 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center mb-4">
          <span class="material-icons text-3xl text-indigo-600 dark:text-indigo-400">store</span>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
          Elige tu lugar de trabajo
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Hola <strong>{{ user?.name }}</strong>, ¿en qué sucursal operarás hoy?
        </p>
      </div>

      <div class="mt-8 space-y-4">
        <button
          v-for="branch in branches"
          :key="branch.id"
          @click="selectBranch(branch)"
          class="group relative w-full flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-500 dark:hover:border-indigo-500 bg-white dark:bg-gray-800 transition-all shadow-sm hover:shadow-md"
        >
          <div class="h-10 w-10 flex-shrink-0 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900 transition-colors">
            <span class="material-icons-outlined text-indigo-600 dark:text-indigo-400">place</span>
          </div>
          <div class="ml-4 text-left">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
              {{ branch.name }}
            </h3>
            <p v-if="branch.is_active" class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-green-500"></span> Operativa
            </p>
          </div>
          <div class="absolute right-4 text-gray-300 group-hover:text-indigo-500 transition-colors">
            <span class="material-icons-outlined">arrow_forward</span>
          </div>
        </button>

        <div v-if="branches.length === 0" class="text-center p-6 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
          <p class="text-yellow-700 dark:text-yellow-400 font-medium">No tienes sucursales asignadas.</p>
          <p class="text-sm text-yellow-600 dark:text-yellow-500 mt-1">Contacta a tu administrador.</p>
        </div>
      </div>
      
       <div class="text-center mt-6">
        <button @click="logout" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 underline">
          Cerrar Sesión
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { authStore } from '@/store/auth'

const router = useRouter()
const user = computed(() => authStore.state.user)
const branches = computed(() => authStore.state.user?.branches || [])

const selectBranch = (branch) => {
  // Store selected branch in localStorage/Store
  authStore.setBranch(branch)
  router.push({ name: 'dashboard' })
}

const logout = () => {
    localStorage.removeItem('smartket_token')
    localStorage.removeItem('smartket_tenant_id')
    window.location.reload()
}
</script>
