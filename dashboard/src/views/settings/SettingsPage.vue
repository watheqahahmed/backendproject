<template>
  <div class="p-4 sm:p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <h1 class="text-3xl sm:text-4xl font-bold mb-6 text-gray-800 dark:text-white">الإعدادات</h1>

    <!-- الوضع الداكن -->
    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
      <h2 class="text-lg sm:text-xl font-semibold mb-4 text-gray-700 dark:text-white">الوضع الداكن</h2>
      <div class="flex items-center gap-4">
        <label for="theme-toggle" class="text-gray-700 dark:text-gray-300 cursor-pointer">تفعيل الوضع الداكن</label>
        <div 
          id="theme-toggle"
          class="relative inline-flex items-center h-6 w-11 rounded-full cursor-pointer transition-colors duration-200"
          :class="themeStore.isDarkMode ? 'bg-indigo-600' : 'bg-gray-300 dark:bg-gray-600'"
          @click="toggleTheme"
        >
          <span class="sr-only">Toggle dark mode</span>
          <!-- الدائرة داخل الزر -->
          <span 
            class="absolute top-0.5 h-5 w-5 bg-white rounded-full shadow transition-all duration-200"
            :class="themeStore.isDarkMode ? 'right-0 left-auto' : 'left-0 right-auto'"
          ></span>
        </div>
      </div>
    </div>

    <!-- إعادة تعيين البيانات -->
    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-sm">
      <h2 class="text-lg sm:text-xl font-semibold mb-4 text-gray-700 dark:text-white">إدارة البيانات</h2>
      <p class="text-gray-600 dark:text-gray-300 mb-4">
        تحذير: هذه العملية ستحذف جميع البيانات المخزنة بشكل دائم ولا يمكن التراجع عنها.
      </p>
      <button 
        @click="resetData" 
        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition"
      >
        إعادة تعيين بيانات الموقع
      </button>
    </div>
  </div>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme';
import { toast } from 'vue3-toastify';
import { resetAllData } from '@/services/localStorage';

const themeStore = useThemeStore();

const toggleTheme = () => {
  themeStore.isDarkMode = !themeStore.isDarkMode;
  localStorage.setItem('darkMode', themeStore.isDarkMode);

  if (themeStore.isDarkMode) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const resetData = () => {
  if (confirm('هل أنت متأكد من أنك تريد إعادة تعيين جميع بيانات الموقع؟')) {
    resetAllData();
    toast.success('تمت إعادة تعيين بيانات الموقع بنجاح!');
    setTimeout(() => window.location.reload(), 1000);
  }
};
</script>

<style scoped>
/* تحسين المسافات على الشاشات الصغيرة */
@media (max-width: 640px) {
  h1 { font-size: 1.75rem; }
  h2 { font-size: 1.125rem; }
  .p-6 { padding: 1.25rem; }
}
</style>
