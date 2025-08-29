<template>
  <div class="flex bg-gray-100 dark:bg-gray-900 min-h-screen" dir="rtl">
    <TheSidebar />
    <main class="flex-1 transition-colors duration-300">
      <router-view />
    </main>
  </div>
</template>


<script setup>
import TheSidebar from './TheSidebar.vue';
import { useThemeStore } from '@/stores/theme';

const themeStore = useThemeStore();
themeStore.initializeTheme(); // هذا هو الاستدعاء المهم
</script>