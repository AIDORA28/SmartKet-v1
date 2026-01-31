<template>
  <div v-if="visible" class="banner">
    <div class="banner-content">
      <div class="banner-text">
        <strong>Completa la configuración de tu negocio</strong>
        <span class="desc">(RUC, comprobantes) para habilitar facturación completa. Puedes usar boleta simple mientras tanto.</span>
      </div>
      <div class="banner-actions">
        <button class="btn-primary" @click="goSetup">Ir al Setup</button>
        <button class="btn-secondary" @click="dismiss">Cerrar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const visible = ref(false)

function isSetupIncomplete() {
  const flag = localStorage.getItem('smartket_setup_complete')
  return flag === 'false'
}

function goSetup() {
  visible.value = false
  router.push({ name: 'setup-fiscal' })
}

function dismiss() {
  try { localStorage.setItem('smartket_setup_banner_dismissed', 'true') } catch {}
  visible.value = false
}

onMounted(() => {
  const dismissed = localStorage.getItem('smartket_setup_banner_dismissed') === 'true'
  visible.value = isSetupIncomplete() && !dismissed
})
</script>

<style scoped>
.banner { background: #fff7ed; border: 1px solid #fdba74; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
.banner-content { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.banner-text { display: flex; flex-direction: column; color: #9a3412; }
.desc { font-size: 12px; opacity: 0.9; }
.banner-actions { display: flex; gap: 8px; }
.btn-primary { background: #4f46e5; color: white; padding: .4rem .7rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .4rem .7rem; border-radius: .5rem; }
</style>

