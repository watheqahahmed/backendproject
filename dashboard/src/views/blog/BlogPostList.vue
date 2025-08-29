<template>
  <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800 dark:text-white">إدارة المدونة</h1>
      <button @click="goToCreate" class="bg-indigo-600 text-white font-semibold py-2 px-6 rounded-full hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
        أضف مقال جديد
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-if="!blogPosts || blogPosts.length === 0" class="col-span-full text-center text-gray-500 dark:text-gray-400">
        لا توجد مقالات حتى الآن.
      </div>

      <div 
        v-for="post in blogPosts" 
        :key="post.id" 
        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1"
      >
        <img :src="getImageUrl(post.image)" :alt="post.title" class="w-full h-48 object-cover" />
        
        <div class="p-6">
          <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">{{ post.title }}</h2>
          <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4">{{ post.content }}</p>
          
          <div class="flex justify-between items-center space-x-4 mt-4">
            <button @click="editPost(post.id)" class="text-indigo-600 hover:text-indigo-900 font-medium transition duration-300">تعديل</button>
            <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-900 font-medium transition duration-300">حذف</button>
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

const router = useRouter();
const blogPosts = ref([]);
const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=blog";

const fetchBlogPosts = async () => {
  try {
    const res = await axios.get(API_URL);
    if (res.data.success) {
      blogPosts.value = res.data.blogPosts;
    }
  } catch (err) {
    console.error("فشل في جلب المقالات", err);
  }
};

const getImageUrl = (path) => path ? `http://localhost:8080/backendproject/backend/${path}` : 'https://via.placeholder.com/400x200?text=No+Image';

const goToCreate = () => router.push({ name: 'BlogNew' });
const editPost = (id) => router.push({ name: 'BlogEdit', params: { id } });

const deletePost = async (id) => {
  if (!confirm('هل تريد حذف المقال؟')) return;
  try {
    const res = await axios.delete(`${API_URL}&id=${id}`);
    if (res.data.success) {
      fetchBlogPosts();
    }
  } catch (err) { 
    console.error('فشل في حذف المقال:', err); 
  }
};

onMounted(() => {
  fetchBlogPosts();
});
</script>