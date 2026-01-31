<template>
  <div class="min-h-[80vh] flex flex-col items-center justify-center p-8 text-center">
    <div class="relative mb-8">
      <div class="absolute inset-0 bg-indigo-100 dark:bg-indigo-900/30 rounded-full animate-ping opacity-75"></div>
      <div class="relative bg-white dark:bg-gray-800 p-6 rounded-full shadow-xl border border-gray-100 dark:border-gray-700">
        <span class="text-6xl">🚧</span>
      </div>
    </div>
    
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">
      Módulo <span class="text-indigo-600 dark:text-indigo-400">En Construcción</span>
    </h1>
    
    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mb-8 leading-relaxed">
      Estamos trabajando meticulosamente en el módulo de <span class="font-bold text-gray-800 dark:text-gray-200">{{ moduleName }}</span> para brindarte una experiencia Enterprise.
      <br>Pronto podrás gestionar esta vertical con la potencia de SmartKet.
    </p>

    <!-- Roadmap Steps -->
    <div class="flex flex-col md:flex-row gap-6 md:gap-12 mb-12">
      <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold mb-2 shadow-lg shadow-green-500/30">✓</div>
        <span class="text-sm font-medium text-gray-500">Diseño</span>
      </div>
      <div class="h-px w-16 bg-gray-300 dark:bg-gray-700 hidden md:block mt-4"></div>
      <div class="flex flex-col items-center">
         <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold mb-2 animate-pulse shadow-lg shadow-indigo-500/30">2</div>
         <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">Desarrollo</span>
      </div>
      <div class="h-px w-16 bg-gray-300 dark:bg-gray-700 hidden md:block mt-4"></div>
      <div class="flex flex-col items-center">
        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-400 flex items-center justify-center font-bold mb-2">3</div>
        <span class="text-sm font-medium text-gray-500">QA</span>
      </div>
    </div>

    <button 
      @click="$router.push({ name: 'dashboard' })"
      class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700 hover:border-indigo-300 transition-all transform hover:-translate-y-1"
    >
      Volver al Dashboard
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const moduleName = computed(() => {
  const map = {
    'ventas': 'Ventas & Facturación',
    'inventario': 'Inventario Avanzado',
    'clientes': 'CRM & Clientes',
    'proveedores': 'Gestión de Proveedores',
    'finanzas': 'Finanzas & Gastos',
    'reportes': 'Business Intelligence'
  }
  // Extract last part of path
  const part = route.path.split('/').pop()
  return map[part] || 'Avanzado'
})
</script>
