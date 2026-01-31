<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-2">Paso 1: Datos Fiscales</h1>
    <p class="text-sm text-gray-700 mb-4">Configura los datos fiscales de tu negocio. Puedes activar "Boleta simple" si aún no cuentas con RUC.</p>

    <form class="space-y-4" @submit.prevent="save">
      <div>
        <label class="block text-sm font-medium text-gray-700">RUC (11 dígitos)</label>
        <input v-model="form.ruc" placeholder="XXXXXXXXXXX" class="input" />
        <p class="text-xs text-gray-500">Opcional. Valida que sean 11 dígitos numéricos.</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Razón Social</label>
        <input v-model="form.razon_social" placeholder="Mi Negocio S.A.C." class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Comprobante por defecto</label>
        <select v-model="form.comprobante_default" class="input">
          <option value="">Selecciona…</option>
          <option value="boleta">Boleta</option>
          <option value="factura">Factura</option>
        </select>
      </div>

      <div class="flex items-center gap-2">
        <input id="boletaSimple" type="checkbox" v-model="form.boleta_simple_enabled" />
        <label for="boletaSimple" class="text-sm text-gray-700">Activar modo Boleta simple</label>
      </div>

      <div class="flex items-center gap-3 mt-4">
        <button class="btn-primary" :disabled="busy">Guardar</button>
        <span v-if="message" :class="msgClass" class="text-sm">{{ message }}</span>
        <router-link class="btn-secondary" :to="{ name: 'onboarding' }">Volver al Onboarding</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api, audit } from '@/api'

const form = ref({ ruc: '', razon_social: '', comprobante_default: '', boleta_simple_enabled: true })
const busy = ref(false)
const message = ref('')
const msgClass = ref('text-gray-600')

onMounted(async () => {
  try {
    const { data } = await api.get('/setup/fiscal')
    form.value = {
      ruc: data?.ruc || '',
      razon_social: data?.razon_social || '',
      comprobante_default: data?.comprobante_default || '',
      boleta_simple_enabled: data?.boleta_simple_enabled ?? true,
    }
  } catch (err) {
    message.value = err?.response?.data?.message || err.message
    msgClass.value = 'text-red-700'
  }
})

async function save() {
  message.value = ''
  busy.value = true
  try {
    // Cliente: validación simple de RUC
    if (form.value.ruc && !/^\d{11}$/.test(form.value.ruc)) {
      throw new Error('El RUC debe tener 11 dígitos numéricos')
    }
    const { data } = await api.post('/setup/fiscal', { ...form.value })
    message.value = data?.message || 'Guardado'
    msgClass.value = 'text-green-700'
    audit('setup_fiscal_saved', 'Datos fiscales guardados', '/setup/fiscal')
  } catch (err) {
    message.value = err?.response?.data?.message || err.message
    msgClass.value = 'text-red-700'
  } finally {
    busy.value = false
  }
}
</script>

<style scoped>
.input { border: 1px solid #d1d5db; border-radius: .5rem; padding: .5rem .75rem; width: 100%; }
.btn-primary { background: #4f46e5; color: white; padding: .5rem .75rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .5rem .75rem; border-radius: .5rem; text-decoration: none; }
.text-gray-900 { color: #111827 }
.text-gray-700 { color: #374151 }
.text-green-700 { color: #15803d }
.text-red-700 { color: #b91c1c }
</style>

