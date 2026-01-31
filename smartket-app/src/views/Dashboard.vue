<template>
  <div>
    <!-- Encabezado de Bienvenida -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Bienvenido de vuelta, [Nombre de Usuario]</h1>
      <p class="text-gray-600">Aquí tienes un resumen de tu negocio hoy, 8 de Noviembre de 2025.</p>
    </div>

    <!-- Cuadrícula de Métricas y Acciones -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Card: Ventas del Día -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-gray-500 text-sm font-medium">Ventas del Día</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">S/ 1,250.50</p>
        <p class="text-green-500 text-sm mt-1">+15% vs ayer</p>
      </div>

      <!-- Card: Pedidos Hoy -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-gray-500 text-sm font-medium">Pedidos Hoy</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">82</p>
        <p class="text-green-500 text-sm mt-1">+5% vs ayer</p>
      </div>

      <!-- Card: Ticket Promedio -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-gray-500 text-sm font-medium">Ticket Promedio</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">S/ 15.25</p>
        <p class="text-red-500 text-sm mt-1">-2% vs ayer</p>
      </div>

      <!-- Card: Acción Rápida - Nueva Venta -->
      <div class="bg-red-600 text-white p-6 rounded-lg shadow-md flex flex-col items-center justify-center text-center hover:bg-red-700 transition-colors cursor-pointer">
        <h3 class="text-lg font-bold">Nueva Venta</h3>
        <p class="text-sm opacity-80">Ir al Punto de Venta (POS)</p>
      </div>

    </div>

    <!-- Sección de Gráfico y Productos Top -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
      
      <!-- Gráfico de Ventas Semanales -->
      <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Ventas de la Semana</h3>
        <div class="flex items-center justify-center h-64 bg-gray-100 rounded-md">
          <p class="text-gray-500">[Placeholder para Gráfico de Barras]</p>
        </div>
      </div>

      <!-- Productos Más Vendidos -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Productos Top del Día</h3>
        <ul class="space-y-4">
          <li class="flex justify-between items-center">
            <span>🍗 1/4 Pollo a la Brasa</span>
            <span class="font-bold text-gray-700">35 uds.</span>
          </li>
          <li class="flex justify-between items-center">
            <span>🍟 Papas Fritas (Personal)</span>
            <span class="font-bold text-gray-700">28 uds.</span>
          </li>
          <li class="flex justify-between items-center">
            <span>🥤 Inka Kola (1.5L)</span>
            <span class="font-bold text-gray-700">22 uds.</span>
          </li>
          <li class="flex justify-between items-center">
            <span>🥗 Ensalada Fresca</span>
            <span class="font-bold text-gray-700">15 uds.</span>
          </li>
          <li class="flex justify-between items-center">
            <span>🍮 Crema Volteada</span>
            <span class="font-bold text-gray-700">12 uds.</span>
          </li>
        </ul>
      </div>

    </div>
  </div>
</template>

<script setup>
// En el futuro, aquí se importarán los servicios para obtener los datos reales de la API.
// import { onMounted, ref } from 'vue';
// import api from '@/api';

// const salesToday = ref(0);
// onMounted(async () => {
//   const response = await api.get('/dashboard/kpis');
//   salesToday.value = response.data.salesToday;
// });
</script>
