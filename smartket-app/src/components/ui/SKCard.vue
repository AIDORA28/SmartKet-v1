<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
    <div v-if="$slots.header || title" class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-white group-hover:bg-gray-50 transition-colors duration-300">
      <slot name="header">
        <h3 class="text-sm font-black text-gray-800 uppercase tracking-[0.1em]">{{ title }}</h3>
      </slot>
    </div>
    <div :class="['px-6 py-5', padding]">
      <slot />
    </div>
    <div v-if="$slots.footer" class="px-6 py-4 bg-gray-50/50 border-t border-gray-50 group-hover:bg-gray-100 transition-colors duration-300">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  padding: {
    type: String,
    default: ''
  }
})
</script>
