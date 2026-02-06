<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">Configuración Inicial</h1>
    <p class="text-sm text-gray-700 mb-4">
      Tu negocio aún no ha completado la configuración inicial. Completa los siguientes pasos para habilitar los módulos del sistema.
    </p>

    <ol class="list-decimal ml-5 space-y-2 text-sm text-gray-700">
      <li>Selecciona el tipo de negocio</li>
      <li>Configura Datos Fiscales</li>
      <li>Configura Caja y Comprobantes</li>
      <li>Importa Productos o crea manualmente</li>
    </ol>

    <div class="mt-6 flex items-center gap-3">
      <router-link class="px-4 py-2 rounded-lg bg-indigo-600 text-white" :to="{ name: 'settings-business-type' }">
        Paso 0: Tipo de Negocio
      </router-link>
      <router-link class="px-4 py-2 rounded-lg bg-indigo-600 text-white" :to="{ name: 'setup-fiscal' }">
        Paso 1: Datos Fiscales
      </router-link>
      <router-link v-if="setupComplete && rubro === 'polleria'" class="px-4 py-2 rounded-lg bg-indigo-600 text-white" :to="{ name: 'polleria-admin' }">
        Ir a Pollería
      </router-link>
      <span v-if="message" class="text-sm" :class="msgClass">{{ message }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api, audit } from '@/api'

const message = ref('')
const msgClass = ref('text-gray-600')
const setupComplete = ref(false)
const rubro = ref('')

onMounted(async () => {
  try {
    const { data } = await api.get('/tenant')
    setupComplete.value = !!data?.setup_complete
    rubro.value = data?.rubro || ''
    if (setupComplete.value) {
      localStorage.setItem('smartket_setup_complete', 'true')
      if (rubro.value) localStorage.setItem('smartket_rubro', rubro.value)
      message.value = 'Setup completado. Puedes ir al módulo de Pollería.'
      msgClass.value = 'text-green-700'
    } else {
      localStorage.setItem('smartket_setup_complete', 'false')
      audit('setup_incomplete_notice', 'Mostrando pantalla de onboarding', '/onboarding')
    }
  } catch (err) {
    message.value = err?.response?.data?.message || err.message || 'No se pudo obtener el estado del setup'
    msgClass.value = 'text-red-700'
  }
})
</script>

<style scoped>
.text-gray-900 { color: #111827 }
.text-gray-700 { color: #374151 }
.text-green-700 { color: #15803d }
.text-red-700 { color: #b91c1c }
.bg-indigo-600 { background-color: #4f46e5 }
.rounded-lg { border-radius: .5rem }
</style>
