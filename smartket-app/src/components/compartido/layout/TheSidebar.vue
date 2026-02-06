<template>
  <div>
    <!-- Mobile Overlay -->
    <transition name="fade">
      <div
        v-if="isOpen"
        @click="$emit('close')"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
      />
    </transition>

    <!-- Mobile Sidebar -->
    <transition name="slide">
      <aside
        v-if="isOpen"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-[#1a1a1a] lg:hidden overflow-y-auto"
      >
        <div class="flex flex-col h-full">
          <SidebarContent
            :is-collapsed="false"
            :navigation="navigation"
            @close="$emit('close')"
            @logout="$emit('logout')"
          />
        </div>
      </aside>
    </transition>

    <!-- Desktop Sidebar -->
    <aside
      :class="[
        'hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:z-50 transition-all duration-300 ease-in-out bg-[#1a1a1a] border-r border-white/5',
        isCollapsed ? 'lg:w-20' : 'lg:w-72'
      ]"
    >
      <div class="flex flex-col h-full">
        <SidebarContent
          :is-collapsed="isCollapsed"
          :navigation="navigation"
          @toggle-collapse="toggleCollapse"
          @logout="$emit('logout')"
        />
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import {
  LayoutDashboard,
  ShoppingBag,
  Package,
  Users,
  TrendingUp,
  FileText,
  DollarSign,
  Settings,
  LogOut,
  ChevronLeft,
  ChevronRight,
  Store,
  Briefcase,
  BarChart3,
  Boxes
} from 'lucide-vue-next'
import SidebarContent from './SidebarContent.vue'

defineProps({
  isOpen: Boolean,
  navigation: Array
})

defineEmits(['close', 'logout'])

const isCollapsed = ref(false)

const toggleCollapse = () => {
  isCollapsed.value = !isCollapsed.value
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
}
</style>
