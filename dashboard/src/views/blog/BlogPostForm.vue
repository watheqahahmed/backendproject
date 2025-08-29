<template>
  <div class="container mx-auto p-4 sm:p-6">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-6">
        {{ isEditing ? 'تعديل المقال' : 'إضافة مقال جديد' }}
      </h1>

      <form @submit.prevent="savePost" class="space-y-6">
        <!-- عنوان المقال -->
        <div>
          <label for="postTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            عنوان المقال
          </label>
          <input 
            type="text" 
            id="postTitle" 
            v-model="post.title" 
            required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm 
                   focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 
                   text-gray-900 dark:text-white"
          />
        </div>

        <!-- محتوى المقال -->
        <div>
          <label for="postContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            المحتوى
          </label>
          <textarea 
            id="postContent" 
            v-model="post.content" 
            rows="6" 
            required
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm 
                   focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 
                   text-gray-900 dark:text-white"
          ></textarea>
        </div>

        <!-- صورة المقال -->
        <div>
          <label for="postImage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            صورة المقال
          </label>
          <input 
            type="file" 
            id="postImage" 
            @change="handleImageUpload" 
            accept="image/*"
            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-200 
                   file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 
                   file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 
                   hover:file:bg-indigo-100"
          />
          <div v-if="previewImage" class="mt-4 w-full sm:w-48 h-auto rounded-lg overflow-hidden shadow-lg">
            <img :src="previewImage" alt="Post Image Preview" class="w-full h-full object-cover">
          </div>
        </div>

        <!-- أزرار الحفظ والإلغاء -->
        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-6">
          <button 
            type="button" 
            @click="router.push({ name: 'BlogList' })" 
            class="py-2 px-6 rounded-full text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 
                   hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-300"
          >
            إلغاء
          </button>
          <button 
            type="submit" 
            class="py-2 px-6 rounded-full bg-indigo-600 text-white font-bold 
                   hover:bg-indigo-700 transition duration-300"
          >
            {{ isEditing ? 'تحديث' : 'حفظ' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const post = ref({
  id: null,
  title: '',
  content: '',
  image: null,
});
const isEditing = ref(false);
const previewImage = ref('');
const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=blog";

const loadPost = async (id) => {
  try {
    const res = await axios.get(`${API_URL}&id=${id}`);
    if (res.data.success) {
      post.value = { ...res.data.blogPost };
      previewImage.value = post.value.image ? `http://localhost:8080/backendproject/backend/${post.value.image}` : '';
      isEditing.value = true;
    }
  } catch (error) {
    console.error("فشل في تحميل المقال", error);
  }
};

const savePost = async () => {
  const formData = new FormData();
  formData.append('title', post.value.title);
  formData.append('content', post.value.content);

  if (post.value.image instanceof File) {
    formData.append('image', post.value.image);
  } else if (!post.value.image && isEditing.value) {
    formData.append('image_path', '');
  }

  try {
    let res;
    if (isEditing.value) {
      formData.append('_method', 'PUT'); 
      res = await axios.post(`${API_URL}&id=${post.value.id}`, formData);
    } else {
      res = await axios.post(API_URL, formData);
    }
    
    if (res.data.success) {
      router.push({ name: 'BlogList' });
    }
  } catch (error) {
    console.error("فشل في حفظ المقال", error);
  }
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    post.value.image = file;
    previewImage.value = URL.createObjectURL(file);
  } else {
    post.value.image = null;
    previewImage.value = '';
  }
};

onMounted(() => {
  if (route.params.id) {
    loadPost(route.params.id);
  }
});
</script>
