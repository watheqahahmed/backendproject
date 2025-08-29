<template>
  <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
      <h1 class="text-3xl font-bold text-gray-800 dark:text-white">إدارة المشاريع</h1>
      <button
        @click="router.push({ name: 'ProjectNew' })"
        class="bg-indigo-600 text-white font-semibold py-2 px-6 rounded-full hover:bg-indigo-700 transition duration-300 transform hover:scale-105"
      >
        أضف مشروع جديد
      </button>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-if="!projects || projects.length === 0" class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
        لا توجد مشاريع حتى الآن.
      </div>

      <div
        v-for="project in projects"
        :key="project.id"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1"
      >
        <!-- Project Image -->
        <div class="w-full h-48 sm:h-56 md:h-60 lg:h-48 overflow-hidden">
          <img
            :src="getBackendUrl(project.image)"
            :alt="project.name"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
          />
        </div>

        <!-- Project Info -->
        <div class="p-4 sm:p-6">
          <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2 truncate">{{ project.name }}</h2>
          <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4">{{ project.description }}</p>

          <!-- Actions -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mt-4">
            <button
              @click="router.push({ name: 'ProjectEdit', params: { id: project.id } })"
              class="text-indigo-600 hover:text-indigo-900 font-medium transition duration-300"
            >
              تعديل
            </button>
            <button
              @click="deleteProject(project.id)"
              class="text-red-600 hover:text-red-900 font-medium transition duration-300"
            >
              حذف
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const projects = ref([]);
const router = useRouter();
const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=projects";

const fetchProjects = async () => {
  try {
    const res = await axios.get(API_URL);
    if (res.data.success) {
      projects.value = res.data.projects;
    }
  } catch (err) {
    console.error("فشل في جلب المشاريع", err);
  }
};

const deleteProject = async (id) => {
  if (!confirm("هل أنت متأكد من الحذف؟")) return;
  try {
    await axios.delete(`${API_URL}&id=${id}`);
    fetchProjects(); // إعادة جلب القائمة بعد الحذف
  } catch (err) {
    console.error("فشل في حذف المشروع", err);
  }
};

const getBackendUrl = (imagePath) => {
  if (!imagePath) {
    return 'https://via.placeholder.com/400x200?text=No+Image';
  }
  return `http://localhost:8080/backendproject/backend/${imagePath}`;
};

onMounted(() => {
  fetchProjects();
});
</script>
