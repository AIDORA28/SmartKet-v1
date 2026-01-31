<template>
  <div>
    <!-- Mobile sidebar overlay -->
    <transition name="slide-fade">
      <div v-if="isOpen" class="fixed inset-0 z-50 lg:hidden">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="$emit('close')"></div>
        <div class="fixed inset-0 flex">
          <div class="relative flex w-full max-w-xs flex-1 flex-col bg-gradient-to-b from-red-600 to-red-800">
            <div class="absolute right-0 top-0 -mr-12 pt-2">
              <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="$emit('close')">
                <span class="sr-only">Close sidebar</span>
                <XMarkIcon class="h-6 w-6 text-white" />
              </button>
            </div>
            <div class="flex flex-shrink-0 items-center px-6 py-5">
              <img class="h-8 w-auto filter brightness-0 invert" :src="logo" alt="SmartKet" />
              <span class="ml-3 text-xl font-bold text-white uppercase tracking-wider">SmartKet</span>
            </div>
            <nav class="flex-1 space-y-1 px-4 pb-4 overflow-y-auto">
               <template v-for="item in navigation" :key="item.name">
                  <router-link 
                    v-if="!item.expandable"
                    :to="item.href" 
                    class="group flex items-center px-3 py-2 text-sm font-semibold rounded-md transition-all duration-200"
                    :class="[isCurrent(item.href) ? 'bg-red-700 text-white shadow-lg scale-[1.02]' : 'text-red-100 hover:bg-red-700 hover:text-white hover:pl-5']"
                    @click="$emit('close')"
                  >
                    <span class="mr-3 text-lg transition-transform group-hover:scale-110">{{ item.emoji }}</span>
                    {{ item.name }}
                  </router-link>
                  <div v-else>
                     <button 
                      @click="toggleExpand(item.name)"
                      class="w-full flex items-center justify-between px-3 py-2 text-sm font-semibold rounded-md text-red-100 hover:bg-red-700 hover:text-white transition-all duration-200"
                    >
                      <div class="flex items-center">
                        <span class="mr-3 text-lg">{{ item.emoji }}</span>
                        {{ item.name }}
                      </div>
                      <ChevronDownIcon :class="[expanded[item.name] ? 'rotate-180' : '', 'h-4 w-4 transition-transform duration-300']" />
                    </button>
                    <transition name="expand">
                      <div v-if="expanded[item.name]" class="mt-1 ml-8 space-y-1 overflow-hidden">
                        <router-link 
                          v-for="sub in item.subModules" 
                          :key="sub.name"
                          :to="sub.href"
                          class="block px-3 py-2 text-xs font-medium rounded-md text-red-100 hover:bg-red-700 hover:text-white transition-all duration-200"
                          :class="[isCurrent(sub.href) ? 'bg-red-700 text-white shadow-md' : '']"
                          @click="$emit('close')"
                        >
                          {{ sub.name }}
                        </router-link>
                      </div>
                    </transition>
                  </div>
               </template>
            </nav>
          </div>
        </div>
      </div>
    </transition>

    <!-- Static sidebar for desktop -->
    <aside class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:inset-y-0 z-50">
      <div class="flex flex-col flex-grow bg-gradient-to-b from-red-600 to-red-800 overflow-y-auto shadow-2xl">
        <div class="flex items-center flex-shrink-0 px-6 py-8">
          <img class="h-10 w-auto filter brightness-0 invert" :src="logo" alt="SmartKet" />
          <div class="ml-4">
            <h1 class="text-white font-black text-xl leading-none">SMART<span class="text-yellow-400">KET</span></h1>
            <p class="text-red-200 text-[10px] uppercase tracking-[0.2em] font-bold">Business Suite</p>
          </div>
        </div>
        <nav class="flex-1 px-4 space-y-2 mt-4 pb-8">
          <template v-for="item in navigation" :key="item.name">
            <div v-if="item.expandable">
              <button 
                @click="toggleExpand(item.name)"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-bold rounded-xl transition-all duration-300 border border-transparent"
                :class="[expanded[item.name] ? 'bg-red-900/40 border-red-500/30' : 'text-red-100 hover:bg-red-700/50 hover:text-white']"
              >
                <div class="flex items-center">
                  <span class="mr-3 text-xl bg-red-500/20 w-8 h-8 flex items-center justify-center rounded-lg transition-transform group-hover:rotate-12">{{ item.emoji }}</span>
                  {{ item.name }}
                </div>
                <ChevronDownIcon :class="[expanded[item.name] ? 'rotate-180' : '', 'h-4 w-4 transition-transform duration-300']" />
              </button>
              <transition name="expand">
                <div v-if="expanded[item.name]" class="mt-2 ml-6 space-y-1 border-l-2 border-red-500/20 overflow-hidden">
                  <router-link 
                    v-for="sub in item.subModules" 
                    :key="sub.name"
                    :to="sub.href"
                    class="block ml-4 px-4 py-2 text-xs font-semibold rounded-lg text-red-100 hover:bg-red-700 hover:text-white transition-all duration-200 hover:translate-x-1"
                    :class="[isCurrent(sub.href) ? 'bg-red-700 text-white shadow-md' : '']"
                  >
                    {{ sub.name }}
                  </router-link>
                </div>
              </transition>
            </div>
            <router-link 
              v-else
              :to="item.href" 
              class="group flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-300 border border-transparent"
              :class="[isCurrent(item.href) ? 'bg-white text-red-700 shadow-xl scale-[1.03] translate-x-1' : 'text-red-100 hover:bg-red-700/50 hover:text-white hover:translate-x-1']"
            >
              <span class="mr-3 text-xl w-8 h-8 flex items-center justify-center rounded-lg transition-all group-hover:scale-110" :class="[isCurrent(item.href) ? 'bg-red-100' : 'bg-red-500/20']">{{ item.emoji }}</span>
              {{ item.name }}
              <div v-if="item.badge" class="ml-auto bg-yellow-400 text-red-900 text-[10px] px-2 py-0.5 rounded-full font-black animate-pulse">{{ item.badge }}</div>
            </router-link>
          </template>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-red-500/20">
          <button @click="$emit('logout')" class="flex w-full items-center px-4 py-3 text-sm font-bold text-red-200 hover:bg-red-700 hover:text-white rounded-xl transition-all group">
            <span class="mr-3 text-xl transition-transform group-hover:-translate-x-1">🚪</span>
            Cerrar Sesión
          </button>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import logo from '../../assets/logo.svg'
import { 
  XMarkIcon, 
  ChevronDownIcon
} from '@heroicons/vue/24/outline'

defineProps({
  isOpen: Boolean,
  navigation: Array
})

defineEmits(['close', 'logout'])

const route = useRoute()
const expanded = ref({})

const isCurrent = (path) => route.path.startsWith(path)

const toggleExpand = (name) => {
  expanded.value[name] = !expanded.value[name]
}
</script>

<style scoped>
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-enter-from, .slide-fade-leave-to {
  opacity: 0;
  transform: translateX(-100%);
}

.expand-enter-active, .expand-leave-active {
  transition: all 0.3s ease-out;
  max-height: 200px;
}
.expand-enter-from, .expand-leave-to {
  max-height: 0;
  opacity: 0;
  transform: translateY(-10px);
}
</style>
