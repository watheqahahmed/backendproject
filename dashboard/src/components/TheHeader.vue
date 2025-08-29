<template>
  <header class="bg-white dark:bg-gray-800 shadow-md p-4 flex justify-between items-center transition-colors duration-300">
    <div class="text-xl font-bold text-gray-800 dark:text-white">
      </div>
    <div class="flex items-center space-x-4">
      <button @click="themeStore.toggleTheme()" class="p-2 rounded-full transition-colors duration-200" :class="{'bg-gray-200 dark:bg-gray-700': true}">
        <svg v-if="themeStore.isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      </button>

      <div class="relative">
        <button class="flex items-center space-x-2 text-gray-800 dark:text-white">
          <span class="text-sm font-medium">Admin</span>
          <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/150" alt="User Avatar">
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme';

const themeStore = useThemeStore();
</script>