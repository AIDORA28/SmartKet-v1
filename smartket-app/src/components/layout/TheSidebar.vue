<template>
  <div>
    <!-- Mobile sidebar overlay -->
    <transition name="slide-fade">
      <div v-if="isOpen" class="fixed inset-0 z-50 lg:hidden">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="$emit('close')"></div>
        <div class="fixed inset-0 flex">
          <div class="relative flex w-full max-w-xs flex-1 flex-col bg-[#C81E1E]"> 
            <!-- Mobile Header -->
            <div class="flex flex-shrink-0 items-center px-4 py-5 bg-[#9B1C1C]">
               <div class="flex items-center gap-3">
                  <div class="bg-white p-1.5 rounded-lg">
                    <img class="h-6 w-auto" :src="logo" alt="SmartKet" />
                  </div>
                  <span class="text-xl font-bold text-white tracking-wide">SMARTKET</span>
               </div>
               <button type="button" class="ml-auto flex h-10 w-10 items-center justify-center rounded-full text-white" @click="$emit('close')">
                <span class="sr-only">Close sidebar</span>
                <XMarkIcon class="h-6 w-6" />
              </button>
            </div>

            <!-- Mobile Nav -->
            <nav class="flex-1 space-y-1 px-2 py-4 overflow-y-auto">
               <template v-for="item in navigation" :key="item.name">
                  <router-link 
                    :to="item.href" 
                    class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg text-white/90 hover:bg-white/10 transition-colors"
                    :class="[isCurrent(item.href) ? 'bg-[#9B1C1C] text-white border-l-4 border-white' : 'border-l-4 border-transparent']"
                    @click="$emit('close')"
                  >
                    <component :is="item.icon" v-if="item.icon" class="mr-3 h-6 w-6 text-white" />
                    <span v-else class="mr-3 text-xl">{{ item.emoji }}</span>
                    {{ item.name }}
                  </router-link>
               </template>
            </nav>
          </div>
        </div>
      </div>
    </transition>

    <!-- Desktop Sidebar (v5 Style) -->
    <aside class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:inset-y-0 z-50">
      <div class="flex flex-col flex-grow bg-[#C81E1E] text-white overflow-y-auto w-full shadow-2xl"> 
        <!-- Brand Logo -->
        <div class="flex items-center h-20 px-8 bg-[#b91c1c]"> <!-- Slight contrast for header -->
           <div class="flex items-center gap-4">
              <div class="bg-white p-2 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300">
                <img class="h-8 w-8" :src="logo" alt="SmartKet Logo" />
              </div>
              <div>
                <h1 class="text-2xl font-black tracking-tight text-white leading-none">
                  SMART<span class="text-white/80 font-light">ket</span>
                </h1>
                <p class="text-[10px] font-bold text-[#fbbf24] uppercase tracking-widest mt-0.5">Enterprise</p>
              </div>
           </div>
        </div>
        
        <!-- SmartKet Label (v5 style badge) -->
        <div class="px-6 my-6">
           <div class="bg-[#9B1C1C]/50 border border-white/10 p-3 rounded-xl flex items-center justify-between">
              <div>
                <p class="text-[10px] text-white/60 uppercase font-bold">Versión</p>
                <p class="text-xs font-bold text-white">v5.0.0 PRO</p>
              </div>
              <div class="h-2 w-2 rounded-full bg-green-400 animate-pulse shadow-[0_0_10px_rgba(74,222,128,0.5)]"></div>
           </div>
        </div>

        <!-- Navigation Items -->
        <nav class="flex-1 px-5 space-y-1.5">
          <template v-for="item in navigation" :key="item.name">
            <router-link 
              :to="item.href" 
              class="group flex items-center px-4 py-4 text-[15px] font-semibold rounded-xl transition-all duration-300 ease-out relative overflow-hidden"
              :class="[
                 // Active State
                 isCurrent(item.href) 
                   ? 'bg-white text-red-700 shadow-lg shadow-black/20 scale-[1.02]' 
                   : 'text-white/90 hover:bg-[#A91C1C] hover:text-white hover:scale-[1.01] hover:shadow-md'
              ]"
            >
              <!-- Hover Background Effect -->
              <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/5 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
              
              <!-- Icon Logic: Support both Emoji and HeroIcons -->
              <div class="mr-4 flex-shrink-0 relative z-10 transition-transform duration-300 group-hover:scale-110" :class="[isCurrent(item.href) ? 'text-red-600' : '']">
                  <component :is="item.icon" v-if="item.icon" class="h-6 w-6" />
                  <span v-else class="text-2xl">{{ item.emoji }}</span>
              </div>
              
              <span class="flex-1 relative z-10">{{ item.name }}</span>

              <!-- Badge (e.g. Principal) -->
              <span v-if="item.badge" class="bg-yellow-400 text-red-900 text-[10px] font-black px-2.5 py-1 rounded-full ml-auto relative z-10 shadow-sm">
                {{ item.badge }}
              </span>
            </router-link>
          </template>
        </nav>

        <!-- Footer / Logout -->
        <div class="p-5 mt-auto border-t border-white/10">
          <button @click="$emit('logout')" class="flex w-full items-center px-4 py-4 text-[15px] font-semibold text-white/80 hover:text-white hover:bg-[#A91C1C] rounded-xl transition-all duration-300 group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/5 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
            <ArrowRightOnRectangleIcon class="mr-4 h-6 w-6 relative z-10 transition-transform duration-300 group-hover:-translate-x-1" />
            <span class="relative z-10">Cerrar Sesión</span>
          </button>
        </div>

      </div>
    </aside>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import logo from '../../assets/logo.svg'
import { 
  XMarkIcon,
  HomeIcon,
  ShoppingCartIcon,
  ArchiveBoxIcon, 
  UsersIcon,
  ChartBarIcon,
  Cog6ToothIcon,
  ArrowRightOnRectangleIcon,
  BuildingStorefrontIcon,
  BanknotesIcon,
  TruckIcon,
  ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline'

// Icons Map for v5 replication
// We will use HeroIcons to look professional like v5
const icons = {
    dashboard: HomeIcon,
    pos: BuildingStorefrontIcon, // Or ShoppingCart
    ventas: ShoppingCartIcon,
    users: UsersIcon,
    inventory: ClipboardDocumentListIcon // Placeholder
}

defineProps({
  isOpen: Boolean,
  navigation: Array
})

defineEmits(['close', 'logout'])

const route = useRoute()
const isCurrent = (path) => route.path.startsWith(path)
</script>

<style scoped>
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.3s ease-out;
}
.slide-fade-enter-from, .slide-fade-leave-to {
  opacity: 0;
  transform: translateX(-100%);
}
</style>
