<template>
  <div v-if="visible" class="overlay" role="dialog" aria-modal="true" aria-label="Aviso de configuración inicial">
    <div class="modal">
      <h2 class="title">Configuración Inicial</h2>
      <p class="desc">
        Tu negocio aún no ha completado el Setup Fiscal. Puedes hacerlo ahora para habilitar todos los módulos, o decidir más tarde.
      </p>
      <div class="actions">
        <button class="btn-primary" type="button" @click="$emit('go-setup')">Ir al Setup Fiscal</button>
        <button class="btn-secondary" type="button" @click="$emit('dismiss')">Después</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({ visible: { type: Boolean, default: false } })
</script>

<style scoped>
.overlay { position: fixed; inset: 0; background: rgba(17,24,39,.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
.modal { background: white; border-radius: .75rem; padding: 1rem 1.25rem; max-width: 560px; width: calc(100% - 2rem); box-shadow: 0 10px 25px rgba(0,0,0,.15); }
.title { font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: .5rem; }
.desc { font-size: .875rem; color: #374151; margin-bottom: .75rem; }
.actions { display: flex; gap: .5rem; justify-content: flex-end; }
.btn-primary { background: #4f46e5; color: white; padding: .5rem .75rem; border-radius: .5rem; }
.btn-secondary { background: #f3f4f6; color: #111827; padding: .5rem .75rem; border-radius: .5rem; }
</style>

