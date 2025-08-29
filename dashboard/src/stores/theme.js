import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useThemeStore = defineStore('theme', () => {
  const isDarkMode = ref(false);

  const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    localStorage.setItem('darkMode', isDarkMode.value ? 'true' : 'false');
    document.documentElement.classList.toggle('dark', isDarkMode.value);
  };

  const initializeTheme = () => {
    const saved = localStorage.getItem('darkMode');
    isDarkMode.value = saved === 'true';
    document.documentElement.classList.toggle('dark', isDarkMode.value);
  };

  return { isDarkMode, toggleTheme, initializeTheme };
});
