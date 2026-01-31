<template>
  <div class="p-6">
    <h1>Productos</h1>

    <div v-if="loading">Cargando...</div>
    <div v-else-if="error" class="text-red-600">Error: {{ error }}</div>

    <ul v-else>
      <li v-for="p in productos" :key="p.id">
        {{ p.name }} — {{ p.price }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/api'

const productos = ref([])
const loading = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    const { data } = await api.get('/productos')
    productos.value = Array.isArray(data) ? data : []
  } catch (e) {
    error.value = e?.response?.data?.message || e.message || 'Error inesperado'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped></style>
