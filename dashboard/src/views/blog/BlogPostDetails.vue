<!-- <template>
  <div class="container mx-auto p-6 max-w-3xl bg-white dark:bg-gray-800 rounded-lg shadow-xl">
    <button @click="goBack" class="mb-4 text-indigo-600 hover:underline">رجوع إلى القائمة</button>

    <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">{{ post.title }}</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-6">تاريخ النشر: {{ formattedDate }}</p>
    
    <img v-if="post.imageUrl" :src="post.imageUrl" :alt="post.title" class="w-full max-h-96 object-cover rounded-lg mb-6" />
    
    <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
      {{ post.content }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { getById } from '@/services/localStorage.js';

const route = useRoute();
const router = useRouter();

const post = ref({
  title: '',
  content: '',
  imageUrl: '',
  date: null,
});

// تحميل المقال حسب المعرف
const loadPost = (id) => {
  const foundPost = getById('blogPosts', parseInt(id));
  if (foundPost) {
    post.value = foundPost;
  }
};

// صياغة التاريخ بشكل جميل (إذا لديك حقل تاريخ)
const formattedDate = computed(() => {
  if (!post.value.date) return 'غير معروف';
  return new Date(post.value.date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' });
});

const goBack = () => {
  router.back();
};

onMounted(() => {
  loadPost(route.params.id);
});
</script> -->
