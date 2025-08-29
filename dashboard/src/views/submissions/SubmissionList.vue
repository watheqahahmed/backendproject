<template>
  <div class="p-4 sm:p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <!-- عنوان الصفحة -->
    <div class="mb-6">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
        إدارة الرسائل
      </h1>
    </div>

    <!-- عرض كجدول على الشاشات الكبيرة -->
    <div class="hidden md:block overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">#</th>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">الاسم</th>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">البريد الإلكتروني</th>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">الرسالة</th>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">تاريخ الإرسال</th>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">إجراءات</th>
          </tr>
        </thead>

        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-if="submissions.length === 0">
            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
              لا توجد رسائل حتى الآن.
            </td>
          </tr>

          <tr v-for="submission in submissions" :key="submission.id">
            <td class="px-4 sm:px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ submission.id }}</td>
            <td class="px-4 sm:px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ submission.name }}</td>
            <td class="px-4 sm:px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ submission.email }}</td>
            <td class="px-4 sm:px-6 py-3 text-sm text-gray-900 dark:text-gray-100 break-words max-w-xs sm:max-w-md">
              {{ submission.message }}
            </td>
            <td class="px-4 sm:px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
              {{ new Date(submission.created_at).toLocaleDateString() }}
            </td>
            <td class="px-4 sm:px-6 py-3 text-sm font-medium flex gap-2 justify-end">
              <button 
                @click="deleteSubmission(submission.id)" 
                class="py-1 px-3 rounded-full text-red-600 hover:text-red-900 border border-red-600 hover:border-red-900 transition text-xs sm:text-sm"
              >
                حذف
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- عرض كبطاقات على الشاشات الصغيرة -->
    <div class="space-y-4 md:hidden">
      <div 
        v-for="submission in submissions" 
        :key="submission.id" 
        class="bg-white dark:bg-gray-800 rounded-lg shadow p-4"
      >
        <div class="flex justify-between items-center border-b pb-2 mb-2">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ submission.name }}
          </h2>
          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ new Date(submission.created_at).toLocaleDateString() }}
          </span>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300 break-words mb-2">
          {{ submission.message }}
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
          {{ submission.email }}
        </p>
        <div class="flex justify-end">
          <button 
            @click="deleteSubmission(submission.id)" 
            class="py-1 px-3 rounded-full text-red-600 hover:text-red-900 border border-red-600 hover:border-red-900 transition text-xs"
          >
            حذف
          </button>
        </div>
      </div>

      <!-- حالة عدم وجود رسائل -->
      <p v-if="submissions.length === 0" class="text-center text-sm text-gray-500 dark:text-gray-400">
        لا توجد رسائل حتى الآن.
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const submissions = ref([]);
const API_URL = "http://localhost:8080/backendproject/backend/routes/api.php?request=submissions";

const fetchSubmissions = async () => {
  try {
    const res = await axios.get(API_URL);
    if (res.data.success) {
      submissions.value = res.data.submissions;
    }
  } catch (error) {
    console.error("فشل في جلب الرسائل:", error);
  }
};

const deleteSubmission = async (id) => {
  if (confirm('هل أنت متأكد من حذف هذه الرسالة؟')) {
    try {
      const res = await axios.delete(`${API_URL}&id=${id}`);
      if (res.data.success) {
        fetchSubmissions();
      }
    } catch (error) {
      console.error("فشل في حذف الرسالة:", error);
    }
  }
};

onMounted(() => {
  fetchSubmissions();
});
</script>
