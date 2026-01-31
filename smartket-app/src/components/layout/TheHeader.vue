<template>
  <header class="sticky top-0 z-40 flex h-20 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="$emit('open-sidebar')">
      <span class="sr-only">Open sidebar</span>
      <Bars3Icon class="h-6 w-6" />
    </button>

    <!-- Dynamic Context Info -->
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center">
      <div class="flex items-center space-x-3">
        <div class="h-10 w-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
          {{ currentTenant?.business_name?.charAt(0) || 'S' }}
        </div>
        <div>
          <h2 class="text-sm font-bold text-gray-900 leading-none">{{ currentTenant?.business_name || 'SmartKet' }}</h2>
          <p class="text-[10px] text-gray-500 uppercase mt-1">Sucursal: {{ currentSucursal?.nombre || 'Principal' }}</p>
        </div>
      </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-x-4 lg:gap-x-6">
      <!-- Multi-business Switcher -->
      <div v-if="hasMultipleTenants" class="relative">
        <button @click="switchingBusiness = !switchingBusiness" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-all border border-gray-200 active:scale-95">
           <span class="text-lg transition-transform group-hover:scale-110">🏢</span>
           <span class="hidden sm:inline text-xs font-bold text-gray-700">Cambiar Negocio</span>
           <ChevronDownIcon :class="[switchingBusiness ? 'rotate-180' : '', 'h-3 w-3 text-gray-400 transition-transform duration-300']" />
        </button>
        
        <transition name="dropdown">
          <div v-if="switchingBusiness" class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 py-3 z-50 transform origin-top-right">
              <div class="px-4 py-2 border-b border-gray-50 mb-2">
                  <p class="text-[10px] font-black text-gray-400 uppercase">Mis Negocios</p>
              </div>
              <button 
                v-for="t in accessibleTenants" 
                :key="t.id"
                @click="handleSwitch(t)"
                class="w-full flex items-center px-4 py-3 hover:bg-red-50 transition-all text-left group"
                :class="[t.id === currentTenant?.id ? 'bg-red-50 border-r-4 border-red-600' : '']"
              >
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 font-bold text-gray-500 group-hover:bg-red-600 group-hover:text-white transition-all transform group-hover:rotate-6">
                  {{ t.business_name.charAt(0) }}
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-800 leading-tight">{{ t.business_name }}</p>
                  <p class="text-[10px] text-gray-400 uppercase">{{ t.business_type || 'General' }}</p>
                </div>
                <CheckIcon v-if="t.id === currentTenant?.id" class="ml-auto h-4 w-4 text-red-600 animate-in zoom-in" />
              </button>
          </div>
        </transition>
      </div>

      <div class="h-8 w-px bg-gray-200"></div>

      <!-- User Menu -->
      <div class="flex items-center space-x-3 group cursor-pointer">
         <div class="text-right hidden sm:block">
            <p class="text-xs font-bold text-gray-900 leading-none group-hover:text-red-600 transition-colors">{{ user?.name }}</p>
            <p class="text-[10px] text-gray-500 uppercase mt-1">{{ user?.roles?.[0] || 'Staff' }}</p>
         </div>
         <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-md transition-all group-hover:border-red-600 group-hover:shadow-lg">
            <span class="font-bold text-gray-600 group-hover:scale-110 transition-transform">{{ user?.name?.charAt(0) }}</span>
         </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
.dropdown-enter-active, .dropdown-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.dropdown-enter-from, .dropdown-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-10px);
}
</style>

<script setup>
import { ref } from 'vue'
import { 
  Bars3Icon, 
  ChevronDownIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

defineProps({
  user: Object,
  currentTenant: Object,
  currentSucursal: Object,
  accessibleTenants: Array,
  hasMultipleTenants: Boolean
})

const emit = defineEmits(['open-sidebar', 'switch-tenant'])

const switchingBusiness = ref(false)

const handleSwitch = (tenant) => {
  switchingBusiness.value = false
  emit('switch-tenant', tenant)
}
</script>
