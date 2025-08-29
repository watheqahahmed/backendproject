<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center">
    <div class="p-3 bg-indigo-100 rounded-full mr-4 text-indigo-600 dark:bg-indigo-600 dark:text-white">
      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
    </div>
    <div>
      <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">{{ title }}</h3>
      <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ value }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
});
</script>