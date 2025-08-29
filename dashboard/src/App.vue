<template>
  <div dir="rtl" class="flex h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-sans transition-colors duration-300">
    
    <!-- Overlay للشاشات الصغيرة -->
    <div 
      v-if="showSidebar && openSidebar && isMobile" 
      @click="toggleSidebar" 
      class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <aside 
      v-if="showSidebar"
      :class="[ 
        'flex flex-col bg-gray-900 dark:bg-gray-800 p-4 shadow-lg transition-all duration-300 ease-in-out z-50',
        openSidebar ? 'w-64' : 'w-16',
        isMobile ? 'fixed right-0 top-0 h-full transform' : 'relative'
      ]"
      :style="isMobile && !openSidebar ? 'transform: translateX(100%);' : ''"
    >
      <!-- Sidebar Header -->
      <div class="flex items-center justify-between py-6 border-b border-gray-700">
        <h1 v-if="openSidebar" class="text-xl font-bold text-white tracking-wide">لوحة التحكم</h1>
        <button @click="toggleSidebar" class="text-white focus:outline-none">
          <i class="fas fa-bars"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 mt-8">
        <ul class="space-y-2">
          <li v-for="item in menuItems" :key="item.name">
            <router-link 
              :to="item.path"
              class="flex items-center p-3 rounded-lg text-white font-medium hover:bg-indigo-600 hover:shadow-md transition-all duration-300 ease-in-out"
            >
              <span v-if="openSidebar" class="mr-3">{{ item.name }}</span>
              <i :class="item.icon + ' w-6 text-center'"></i>
            </router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-6 sm:p-8">
      <!-- زر القائمة يظهر فقط على الموبايل -->
      <button 
        v-if="showSidebar"
        @click="toggleSidebar" 
        class="lg:hidden mb-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md"
      >
        القائمة
      </button>

      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const openSidebar = ref(false);
const isMobile = ref(window.innerWidth < 1024);

const toggleSidebar = () => {
  openSidebar.value = !openSidebar.value;
};

const handleResize = () => {
  isMobile.value = window.innerWidth < 1024;
  if (!isMobile.value) openSidebar.value = true;
};

onMounted(() => {
  handleResize();
  window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize);
});

// إخفاء Sidebar في صفحات تسجيل الدخول والتسجيل
const showSidebar = computed(() => !(route.name === 'Login' || route.name === 'SignUp'));

const menuItems = [
  { name: 'الرئيسية', path: '/home', icon: 'fas fa-chart-line' },
  { name: 'المشاريع', path: '/projects', icon: 'fas fa-project-diagram' },
  { name: 'المدونة', path: '/blog', icon: 'fas fa-newspaper' },
  { name: 'الرسائل', path: '/submissions', icon: 'fas fa-envelope' },
  { name: 'المستخدمين', path: '/users', icon: 'fas fa-users' },
  { name: 'الإعدادات', path: '/settings', icon: 'fas fa-cog' }
];
</script>
